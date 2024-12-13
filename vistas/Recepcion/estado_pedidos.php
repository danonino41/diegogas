<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Variables
$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';
$fechaHoy = date('2024-12-13');

// Procesar solicitud de cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido']) && isset($_POST['nuevo_estado'])) {
    $idPedido = $_POST['id_pedido'];
    $nuevoEstadoId = $_POST['nuevo_estado'];
    $idEmpleado = $_POST['id_empleado'] ?? null;

    if ($nuevoEstadoId == 4 && !$idEmpleado) { // En Ruta requiere motorizado
        $error = "Debe asignar un motorizado antes de enviar el pedido a En Ruta.";
    } else {
        if (actualizarEstadoPedido($idPedido, $nuevoEstadoId, $idEmpleado)) {
            $mensaje = "Estado del pedido actualizado correctamente.";
        } else {
            $error = "Error al actualizar el estado del pedido.";
        }
    }
    header("Location: estado_pedidos.php?mensaje=$mensaje&error=$error");
    exit();
}

// Procesar solicitud de cancelación de pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido']) && isset($_POST['cancelar_pedido'])) {
    $idPedido = $_POST['id_pedido'];

    if (actualizarEstadoPedido($idPedido, 7)) { // Cambiar estado a Cancelado
        $mensaje = "Pedido cancelado correctamente.";
    } else {
        $error = "No se pudo cancelar el pedido.";
    }
    header("Location: estado_pedidos.php?mensaje=$mensaje&error=$error");
    exit();
}

// Obtener los pedidos del día por estado
$pedidosPendientes = obtenerPedidosPorEstadoYFecha('Pendiente', $fechaHoy);
$pedidosConfirmados = obtenerPedidosPorEstadoYFecha('Confirmado', $fechaHoy);
$pedidosPreparados = obtenerPedidosPorEstadoYFecha('Preparado', $fechaHoy);
$pedidosEnRuta = obtenerPedidosPorEstadoYFecha('En Ruta', $fechaHoy);
$pedidosLlegados = obtenerPedidosPorEstadoYFecha('Llegó a Domicilio', $fechaHoy);
$pedidosEntregados = obtenerPedidosPorEstadoYFecha('Entregado', $fechaHoy);
$pedidosCancelados = obtenerPedidosPorEstadoYFecha('Cancelado', $fechaHoy);

// Obtener lista de motorizados
$motorizados = obtenerMotorizados();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estado de Pedidos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Estado de Pedidos</h1>

        <!-- Mensajes de éxito o error -->
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Función para mostrar pedidos por estado -->
        <?php
        function mostrarPedidos($pedidos, $titulo, $clase, $acciones = [])
        {
            ?>
            <div class="card mb-4">
                <div class="card-header <?php echo $clase; ?> text-white"><?php echo $titulo; ?></div>
                <div class="card-body">
                    <?php if (!empty($pedidos)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                    <?php if (in_array('motorizado', $acciones)): ?>
                                        <th>Motorizado</th>
                                    <?php endif; ?>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                        <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                        <td>S/ <?php echo number_format($pedido['total_pedido'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                                        <?php if (in_array('motorizado', $acciones)): ?>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <input type="hidden" name="nuevo_estado" value="4"> <!-- En Ruta -->
                                                    <select name="id_empleado" class="form-select form-select-sm" required>
                                                        <option value="" disabled selected>Seleccionar Motorizado</option>
                                                        <?php foreach ($GLOBALS['motorizados'] as $motorizado): ?>
                                                            <option value="<?php echo $motorizado['id_empleado']; ?>">
                                                                <?php echo htmlspecialchars($motorizado['nombre_empleado'] . ' ' . $motorizado['apellido_empleado']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-success btn-sm mt-2">Asignar</button>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                        <td>
                                            <?php if (in_array('confirmar', $acciones)): ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <input type="hidden" name="nuevo_estado" value="2"> <!-- Confirmado -->
                                                    <button type="submit" class="btn btn-secondary btn-sm">Confirmar</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if (in_array('preparar', $acciones)): ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <input type="hidden" name="nuevo_estado" value="3"> <!-- Preparado -->
                                                    <button type="submit" class="btn btn-primary btn-sm">Preparar</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if (in_array('llego_domicilio', $acciones)): ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <input type="hidden" name="nuevo_estado" value="5"> <!-- Llegó a Domicilio -->
                                                    <button type="submit" class="btn btn-info btn-sm">Llegó a Domicilio</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if (in_array('entregar', $acciones)): ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <input type="hidden" name="nuevo_estado" value="6"> <!-- Entregado -->
                                                    <button type="submit" class="btn btn-success btn-sm">Entregar</button>
                                                </form>
                                            <?php endif; ?>

                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                <input type="hidden" name="cancelar_pedido" value="1">
                                                <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay pedidos en esta categoría.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }

        mostrarPedidos($pedidosPendientes, "Pedidos Pendientes", "bg-warning", ['confirmar', 'cancelar']);
        mostrarPedidos($pedidosConfirmados, "Pedidos Confirmados", "bg-secondary", ['preparar', 'cancelar']);
        mostrarPedidos($pedidosPreparados, "Pedidos Preparados", "bg-primary", ['motorizado', 'cancelar']);
        mostrarPedidos($pedidosEnRuta, "Pedidos En Ruta", "bg-info", ['llego_domicilio', 'cancelar']);
        mostrarPedidos($pedidosLlegados, "Pedidos Llegó a Domicilio", "bg-warning", ['entregar', 'cancelar']);
        mostrarPedidos($pedidosEntregados, "Pedidos Entregados", "bg-success");
        mostrarPedidos($pedidosCancelados, "Pedidos Cancelados", "bg-danger");
        ?>
    </div>
</body>

</html>
