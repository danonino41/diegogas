<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Diego Gas</title>
    <link rel="stylesheet" href="recursos/css/estilos.css"> <!-- Enlace a la hoja de estilos CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Enlace a Font Awesome para íconos -->
    <!-- Si usas una fuente como Lobster, puedes agregar este enlace -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <h2>Sistema de Gestion de Ventas</h2>
    </header>

    <!-- Login Form Container -->
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <!-- Logo como texto estilizado -->
            <div class="logo">
                <p>Diego Gas</p>
            </div>
            <p>Bienvenidos al Sistema</p>

            <!-- Formulario de Login -->
            <form action="controladores/login_controlador.php" method="post">
                <!-- Input para el nombre de usuario -->
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" id="username" name="username" placeholder="Ingresar tu nombre.." required>
                </div>

                <!-- Input para la contraseña -->
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña.." required>
                </div>

                <!-- Botón de Ingreso -->
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Diego Gas. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
