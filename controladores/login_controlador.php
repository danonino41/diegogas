<?php
session_start();
require_once '../incluidos/conexion_bd.php'; // Conexión a la base de datos

// Validamos que el formulario haya sido enviado con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Preparar la consulta para buscar el usuario
        $stmt = $conn->prepare("SELECT u.id_usuario, u.password_usuario 
                                FROM usuarios u
                                WHERE u.nombre_usuario = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Verificar si el usuario existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Comparar directamente la contraseña
            if ($password === $user['password_usuario']) {
                // Obtener todos los roles del usuario y guardarlos en la sesión
                $rolesStmt = $conn->prepare("SELECT r.nombre_rol 
                                             FROM usuarios u
                                             JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
                                             JOIN roles r ON ur.id_rol = r.id_rol
                                             WHERE u.nombre_usuario = :username");
                $rolesStmt->bindParam(':username', $username);
                $rolesStmt->execute();
                $userRoles = $rolesStmt->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($userRoles)) {
                    $_SESSION['id_usuario'] = $user['id_usuario'];
                    $_SESSION['username'] = $username;
                    $_SESSION['roles'] = $userRoles; // Almacenar los roles en la sesión

                    // Redirigir a rol.php para seleccionar el rol
                    header("Location: ../vistas/rol.php");
                    exit();
                } else {
                    // Usuario sin roles asignados
                    header("Location: ../index.php?error=Sin roles asignados");
                    exit();
                }
            } else {
                // Contraseña incorrecta
                header("Location: ../index.php?error=Contraseña incorrecta");
                exit();
            }
        } else {
            // Usuario no encontrado
            header("Location: ../index.php?error=Usuario no encontrado");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
} else {
    // Si no es un envío POST, redirigir al login
    header("Location: ../index.php");
    exit();
}
