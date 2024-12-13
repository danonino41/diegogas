<?php
require_once 'vistas/funciones.php';
// Verificar si el formulario fue enviado
if (isset($_POST['id_pedido'])) {
    $id_pedido = $_POST['id_pedido'];
    $historial = obtenerHistorialConDescripcion($id_pedido);  // Usar la nueva función
} else {
    $historial = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Pedido - DiegoGas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Header de bienvenida -->
    <header class="bg-primary text-white p-4">
        <div class="container">
            <h1 class="text-center">¡Bienvenido a DiegoGas!</h1>
            <p class="text-center">Sigue el estado de tu pedido de manera fácil y rápida.</p>
        </div>
    </header>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Seguimiento de Pedido</h2>

        <!-- Formulario para ingresar el ID del pedido -->
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="id_pedido" class="form-label">Ingresa tu ID de Pedido</label>
                <input type="number" name="id_pedido" id="id_pedido" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Verificar Estado</button>
        </form>

        <?php if (!empty($historial)): ?>
            <!-- Tabla para mostrar el historial del pedido -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Fecha de Cambio</th>
                        <th>Empleado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $registro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['estado']); ?></td>
                            <td><?php echo htmlspecialchars($registro['descripcion']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($registro['fecha_cambio'])); ?></td>
                            <td><?php echo htmlspecialchars($registro['nombre_empleado'] . ' ' . $registro['apellido_empleado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['id_pedido'])): ?>
            <div class="alert alert-warning text-center">No se encontró historial para el ID de pedido proporcionado.</div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
