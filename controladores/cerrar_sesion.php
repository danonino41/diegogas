<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success" role="alert">
            Sesión cerrada exitosamente. Redirigiendo a la página de inicio de sesión...
        </div>
    </div>
    <script>
        setTimeout(() => {
            window.location.href = "../index.php";
        }, 1000); // Redirige después de 1 segundo
    </script>
</body>
</html>
