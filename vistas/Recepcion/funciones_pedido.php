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

function obtenerClientes($busqueda = null) {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $parametros = [];
    $sentencia = "SELECT c.id_cliente, c.nombre_cliente, c.descripcion_cliente, c.dni_cliente, c.email_cliente, t.telefono 
                  FROM clientes c
                  LEFT JOIN telefonos_cliente t ON c.id_cliente = t.id_cliente";

    if ($busqueda) {
        $sentencia .= " WHERE c.nombre_cliente LIKE ? OR c.dni_cliente LIKE ? OR t.telefono LIKE ?";
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

function obtenerTelefonosCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->prepare("SELECT telefono, es_principal FROM telefonos_cliente WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener teléfonos: " . $e->getMessage();
        return [];
    }
}
function obtenerDireccionesCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        // Cambié la columna 'direccion_cliente' por 'direccion', si es que tu columna se llama así.
        $stmt = $conn->prepare("SELECT direccion_cliente FROM direcciones_cliente WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener direcciones: " . $e->getMessage();
        return [];
    }
}

function calcularTotalPedido($productosSeleccionados) {
    $total = 0;
    foreach ($productosSeleccionados as $producto) {
        $total += $producto['subtotal'];
    }
    return $total;
}

function obtenerExistenciasProducto($id_producto)
{
    $conn = obtenerConexion();
    if (!$conn) {
        return 0;
    }

    try {
        $stmt = $conn->prepare("SELECT existencias FROM productos WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['existencias'] : 0;
    } catch (PDOException $e) {
        echo "Error al obtener existencias del producto: " . $e->getMessage();
        return 0;
    }
}

function crearPedido($id_cliente, $id_usuario, $total_pedido, $id_pago, $id_despacho)
{
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("INSERT INTO pedidos (id_cliente, id_usuario, fecha_pedido, total_pedido, id_estado, id_pago, id_tipo_despacho) 
                               VALUES (?, ?, NOW(), ?, 1, ?, ?)");
        $stmt->execute([$id_cliente, $id_usuario, $total_pedido, $id_pago, $id_despacho]);
        return $conn->lastInsertId(); // Retorna el ID del pedido
    } catch (PDOException $e) {
        echo "Error al crear pedido: " . $e->getMessage();
        return false;
    }
}

function agregarDetallePedido($id_pedido, $id_producto, $cantidad, $precio_venta)
{
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) 
                               VALUES (?, ?, ?, ?, ?)");
        $subtotal = $cantidad * $precio_venta;
        return $stmt->execute([$id_pedido, $id_producto, $cantidad, $precio_venta, $subtotal]);
    } catch (PDOException $e) {
        echo "Error al agregar detalle de pedido: " . $e->getMessage();
        return false;
    }
}

function descontarInventario($id_producto, $cantidad)
{
    $conn = obtenerConexion();
    if (!$conn) {
        return false;
    }

    try {
        $stmt = $conn->prepare("SELECT existencias FROM productos WHERE id_producto = :id_producto");
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            throw new Exception("Producto no encontrado.");
        }

        if ($producto['existencias'] < $cantidad) {
            throw new Exception("Cantidad insuficiente en el inventario para realizar el descuento.");
        }

        $sql = "UPDATE productos SET existencias = existencias - :cantidad WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (Exception $e) {
        echo "Error al descontar inventario: " . $e->getMessage();
        return false;
    }
}

function obtenerProductosConDetalles($busqueda = '')
{
    $conn = obtenerConexion();
    if (!$conn) {
        return [];
    }

    $parametros = [];
    $sentencia = "SELECT p.id_producto, p.nombre_producto, p.precio_venta, p.existencias, 
                         IFNULL(s.nombre_subcategoria, 'Sin subcategoría') AS nombre_subcategoria,
                         IFNULL(c.nombre_categoria, 'Sin categoría') AS nombre_categoria
                  FROM productos p
                  LEFT JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
                  LEFT JOIN categorias c ON s.id_categoria = c.id_categoria";

    if ($busqueda) {
        $sentencia .= " WHERE p.nombre_producto LIKE ? OR s.nombre_subcategoria LIKE ? OR c.nombre_categoria LIKE ?";
        $parametros[] = "%$busqueda%";
        $parametros[] = "%$busqueda%";
        $parametros[] = "%$busqueda%";
    }

    try {
        $stmt = $conn->prepare($sentencia);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener productos: " . $e->getMessage();
        return [];
    }
}