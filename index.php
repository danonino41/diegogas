<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Diego Gas</title>
    <link rel="stylesheet" href="recursos/css/estilosmenu.css"> <!-- Enlace a la hoja de estilos CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Enlace a Font Awesome para íconos -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <div class="logo">
                <p>Diego Gas</p>
            </div>
            <p>Bienvenidos al Sistema</p>

            <form action="controladores/login_controlador.php" method="post">
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" id="username" name="username" placeholder="Ingresa tu usuario.." required>
                </div>

                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña.." required>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>
            </form>
        </div>
    </div>

</body>
</html>
