<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Diego Gas</title>
    <link rel="stylesheet" href="recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
    <div class="login-container" style="padding: 150px;">
        <div class="login-box">
            <h2>Login</h2>
            <div class="logo">
                <p>Diego Gas</p>
            </div>
            <p>Bienvenidos al Sistema</p>
            <!-- Mostrar mensaje de error si existe -->
            <?php
            session_start();
            if (isset($_SESSION['error'])): ?>
                <div class="error-message" id="errorMessage">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']);?>
            <?php endif; ?>

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
    <style>
        .error-message {
            color: #ff4d4d;
            background-color: #ffe6e6;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ff4d4d;
            border-radius: 5px;
            text-align: center;
            opacity: 1;
            transition: opacity 1s ease;
        }
    </style>

    <script>
        setTimeout(() => {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.style.opacity = '0';
            }
        },1000);
    </script>
</body>
</html>
