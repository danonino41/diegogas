<?php
session_start();

if (!isset($_SESSION['roles']) || empty($_SESSION['roles'])) {
    echo "No tienes roles asignados.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Diego Gas</title>
    <link rel="stylesheet" href="../recursos/css/estiloindex.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h2>Sistema de Gestión de Ventas</h2>
        <!-- Botón de Cerrar Sesión en el encabezado -->
        <div style="position: absolute; top: 15px; right: 15px;">
            <a href="../controladores/cerrar_sesion.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido al Sistema</h2>
            <p>Selecciona tu rol para ingresar:</p>
            
            <?php if (in_array('Administrador', $_SESSION['roles'])): ?>
                <div class="login-option">
                    <a href="Admin/admin_panel.php" class="login-btn">
                        <i class="fas fa-user-shield"></i> Ingresar como Administrador
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('Recepcionista', $_SESSION['roles'])): ?>
                <div class="login-option">
                    <a href="Recepcion/panel_recepcion.php" class="login-btn">
                        <i class="fas fa-user-tie"></i> Ingresar como Recepcionista
                    </a>
                </div>
            <?php endif; ?>

            <?php if (in_array('Motorizado', $_SESSION['roles'])): ?>
                <div class="login-option">
                    <a href="motorizado/motorizado_panel.php" class="login-btn">
                        <i class="fas fa-motorcycle"></i> Ingresar como Motorizado
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <footer>
        <p>&copy; 2024 Diego Gas. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
