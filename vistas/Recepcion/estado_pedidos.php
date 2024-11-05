<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Obtener los pedidos por estado
$pedidosPendientes = obtenerPedidosPorEstado('Pendiente');
$pedidosPreparados = obtenerPedidosPorEstado('Preparado');
$pedidosEnCamino = obtenerPedidosPorEstado('En camino');
$pedidosEntregados = obtenerPedidosPorEstado('Entregado');
$pedidosCancelados = obtenerPedidosPorEstado('Cancelado');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Pedidos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
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
                                    <!-- Aquí podrías agregar acciones, como cambiar de estado -->
                                    <button class="btn btn-primary btn-sm">Preparar</button>
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
                            <th>Acciones</th>
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
                                    <button class="btn btn-success btn-sm">En Camino</button>
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
                                    <button class="btn btn-success btn-sm">Entregado</button>
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
