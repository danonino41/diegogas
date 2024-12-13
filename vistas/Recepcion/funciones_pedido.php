<?php
function obtenerConexion()
{
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
function obtenerClientesConDetalles($busqueda = null)
{
    $conn = obtenerConexion();
    if (!$conn) return [];

    $parametros = [];
    $query = "
        SELECT 
            c.id_cliente, 
            c.nombre_cliente, 
            c.apellido_cliente, 
            c.dni_cliente, 
            t.telefono AS telefono_principal, 
            d.direccion AS direccion_principal
        FROM clientes c
        LEFT JOIN telefonos_cliente t 
            ON c.id_cliente = t.id_cliente AND t.es_principal = 1
        LEFT JOIN direcciones_cliente d 
            ON c.id_cliente = d.id_cliente AND d.es_principal = 1
    ";

    if ($busqueda) {
        $query .= " WHERE c.nombre_cliente LIKE :busqueda 
                    OR c.apellido_cliente LIKE :busqueda 
                    OR c.dni_cliente LIKE :busqueda 
                    OR t.telefono LIKE :busqueda";
        $parametros[':busqueda'] = "%$busqueda%";
    }

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener clientes con detalles: " . $e->getMessage());
        return [];
    }
}

function obtenerClientePorId($id_cliente)
{
    $conn = obtenerConexion();
    if (!$conn) return null;

    try {
        // Obtener datos principales del cliente
        $stmt = $conn->prepare("
            SELECT 
                c.id_cliente, 
                c.nombre_cliente, 
                c.apellido_cliente, 
                c.dni_cliente, 
                t.telefono AS telefono_principal, 
                d.direccion AS direccion_principal
            FROM clientes c
            LEFT JOIN telefonos_cliente t 
                ON c.id_cliente = t.id_cliente AND t.es_principal = 1
            LEFT JOIN direcciones_cliente d 
                ON c.id_cliente = d.id_cliente AND d.es_principal = 1
            WHERE c.id_cliente = ?
        ");
        $stmt->execute([$id_cliente]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            // Obtener todas las direcciones del cliente
            $cliente['direcciones'] = obtenerDireccionesPorCliente($id_cliente);

            // Obtener todos los teléfonos del cliente
            $cliente['telefonos'] = obtenerTelefonosPorCliente($id_cliente);
        }

        return $cliente;
    } catch (PDOException $e) {
        error_log("Error al obtener cliente por ID: " . $e->getMessage());
        return null;
    }
}

function obtenerDireccionesPorCliente($id_cliente)
{
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->prepare("
            SELECT direccion, es_principal 
            FROM direcciones_cliente 
            WHERE id_cliente = ?
        ");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener direcciones del cliente: " . $e->getMessage());
        return [];
    }
}
function obtenerTelefonosPorCliente($id_cliente)
{
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->prepare("
            SELECT telefono, es_principal 
            FROM telefonos_cliente 
            WHERE id_cliente = ?
        ");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener teléfonos del cliente: " . $e->getMessage());
        return [];
    }
}

function agregarDireccionCliente($id_cliente, $direccion, $es_principal = false)
{
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        if ($es_principal) {
            // Desmarcar la dirección principal actual
            $stmt = $conn->prepare("UPDATE direcciones_cliente SET es_principal = 0 WHERE id_cliente = ?");
            $stmt->execute([$id_cliente]);
        }

        // Insertar la nueva dirección
        $stmt = $conn->prepare("
            INSERT INTO direcciones_cliente (id_cliente, direccion, es_principal) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$id_cliente, $direccion, $es_principal]);
    } catch (PDOException $e) {
        error_log("Error al agregar dirección al cliente: " . $e->getMessage());
        return false;
    }
}

function agregarTelefonoCliente($id_cliente, $telefono, $es_principal = false)
{
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        if ($es_principal) {
            // Desmarcar el teléfono principal actual
            $stmt = $conn->prepare("UPDATE telefonos_cliente SET es_principal = 0 WHERE id_cliente = ?");
            $stmt->execute([$id_cliente]);
        }

        // Insertar el nuevo teléfono
        $stmt = $conn->prepare("
            INSERT INTO telefonos_cliente (id_cliente, telefono, es_principal) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$id_cliente, $telefono, $es_principal]);
    } catch (PDOException $e) {
        error_log("Error al agregar teléfono al cliente: " . $e->getMessage());
        return false;
    }
}


