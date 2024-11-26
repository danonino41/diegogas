<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Procesar solicitud de cambio de estado y asignación de motorizado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido']) && isset($_POST['nuevo_estado'])) {
    $idPedido = $_POST['id_pedido'];
    $nuevoEstadoId = $_POST['nuevo_estado'];
    $idMotorizado = $_POST['id_motorizado'] ?? null;

    // Actualizar el estado del pedido y asignar el motorizado si se proporciona
    actualizarEstadoPedido($idPedido, $nuevoEstadoId, $idMotorizado);
    header("Location: estado_pedidos.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;

    if ($id_pedido && cancelarVenta($id_pedido)) {
        header("Location: estado_pedidos.php?mensaje=pedido_cancelado");
    } else {
        header("Location: estado_pedidos.php?error=no_se_pudo_cancelar");
    }
    exit();
}



// Obtener los pedidos por estado
$pedidosPendientes = obtenerPedidosPorEstado('Pendiente');
$pedidosPreparados = obtenerPedidosPorEstado('Preparado');
$pedidosEnCamino = obtenerPedidosPorEstado('En camino');
$pedidosEntregados = obtenerPedidosPorEstado('Entregado');
$pedidosCancelados = obtenerPedidosPorEstado('Cancelado');

// Obtener lista de motorizados
$motorizados = obtenerMotorizados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Pedidos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'navbar_recepcion.php'; ?>

<div class="container mt-4">
    <h1>Estado de Pedidos</h1>

    <!-- Apartado de pedidos pendientes -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">Pedidos Pendientes</div>
        <div class="card-body">
            <?php if (!empty($pedidosPendientes)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosPendientes as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td>S/ <?php echo htmlspecialchars($pedido['total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="2"> <!-- ID para "Preparado" -->
                                        <button type="submit" class="btn btn-primary btn-sm">Preparar Pedido</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Anular Pedido</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos pendientes.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Apartado de pedidos preparados -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Pedidos Preparados</div>
        <div class="card-body">
            <?php if (!empty($pedidosPreparados)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Motorizado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosPreparados as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td>S/ <?php echo htmlspecialchars($pedido['total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="3">
                                        <select name="id_motorizado" class="form-select form-select-sm" required>
                                            <option value="" disabled selected>Seleccionar Motorizado</option>
                                            <?php foreach ($motorizados as $motorizado): ?>
                                                <option value="<?php echo $motorizado['id_empleado']; ?>">
                                                    <?php echo $motorizado['nombre_empleado'] . ' ' . $motorizado['apellido_empleado']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-success btn-sm mt-2">Asignar y Enviar</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Anular Pedido</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos preparados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Apartado de pedidos en camino -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Pedidos En Camino</div>
        <div class="card-body">
            <?php if (!empty($pedidosEnCamino)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosEnCamino as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td>S/ <?php echo htmlspecialchars($pedido['total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="4"> <!-- ID para "Entregado" -->
                                        <button type="submit" class="btn btn-success btn-sm">Entregar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos en camino.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Apartado de pedidos entregados -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Pedidos Entregados</div>
        <div class="card-body">
            <?php if (!empty($pedidosEntregados)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosEntregados as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td>S/ <?php echo htmlspecialchars($pedido['total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos entregados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Apartado de pedidos cancelados -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">Pedidos Cancelados</div>
        <div class="card-body">
            <?php if (!empty($pedidosCancelados)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosCancelados as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td>S/ <?php echo htmlspecialchars($pedido['total']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pedidos cancelados.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
