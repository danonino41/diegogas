<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Motorizado', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

$idEmpleado = $_SESSION['id_empleado'];

$fechaFiltro = $_GET['fecha'] ?? '';
$clienteFiltro = $_GET['cliente'] ?? '';

$historialPedidos = obtenerHistorialPedidosMotorizado($idEmpleado, $fechaFiltro, $clienteFiltro);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos - Motorizado</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once 'navbar_motorizado.php'; ?>

<div class="container mt-4">
    <h1>Historial de Pedidos</h1>

    <!-- Formulario de búsqueda -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="fecha" class="form-label">Filtrar por Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fechaFiltro); ?>">
        </div>
        <div class="col-md-4">
            <label for="cliente" class="form-label">Filtrar por Cliente</label>
            <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nombre del Cliente" value="<?php echo htmlspecialchars($clienteFiltro); ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <!-- Tabla de historial de pedidos -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historialPedidos)): ?>
                    <?php foreach ($historialPedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                            <td>S/ <?php echo number_format($pedido['total_pedido'], 2); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td>
                                <a href="detalle_pedido.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron pedidos para los filtros aplicados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../../recursos/js/cliente.js"></script>
</body>
</html>
