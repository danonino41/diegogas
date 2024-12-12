<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

// Verificar si se ha proporcionado el ID del pedido
if (!isset($_GET['id_pedido']) || empty($_GET['id_pedido'])) {
    die("ID del pedido no proporcionado.");
}

$id_pedido = $_GET['id_pedido'];

// Obtener detalles del pedido
$pedido = obtenerPedidoPorId($id_pedido);
if (!$pedido) {
    die("Pedido no encontrado.");
}

// Obtener los productos del pedido
$detallesPedido = obtenerDetallesPedido($id_pedido);

// Obtener historial de estado del pedido
$historialEstadoPedido = obtenerHistorialEstadoPedido($id_pedido);

// Obtener la dirección de envío del pedido usando la función
$direccionEnvio = obtenerDireccionEnvio($pedido['id_direccion_envio']);

$mensaje = '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Detalles del Pedido</h1><br>

        <!-- Información General del Pedido -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Información del Pedido
            </div>
            <div class="card-body">
                <p><strong>ID del Pedido:</strong> <?php echo htmlspecialchars($pedido['id_pedido']); ?></p>
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre_cliente']); ?></p>
                <p><strong>Fecha del Pedido:</strong> <?php echo htmlspecialchars($pedido['fecha_pedido']); ?></p>
                <p><strong>Total:</strong> S/ <?php echo htmlspecialchars($pedido['total_pedido']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
                <p><strong>Empleado que gestionó el pedido:</strong> 
                    <?php echo htmlspecialchars($pedido['nombre_empleado'] . ' ' . $pedido['apellido_empleado'] ?? 'No asignado'); ?>
                </p>
            </div>
        </div>

        <!-- Dirección de Envío -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                Dirección de Envío
            </div>
            <div class="card-body">
                <?php if ($direccionEnvio): ?>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccionEnvio['direccion']); ?></p>
                    <p><strong>Coordenadas:</strong> <?php echo htmlspecialchars($direccionEnvio['coordenadas']); ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($direccionEnvio['descripcion']); ?></p>
                <?php else: ?>
                    <p>No se encontró la dirección de envío.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detalles de los Productos en el Pedido -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                Productos en el Pedido
            </div>
            <div class="card-body">
                <?php if (!empty($detallesPedido)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detallesPedido as $detalle): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                                    <td>S/ <?php echo htmlspecialchars($detalle['precio_unitario']); ?></td>
                                    <td>S/ <?php echo htmlspecialchars($detalle['subtotal']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron detalles para este pedido.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Historial de Estado del Pedido -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                Historial de Estado
            </div>
            <div class="card-body">
                <?php if (!empty($historialEstadoPedido)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Fecha de Cambio</th>
                                <th>Empleado Responsable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historialEstadoPedido as $estado): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($estado['estado']); ?></td>
                                    <td><?php echo htmlspecialchars($estado['fecha_cambio']); ?></td>
                                    <td><?php echo htmlspecialchars($estado['nombre_empleado'] ?? 'No asignado'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontró historial de estado para este pedido.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/cliente.js"></script>
</body>

</html>