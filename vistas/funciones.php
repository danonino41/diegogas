<?php
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


function obtenerClientes($busqueda = null){
    $parametros = [];
    $sentencia = "SELECT * FROM clientes ";
    if(isset($busqueda)){
        $sentencia .= " WHERE nombre_cliente LIKE ? OR telefono_cliente LIKE ? OR direccion_cliente LIKE ?";
        array_push($parametros, "%".$busqueda."%", "%".$busqueda."%", "%".$busqueda."%"); 
    } 
    return select($sentencia, $parametros);
}

function select($sentencia, $parametros = []) {
    $conn = obtenerConexion();
    if (!$conn) {
        return []; 
    }

    try {
        $stmt = $conn->prepare($sentencia); 
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
        return [];
    }
}

function agregarCliente($nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("INSERT INTO clientes (nombre_cliente, apellido_cliente, telefono_cliente, direccion_cliente, coordenadas_cliente, email_cliente, descripcion_cliente, fecha_registro_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion]);
    } catch (PDOException $e) {
        echo "Error al agregar cliente: " . $e->getMessage();
        return false;
    }
}

function actualizarCliente($id, $nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("UPDATE clientes SET nombre_cliente = ?, apellido_cliente = ?, telefono_cliente = ?, direccion_cliente = ?, coordenadas_cliente = ?, email_cliente = ?, descripcion_cliente = ? WHERE id_cliente = ?");
        return $stmt->execute([$nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion, $id]);
    } catch (PDOException $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
        return false;
    }
}

function eliminarCliente($id) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
        return false;
    }
}

function obtenerProductos($busqueda = null) {
    $conn = obtenerConexion();
    if (!$conn) {
        return [];
    }

    $parametros = [];
    $sentencia = "SELECT p.*, s.nombre_subcategoria, c.nombre_categoria
                  FROM productos p
                  JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
                  JOIN categorias c ON s.id_categoria = c.id_categoria";

    if (isset($busqueda) && $busqueda !== '') {
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

function actualizarProducto($id, $nombre, $precio_venta, $existencias, $id_subcategoria, $id_proveedor) {
    $conn = obtenerConexion();
    if (!$conn) {
        return false;
    }

    try {
        $stmt = $conn->prepare("UPDATE productos SET nombre_producto = ?, precio_venta = ?, existencias = ?, id_subcategoria = ?, id_proveedor = ? WHERE id_producto = ?");
        return $stmt->execute([$nombre, $precio_venta, $existencias, $id_subcategoria, $id_proveedor, $id]);
    } catch (PDOException $e) {
        echo "Error al actualizar producto: " . $e->getMessage();
        return false;
    }
}

function eliminarProducto($id) {
    $conn = obtenerConexion();
    if (!$conn) {
        return false;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "Error al eliminar producto: " . $e->getMessage();
        return false;
    }
}

function obtenerSubcategorias() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->prepare("SELECT id_subcategoria, nombre_subcategoria FROM subcategorias");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener subcategorías: " . $e->getMessage();
        return [];
    }
}

function obtenerProveedores() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->prepare("SELECT id_proveedor, nombre_proveedor FROM proveedores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener proveedores: " . $e->getMessage();
        return [];
    }
}

function obtenerProductosConDetalles($busqueda = null) {
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


function agregarReabastecimiento($id_proveedor, $fecha_reabastecimiento, $total_reabastecimiento, $id_usuario) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("INSERT INTO reabastecimientos (id_proveedor, fecha_reabastecimiento, total_reabastecimiento, id_usuario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_proveedor, $fecha_reabastecimiento, $total_reabastecimiento, $id_usuario]);
        return $conn->lastInsertId(); 
    } catch (PDOException $e) {
        echo "Error al agregar reabastecimiento: " . $e->getMessage();
        return false;
    }
}

function agregarDetalleReabastecimiento($id_reabastecimiento, $id_producto, $cantidad, $precio_compra) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    $subtotal = $cantidad * $precio_compra;

    try {
        $stmt = $conn->prepare("INSERT INTO detalles_reabastecimiento (id_reabastecimiento, id_producto, cantidad, precio_compra, subtotal) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$id_reabastecimiento, $id_producto, $cantidad, $precio_compra, $subtotal]);
    } catch (PDOException $e) {
        echo "Error al agregar detalle de reabastecimiento: " . $e->getMessage();
        return false;
    }
}

function obtenerReabastecimientosConDetalles() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->query("
            SELECT r.id_reabastecimiento, r.fecha_reabastecimiento, r.total_reabastecimiento, 
                   p.nombre_proveedor, 
                   dr.id_producto, prod.nombre_producto, dr.cantidad, dr.precio_compra, dr.subtotal
            FROM reabastecimientos r
            JOIN proveedores p ON r.id_proveedor = p.id_proveedor
            JOIN detalles_reabastecimiento dr ON r.id_reabastecimiento = dr.id_reabastecimiento
            JOIN productos prod ON dr.id_producto = prod.id_producto
            ORDER BY r.fecha_reabastecimiento DESC
        ");
        
        $reabastecimientos = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id_reabastecimiento = $row['id_reabastecimiento'];
            if (!isset($reabastecimientos[$id_reabastecimiento])) {
                $reabastecimientos[$id_reabastecimiento] = [
                    'fecha' => $row['fecha_reabastecimiento'],
                    'total' => $row['total_reabastecimiento'],
                    'proveedor' => $row['nombre_proveedor'],
                    'detalles' => []
                ];
            }
            $reabastecimientos[$id_reabastecimiento]['detalles'][] = [
                'producto' => $row['nombre_producto'],
                'cantidad' => $row['cantidad'],
                'precio_compra' => $row['precio_compra'],
                'subtotal' => $row['subtotal']
            ];
        }

        return $reabastecimientos;
    } catch (PDOException $e) {
        echo "Error al obtener reabastecimientos: " . $e->getMessage();
        return [];
    }
}

