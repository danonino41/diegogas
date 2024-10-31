<?php
include_once "C:/xampp/htdocs/diegogas/incluidos/conexion_bd.php";

function obtenerConexion() {
    $conn = conectar();
    if (!$conn) {
        echo "Error: No se pudo establecer conexión con la base de datos.";
        return null;
    }
    return $conn;
}

// 1. Obtener el total de ventas de hoy
function obtenerTotalVentasHoy() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $hoy = date("Y-m-d");
    $sql = "SELECT SUM(total_boleta) AS total FROM boletas WHERE DATE(fecha_boleta) = :hoy";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":hoy", $hoy);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 2. Obtener el total de ventas de la semana
function obtenerTotalVentasSemana() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $inicioSemana = date("Y-m-d", strtotime("last Monday"));
    $sql = "SELECT SUM(total_boleta) AS total FROM boletas WHERE DATE(fecha_boleta) >= :inicioSemana";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":inicioSemana", $inicioSemana);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 3. Obtener el total de ventas del mes
function obtenerTotalVentasMes() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $inicioMes = date("Y-m-01");
    $sql = "SELECT SUM(total_boleta) AS total FROM boletas WHERE DATE(fecha_boleta) >= :inicioMes";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":inicioMes", $inicioMes);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 4. Obtener el número de pedidos activos
function obtenerNumeroPedidosActivos() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $sql = "SELECT COUNT(*) AS total FROM pedidos WHERE id_estado IN (1, 6)"; // Ajusta los estados según el ID correcto
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 5. Obtener detalles de pedidos activos
function obtenerPedidosActivos() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $sql = "SELECT id_pedido, id_cliente, total_pedido FROM pedidos WHERE id_estado IN (1, 6)";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// 6. Obtener el número de clientes nuevos en la última semana
function obtenerNumeroClientesNuevos() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $ultimaSemana = date("Y-m-d", strtotime("-1 week"));
    $sql = "SELECT COUNT(*) AS total FROM clientes WHERE fecha_registro_cliente >= :ultimaSemana";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":ultimaSemana", $ultimaSemana);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 7. Obtener detalles de clientes nuevos en la última semana
function obtenerClientesRecientes() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $ultimaSemana = date("Y-m-d", strtotime("-1 week"));
    $sql = "SELECT nombre_cliente, fecha_registro_cliente FROM clientes WHERE fecha_registro_cliente >= :ultimaSemana";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":ultimaSemana", $ultimaSemana);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// 8. Obtener el número de productos con bajo stock
function obtenerProductosBajoStock() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $stockMinimo = 10;  // Umbral de bajo stock
    $sql = "SELECT COUNT(*) AS total FROM productos WHERE existencias < :stockMinimo";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":stockMinimo", $stockMinimo, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 9. Obtener los productos más vendidos
function obtenerProductosMasVendidos() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $sql = "SELECT productos.nombre_producto AS nombre, SUM(detalle_pedido.cantidad) AS unidades, SUM(detalle_pedido.subtotal) AS total
            FROM detalle_pedido
            JOIN productos ON detalle_pedido.id_producto = productos.id_producto
            GROUP BY productos.nombre_producto
            ORDER BY unidades DESC
            LIMIT 10";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}




// 10. Obtener el total de todas las ventas
function obtenerTotalVentas() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $sql = "SELECT SUM(total_boleta) AS total FROM boletas";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 11. Obtener el número total de productos
function obtenerNumeroProductos() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $sql = "SELECT COUNT(*) AS total FROM productos";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 12. Obtener el número total de ventas registradas
function obtenerNumeroVentas() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $sql = "SELECT COUNT(*) AS total FROM boletas";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 13. Obtener el número total de usuarios registrados
function obtenerNumeroUsuarios() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $sql = "SELECT COUNT(*) AS total FROM usuarios";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 14. Obtener el número total de clientes registrados
function obtenerNumeroClientes() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    $sql = "SELECT COUNT(*) AS total FROM clientes";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 15. Obtener el total de ventas por usuario
function obtenerVentasPorUsuario() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $sql = "SELECT usuarios.nombre_usuario AS usuario, COUNT(boletas.id_boleta) AS numeroVentas, SUM(boletas.total_boleta) AS total
            FROM boletas
            JOIN pedidos ON boletas.id_pedido = pedidos.id_pedido
            JOIN usuarios ON pedidos.id_usuario = usuarios.id_usuario
            GROUP BY usuarios.nombre_usuario";
    
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// 16. Obtener el total de ventas por cliente
function obtenerVentasPorCliente() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $sql = "SELECT clientes.nombre_cliente AS cliente, COUNT(boletas.id_boleta) AS numeroCompras, SUM(boletas.total_boleta) AS total
            FROM boletas
            JOIN clientes ON boletas.id_cliente = clientes.id_cliente
            GROUP BY clientes.nombre_cliente";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>
