<?php
session_start();
require_once '../incluidos/conexion_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = obtenerConexion();
    if (!$conn) {
        $_SESSION['error'] = "Error al conectar con la base de datos.";
        header("Location: ../index.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT u.id_usuario, u.password_usuario 
                                FROM usuarios u
                                WHERE u.nombre_usuario = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($password === $user['password_usuario']) {
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
                    $_SESSION['roles'] = $userRoles;

                    header("Location: ../vistas/rol.php");
                    exit();
                } else {
                    $_SESSION['error'] = "No tienes roles asignados.";
                }
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error en la consulta: " . $e->getMessage();
    }
    header("Location: ../index.php");
    exit();
}
?>
