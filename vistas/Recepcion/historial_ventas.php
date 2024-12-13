<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Variables de búsqueda
$criterio = $_GET['criterio'] ?? '';
$valor = $_GET['valor'] ?? '';

// Obtener historial de pedidos según el criterio de búsqueda
$pedidos = buscarHistorialPedidos($criterio, $valor);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Pedidos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Historial de Pedidos</h1><br>

        <!-- Barra de búsqueda -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="criterio" class="form-label">Buscar por:</label>
                <select name="criterio" id="criterio" class="form-select">
                    <option value="id_pedido" <?php if ($criterio === 'id_pedido') echo 'selected'; ?>>ID Pedido</option>
                    <option value="dni_cliente" <?php if ($criterio === 'dni_cliente') echo 'selected'; ?>>DNI Cliente</option>
                    <option value="nombre_cliente" <?php if ($criterio === 'nombre_cliente') echo 'selected'; ?>>Nombre Cliente</option>
                    <option value="fecha_pedido" <?php if ($criterio === 'fecha_pedido') echo 'selected'; ?>>Fecha</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="valor" class="form-label">Valor:</label>
                <input type="text" name="valor" id="valor" class="form-control" value="<?php echo htmlspecialchars($valor); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>

        <!-- Tabla de historial de pedidos -->
        <div class="card">
            <div class="card-header bg-primary text-white">Historial de Pedidos</div>
            <div class="card-body">
                <?php if (!empty($pedidos)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Cliente</th>
                                <th>DNI</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['dni_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                                    <td>S/ <?php echo number_format($pedido['total_pedido'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                    <td>
                                        <a href="detalles_pedido.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron pedidos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/cliente.js"></script>
</body>

</html>
