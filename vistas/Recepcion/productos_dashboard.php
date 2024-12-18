<?php
require_once '../funciones.php';

// Verificar sesión y rol
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Obtener métricas
$totalProductos = obtenerTotalProductos();
$stock5kg = obtenerStockPorPeso(5);
$stock10kg = obtenerStockPorPeso(10);
$stock15kg = obtenerStockPorPeso(15);
$stock45kg = obtenerStockPorPeso(45);
$stockValvulas = obtenerStockAccesorios('Válvulas');
$stockMangueras = obtenerStockAccesorios('Mangueras');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Productos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <div class="row">
            <!-- Número total de productos -->
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total de Productos</h5>
                        <p class="card-text"><?php echo $totalProductos > 0 ? $totalProductos : 'No hay productos en inventario'; ?></p>
                    </div>
                </div>
            </div>

            <!-- Stock por peso -->
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock de Balones de Gas 5 kg</h5>
                        <p class="card-text"><?php echo $stock5kg > 0 ? $stock5kg : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock de Balones de Gas 10 kg</h5>
                        <p class="card-text"><?php echo $stock10kg > 0 ? $stock10kg : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock de Balones de Gas 15 kg</h5>
                        <p class="card-text"><?php echo $stock15kg > 0 ? $stock15kg : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock de Balones de Gas 45 kg</h5>
                        <p class="card-text"><?php echo $stock45kg > 0 ? $stock45kg : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>

            <!-- Stock de accesorios -->
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock de Válvulas</h5>
                        <p class="card-text"><?php echo $stockValvulas > 0 ? $stockValvulas : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock de Mangueras</h5>
                        <p class="card-text"><?php echo $stockMangueras > 0 ? $stockMangueras : 'No disponible'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
