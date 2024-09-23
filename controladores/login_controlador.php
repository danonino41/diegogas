<?php
session_start();
include '../incluidos/conexion_bd.php';  // Conexión a la BD

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario por su nombre de usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Usar password_verify para verificar la contraseña hasheada
    if ($user && password_verify($password, $user['password_usuario'])) {
        // Iniciar la sesión si la contraseña es correcta
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['username'] = $user['nombre_usuario'];
        $_SESSION['rol'] = $user['id_rol'];

        // Redirigir según el rol del usuario
        if ($user['id_rol'] == 1) {  // Administrador
            header("Location: ../vistas/Admin/admin_panel.php");
        } else if ($user['id_rol'] == 3) {  // Recepcionista
            header("Location: ../vistas/Recepcion/recepcion_panel.php");
        }
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>