function actualizarExistenciasProducto($id_producto, $cantidad_reabastecida) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("UPDATE productos SET existencias = existencias + ? WHERE id_producto = ?");
        return $stmt->execute([$cantidad_reabastecida, $id_producto]);
    } catch (PDOException $e) {
        echo "Error al actualizar existencias: " . $e->getMessage();
        return false;
    }
}

function obtenerStockPorPeso($peso) {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    try {
        $stmt = $conn->prepare("
            SELECT SUM(p.existencias) as total_stock
            FROM productos p
            JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
            WHERE s.nombre_subcategoria = ? OR s.nombre_subcategoria LIKE ? OR s.nombre_subcategoria LIKE ?
        ");
        $stmt->execute(["{$peso} kg", "% {$peso} kg", "%{$peso}kg"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_stock'] ?? 0;
    } catch (PDOException $e) {
        echo "Error al obtener stock por peso: " . $e->getMessage();
        return 0;
    }
}



function obtenerStockAccesorios($tipo) {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    try {
        $stmt = $conn->prepare("
            SELECT SUM(p.existencias) as total_stock
            FROM productos p
            JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
            WHERE s.nombre_subcategoria LIKE ?
        ");
        $stmt->execute(["%{$tipo}%"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_stock'] ?? 0;
    } catch (PDOException $e) {
        echo "Error al obtener stock de accesorios: " . $e->getMessage();
        return 0;
    }
}


function obtenerTotalProductos() {
    $conn = obtenerConexion();
    if (!$conn) return 0;

    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM productos");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        echo "Error al obtener el total de productos: " . $e->getMessage();
        return 0;
    }
}

function crearPedido($id_cliente, $id_usuario, $total_pedido, $id_pago, $id_despacho) {
    $conn = obtenerConexion();
    if (!$conn) return false;
    try {
        $stmt = $conn->prepare("INSERT INTO pedidos (id_cliente, id_usuario, fecha_pedido, total_pedido, id_estado, id_pago, id_tipo_despacho) VALUES (?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->execute([$id_cliente, $id_usuario, $total_pedido, 2, $id_pago, $id_despacho]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        echo "Error al crear pedido: " . $e->getMessage();
        return false;
    }
}


function agregarDetallePedido($id_pedido, $id_producto, $cantidad, $precio_venta) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $subtotal = $cantidad * $precio_venta;
        return $stmt->execute([$id_pedido, $id_producto, $cantidad, $precio_venta, $subtotal]);
    } catch (PDOException $e) {
        echo "Error al agregar detalle de pedido: " . $e->getMessage();
        return false;
    }
}


function actualizarEstadoPedido($id_pedido, $nuevo_estado) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("UPDATE pedidos SET id_estado = ? WHERE id_pedido = ?");
        $stmt->execute([$nuevo_estado, $id_pedido]);

        if ($nuevo_estado == 5) {
            devolverStockPedido($id_pedido);
        }
        return true;
    } catch (PDOException $e) {
        echo "Error al actualizar estado del pedido: " . $e->getMessage();
        return false;
    }
}

function obtenerMetodosPago() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->query("SELECT id_pago, descripcion_pago FROM tipos_pago");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener métodos de pago: " . $e->getMessage();
        return [];
    }
}

function obtenerTiposDespacho() {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->query("SELECT id_tipo_despacho, descripcion_despacho FROM tipo_despacho");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener tipos de despacho: " . $e->getMessage();
        return [];
    }
}

function agregarProductoSeleccionado($id_producto, $nombre, $precio_venta, $cantidad) {
    $subtotal = $precio_venta * $cantidad;
    $_SESSION['productos_seleccionados'][$id_producto] = [
        'nombre' => $nombre,
        'precio_venta' => $precio_venta,
        'cantidad' => $cantidad,
        'subtotal' => $subtotal
    ];
}

function quitarProductoSeleccionado($id_producto) {
    if (isset($_SESSION['productos_seleccionados'][$id_producto])) {
        unset($_SESSION['productos_seleccionados'][$id_producto]);
    }
}

function actualizarCantidadProducto($id_producto, $nueva_cantidad) {
    if (isset($_SESSION['productos_seleccionados'][$id_producto])) {
        $producto = &$_SESSION['productos_seleccionados'][$id_producto];
        $producto['cantidad'] = $nueva_cantidad;
        $producto['subtotal'] = $producto['precio_venta'] * $nueva_cantidad;
    }
}

function calcularTotalPedido($productos) {
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto['precio_venta'] * $producto['cantidad'];
    }
    return $total;
}









?>
