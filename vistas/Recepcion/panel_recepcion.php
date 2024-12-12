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
$usuario = obtenerDatosUsuario($_SESSION['id_usuario']);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Recepcionista - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once 'navbar_recepcion.php'; ?>
    
<main>
    <div class="container mt-4">
        
    <h1>Bienvenido <?php echo htmlspecialchars($usuario['nombre_empleado'] ?? 'Usuario no encontrado') . "!!"; ?></h1>
    <h4>Fecha: <span id="fecha_actual"></span></h4>
    <h4>Hora: <span id="hora_actual"></span></h4>

    <?php include_once 'cartilla_navbar_recepcion.php'; ?>
        
    <h3>Historial de Pedidos Recientes (cambiar por pedidos de ese mismo dia 5am a 12pm)</h3>
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
    </main>
    <BR><BR>

    <script>
    function fechaActual() {
        const ahora = new Date();
        const dia = ahora.getDate().toString().padStart(2, '0'); 
        const mes = (ahora.getMonth() + 1).toString().padStart(2, '0');
        const ano = ahora.getFullYear().toString();
        document.getElementById('fecha_actual').textContent = `${dia}-${mes}-${ano}`;
    }
    fechaActual();
    function actualizarHora() {
        const ahora = new Date();
        const hora = ahora.getHours().toString().padStart(2, '0');
        const minutos = ahora.getMinutes().toString().padStart(2, '0'); 
        const segundos = ahora.getSeconds().toString().padStart(2, '0');
        document.getElementById('hora_actual').textContent = `${hora}:${minutos}:${segundos}`;
    }
    actualizarHora();
    setInterval(actualizarHora, 1000);
</script>

                    
</div>
</body>
</html>
