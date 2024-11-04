<?php
session_start();
require_once '../funciones.php'; // Incluye funciones.php, que ya incluye conexion_bd.php

// Verificación de la sesión y del rol de usuario
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

// Aquí puedes agregar consultas para obtener los totales y demás datos necesarios
$totalProductos = 8; // Ejemplo estático, reemplazar con consulta
$totalVentas = 0.00; // Ejemplo estático, reemplazar con consulta
$totalClientes = 4; // Ejemplo estático, reemplazar con consulta
$ventasHoy = 0.00; // Ejemplo estático, reemplazar con consulta
$ventasSemana = 0.00; // Ejemplo estático, reemplazar con consulta
$ventasMes = 151.00; // Ejemplo estático, reemplazar con consulta

// Consulta para obtener ventas por usuario
$ventasPorUsuarios = [
];

// Consulta para obtener ventas por cliente
$ventasPorClientes = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once 'navbar_recepcion.php'; ?>

<div class="container mt-4">
<h1>Hola, <?php echo htmlspecialchars(isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'Recepcionista'); ?></h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total productos</h5>
                    <p class="card-text"><?php echo $totalProductos; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ventas registradas</h5>
                    <p class="card-text"><?php echo $totalVentas; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Usuarios registrados</h5>
                    <p class="card-text"><?php echo $totalClientes; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ventas hoy</h5>
                    <p class="card-text"><?php echo $ventasHoy; ?></p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4">Ventas por usuarios</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre usuario</th>
                <th>Número ventas</th>
                <th>Total ventas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventasPorUsuarios as $venta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($venta['nombre_usuario']); ?></td>
                    <td><?php echo $venta['numero_ventas']; ?></td>
                    <td><?php echo $venta['total_ventas']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="mt-4">Ventas por clientes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre cliente</th>
                <th>Número compras</th>
                <th>Total ventas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventasPorClientes as $venta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($venta['nombre_cliente']); ?></td>
                    <td><?php echo $venta['numero_compras']; ?></td>
                    <td><?php echo $venta['total_ventas']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><BR><BR><BR><BR><BR></BR></BR></BR></BR></BR>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>
