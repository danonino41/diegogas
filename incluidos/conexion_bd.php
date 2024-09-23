<?php
$host = "localhost";
$dbname = "diegogas"; // Tu base de datos
$username = "root";
$password = ""; // Ajusta según tu configuración

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>
