<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Diego Gas</title>
    <link rel="stylesheet" href="/recursos/css/estilos.css"> <!-- Enlace a la hoja de estilos CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlace a Font Awesome para íconos -->
    <!-- Si usas una fuente como Lobster, puedes agregar este enlace -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100vh;
}

/* Encabezado */
header {
    background-color: #333;
    color: white;
    padding: 15px;
    text-align: center;
}

header h2 {
    margin: 0;
}

/* Contenedor de login */
.login-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f4f4f4;
}

.login-box {
    background-color: white;
    padding: 40px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    width: 300px;
    text-align: center;
}

.login-box h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

.logo {
    text-align: center;
}

/* Estilos para el texto dentro de logo */
.logo p {
    font-family: 'Lobster', cursive;
    /* Puedes cambiar a la fuente que desees */
    font-size: 48px;
    color: #ffcc00;
    /* Color de texto similar al mostrado en la imagen */
    text-shadow:
        3px 3px 0 #1a0f26,
        /* Sombra principal para dar profundidad */
        6px 6px 0 #f1c5e3;
    /* Sombra secundaria para mayor efecto */
    margin: 0;
}


p {
    color: #666;
    margin-bottom: 20px;
}

.input-group {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.input-icon {
    background-color: #f4f4f4;
    padding: 10px;
    border-right: 1px solid #ddd;
}

.input-group input {
    width: 100%;
    border: none;
    padding: 10px;
    outline: none;
}

.login-btn {
    background-color: #007BFF;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-btn i {
    margin-right: 10px;
}

.login-btn:hover {
    background-color: #0056b3;
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    padding: 10px 0;
    text-align: center;
}
    </style>
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
                <p>Hola Recep</p>
            </div>
            <p>Bienvenidos al Sistema</p>

            <!-- Formulario de Login -->
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Diego Gas. Todos los derechos reservados.</p>
    </footer>
</body>

</html>