<?php
session_start();
include '../incluidos/conexion_bd.php';  // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['accion'] == 'modificar') {
    $id_usuario = $_POST['id_usuario'];
    $nuevo_username = $_POST['nuevo_username'];
    $nuevo_password = $_POST['nuevo_password'];
    $nuevo_rol = $_POST['nuevo_rol'];

    // Preparar la actualización
    if (!empty($nuevo_password)) {
        // Si el campo de la nueva contraseña no está vacío, hashearla
        $hashed_password = password_hash($nuevo_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = :nuevo_username, password_usuario = :nuevo_password, id_rol = :nuevo_rol WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':nuevo_password', $hashed_password);
    } else {
        // Si no se cambia la contraseña, solo actualizamos el nombre y el rol
        $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = :nuevo_username, id_rol = :nuevo_rol WHERE id_usuario = :id_usuario");
    }

    // Enlazar los parámetros
    $stmt->bindParam(':nuevo_username', $nuevo_username);
    $stmt->bindParam(':nuevo_rol', $nuevo_rol);
    $stmt->bindParam(':id_usuario', $id_usuario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Usuario modificado exitosamente.";
    } else {
        echo "Error al modificar el usuario.";
    }
}
?>
