<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro y Modificación de Usuario</title>
    <link rel="stylesheet" href="../recursos/css/estilos.css"> <!-- Ruta a tu archivo CSS -->
</head>
<body>
    <h2>Registro de Usuario</h2>

    <!-- Formulario para registrar usuario -->
    <form action="../controladores/registrar_usuario.php" method="POST">
        <input type="hidden" name="accion" value="registrar">  <!-- Campo oculto para identificar la acción -->
        <label for="username">Nombre de usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="rol">Rol:</label>
        <select name="rol" id="rol" required>
            <option value="1">Administrador</option>
            <option value="3">Recepcionista</option>
            <!-- Puedes agregar más roles -->
        </select>

        <button type="submit">Registrar</button>
    </form>

    <h2>Modificar Usuario</h2>

    <!-- Formulario para modificar usuario -->
    <form action="../controladores/modificar_usuario.php" method="POST">
        <input type="hidden" name="accion" value="modificar">  <!-- Campo oculto para identificar la acción -->
        <label for="id_usuario">Seleccionar Usuario:</label>
        <select name="id_usuario" id="id_usuario" required>
            <!-- Aquí deberías cargar los usuarios desde la base de datos -->
            <?php
            include '../incluidos/conexion_bd.php';  // Conexión a la BD
            $stmt = $conn->prepare("SELECT id_usuario, nombre_usuario FROM usuarios");
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($usuarios as $usuario) {
                echo "<option value='{$usuario['id_usuario']}'>{$usuario['nombre_usuario']}</option>";
            }
            ?>
        </select>

        <label for="nuevo_username">Nuevo nombre de usuario:</label>
        <input type="text" id="nuevo_username" name="nuevo_username" required>

        <label for="nuevo_password">Nueva contraseña:</label>
        <input type="password" id="nuevo_password" name="nuevo_password">

        <label for="nuevo_rol">Nuevo Rol:</label>
        <select name="nuevo_rol" id="nuevo_rol" required>
            <option value="1">Administrador</option>
            <option value="3">Recepcionista</option>
            <!-- Puedes agregar más roles -->
        </select>

        <button type="submit">Modificar</button>
    </form>
</body>
</html>
