<?php
session_start();
require_once '../incluidos/conexion_bd.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $idCliente = $_GET['id'];

    try {
        // Preparar la consulta para obtener el cliente por ID
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE IdCliente = :idCliente");
        $stmt->bindParam(':idCliente', $idCliente);
        $stmt->execute();

        // Verificar si el cliente existe
        if ($stmt->rowCount() > 0) {
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Redirigir si no se encuentra el cliente
            header("Location: clientes_recepcion.php?error=Cliente no encontrado");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error al obtener el cliente: " . $e->getMessage();
    }
} else {
    header("Location: clientes_recepcion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Cliente</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="panel_recepcion.php">Diego Gas</a>
        <!-- Otras opciones de navegación -->
    </nav>

    <div class="container mt-4">
        <h1>Detalles del Cliente</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($cliente['Nombre'] . ' ' . $cliente['Apellido']); ?></h5>
                <p class="card-text"><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['Telefono']); ?></p>
                <p class="card-text"><strong>Dirección:</strong> <?php echo htmlspecialchars($cliente['Direccion']); ?></p>
                <p class="card-text"><strong>Coordenadas:</strong> <?php echo htmlspecialchars($cliente['Coordenadas']); ?></p>
                <p class="card-text"><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($cliente['FechaRegistro']); ?></p>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($cliente['Email']); ?></p>
            </div>
        </div>
        <a href="clientes_recepcion.php" class="btn btn-primary mt-3">Volver a la lista de clientes</a>
        <a href="EditarCliente.php?id=<?php echo $cliente['IdCliente']; ?>" class="btn btn-warning mt-3">Editar Cliente</a>
        <button class="btn btn-danger mt-3" onclick="confirmDelete(<?php echo $cliente['IdCliente']; ?>)">Eliminar Cliente</button>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
                window.location.href = "EliminarCliente.php?id=" + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
