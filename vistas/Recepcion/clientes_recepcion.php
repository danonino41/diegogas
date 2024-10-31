<?php
session_start();
require_once '../../incluidos/conexion_bd.php';

// Verificación de la sesión y del rol de recepcionista
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

// Obtener la lista de clientes desde la base de datos
try {
    $stmt = $conn->prepare("SELECT nombre_cliente, telefono_cliente, direccion_cliente FROM clientes");
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los clientes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Recepcionista</title>
    <link rel="stylesheet" href="../../recursos/css/estilos.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar_recepcion.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Clientes</h2>
        <a href="agregar_cliente.php" class="btn btn-success"><i class="fas fa-plus"></i> Agregar</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefono_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['direccion_cliente']); ?></td>
                        <td><a href="editar_cliente.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a></td>
                        <td><a href="eliminar_cliente.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este cliente?');"><i class="fas fa-trash"></i> Eliminar</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay clientes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../incluidos/footer.php'; ?>

</body>
</html>
