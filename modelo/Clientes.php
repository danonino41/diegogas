<?php
// Función para obtener la conexión
function obtenerConexion() {
    $host = "localhost";
    $dbname = "diegogas";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Conexión fallida: " . $e->getMessage();
        return null;
    }
}

// Función para obtener clientes con búsqueda opcional
function obtenerClientes($busqueda = null) {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $parametros = [];
    $sentencia = "SELECT * FROM clientes";
    if ($busqueda) {
        $sentencia .= " WHERE nombre_cliente LIKE ? OR dni_cliente LIKE ? OR email_cliente LIKE ?";
        $parametros[] = "%$busqueda%";
        $parametros[] = "%$busqueda%";
        $parametros[] = "%$busqueda%";
    }

    try {
        $stmt = $conn->prepare($sentencia);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener clientes: " . $e->getMessage();
        return [];
    }
}

// Función para insertar un nuevo cliente
function insertarCliente($nombre, $dni, $email) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $sentencia = "INSERT INTO clientes (nombre_cliente, dni_cliente, email_cliente) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sentencia);
        $stmt->execute([$nombre, $dni, $email]);
        return true;
    } catch (PDOException $e) {
        echo "Error al insertar cliente: " . $e->getMessage();
        return false;
    }
}

// Función para actualizar un cliente existente
function actualizarCliente($id_cliente, $nombre, $dni, $email) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $sentencia = "UPDATE clientes SET nombre_cliente = ?, dni_cliente = ?, email_cliente = ? WHERE id_cliente = ?";
        $stmt = $conn->prepare($sentencia);
        $stmt->execute([$nombre, $dni, $email, $id_cliente]);
        return true;
    } catch (PDOException $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
        return false;
    }
}

// Función para eliminar un cliente por ID
function eliminarCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $sentencia = "DELETE FROM clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sentencia);
        $stmt->execute([$id_cliente]);
        return true;
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
        return false;
    }
}
?>
