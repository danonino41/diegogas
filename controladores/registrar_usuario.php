<?php
// archivo: controladores/registrar_usuario.php

session_start();
include '../incluidos/conexion_bd.php';  // Asegúrate de ajustar la ruta a la conexión a la BD

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];  // Suponiendo que seleccionas el rol del usuario

    // Verificar si el nombre de usuario ya existe
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        echo "El nombre de usuario ya existe, por favor elige otro.";
    } else {
        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, password_usuario, id_rol, fecha_creacion_usuario) VALUES (:username, :password, :rol, :fecha)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':rol', $rol);

        // Definir la fecha actual
        $fecha_actual = date('Y-m-d');
        $stmt->bindParam(':fecha', $fecha_actual);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Usuario registrado exitosamente.";
        } else {
            echo "Hubo un error al registrar el usuario.";
        }
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
