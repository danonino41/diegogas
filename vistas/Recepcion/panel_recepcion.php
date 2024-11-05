<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

$pedidosPendientes = obtenerPedidosPorEstado('Pendiente');
$pedidosPreparados = obtenerPedidosPorEstado('Preparado'); 
$pedidosEnCamino = obtenerPedidosPorEstado('En camino');
$pedidosEntregados = obtenerPedidosPorEstado('Entregado');
$pedidosCancelados = obtenerPedidosPorEstado('Cancelado');
$historialPedidos = obtenerHistorialPedidos(5) ?? [];
$productosBajoInventario = obtenerProductosBajoInventario(5) ?? []; 

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Recepcionista - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Dashboard Recepcionista</h1>
        <br>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Pendientes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosPendientes); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Preparados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosPreparados); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">En camino</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosEnCamino); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Entregados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosEntregados); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Cancelados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo count($pedidosCancelados); ?> Pedidos</h5>
                    </div>
                </div>
            </div>
        </div>
    <h3>Historial de Pedidos Recientes</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historialPedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                        <td>S/ <?php echo htmlspecialchars($pedido['total']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h3>Productos con Bajo Inventario</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productosBajoInventario as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                        <td><?php echo htmlspecialchars($producto['existencias']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <BR><BR><BR></BR>

</div>
</body>
</html>
