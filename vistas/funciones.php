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

function agregarCliente($nombre, $apellido = null, $dni_cliente = null, $email = null, $descripcion = null) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("
            INSERT INTO clientes (nombre_cliente, apellido_cliente, dni_cliente, email_cliente, descripcion_cliente, fecha_registro_cliente) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$nombre, $apellido, $dni_cliente, $email, $descripcion]);
        return $conn->lastInsertId(); // Retorna el ID del cliente insertado
    } catch (PDOException $e) {
        echo "Error al agregar cliente: " . $e->getMessage();
        return false;
    }
}

function actualizarCliente($id_cliente, $nombre, $apellido = null, $email = null, $descripcion = null, $dni_cliente = null) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        // Validar si el DNI ya existe en otro cliente
        if (!empty($dni_cliente)) {
            $stmtCheck = $conn->prepare("
                SELECT id_cliente 
                FROM clientes 
                WHERE dni_cliente = ? AND id_cliente != ?
            ");
            $stmtCheck->execute([$dni_cliente, $id_cliente]);
            $result = $stmtCheck->fetch();

            if ($result) {
                throw new Exception("El DNI proporcionado ya está registrado en otro cliente.");
            }
        }

        $stmt = $conn->prepare("
            UPDATE clientes 
            SET nombre_cliente = ?, apellido_cliente = ?, email_cliente = ?, descripcion_cliente = ?, dni_cliente = ?
            WHERE id_cliente = ?
        ");
        return $stmt->execute([$nombre, $apellido, $email, $descripcion, $dni_cliente, $id_cliente]);
    } catch (Exception $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
        return false;
    }
}

function obtenerClientesConTelefonoPrincipal($busqueda = null) {
    $conn = obtenerConexion();
    if (!$conn) return [];

    $parametros = [];
    $query = "
        SELECT c.id_cliente, c.nombre_cliente, c.apellido_cliente, c.dni_cliente, c.email_cliente, 
               c.descripcion_cliente, c.fecha_registro_cliente, t.telefono AS telefono_principal
        FROM clientes c
        LEFT JOIN telefonos_cliente t ON c.id_cliente = t.id_cliente AND t.es_principal = 1
    ";

    if ($busqueda) {
        $query .= " WHERE c.nombre_cliente LIKE ? OR t.telefono LIKE ? OR c.dni_cliente LIKE ?";
        $parametros[] = "%$busqueda%";
        $parametros[] = "%$busqueda%";
        $parametros[] = "%$busqueda%";
    }

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener clientes con teléfono principal: " . $e->getMessage();
        return [];
    }
}

function agregarTelefonoCliente($id_cliente, $telefono, $es_principal = false) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        if ($es_principal) {
            $stmt = $conn->prepare("UPDATE telefonos_cliente SET es_principal = 0 WHERE id_cliente = ?");
            $stmt->execute([$id_cliente]);
        }

        $stmt = $conn->prepare("INSERT INTO telefonos_cliente (id_cliente, telefono, es_principal) VALUES (?, ?, ?)");
        return $stmt->execute([$id_cliente, $telefono, $es_principal]);
    } catch (PDOException $e) {
        echo "Error al agregar teléfono: " . $e->getMessage();
        return false;
    }
}

function actualizarTelefonoCliente($id_telefono, $telefono, $es_principal = false) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        if ($es_principal) {
            $stmt = $conn->prepare("UPDATE telefonos_cliente SET es_principal = 0 WHERE id_cliente = (SELECT id_cliente FROM telefonos_cliente WHERE id_telefono = ?)");
            $stmt->execute([$id_telefono]);
        }

        $stmt = $conn->prepare("UPDATE telefonos_cliente SET telefono = ?, es_principal = ? WHERE id_telefono = ?");
        return $stmt->execute([$telefono, $es_principal, $id_telefono]);
    } catch (PDOException $e) {
        echo "Error al actualizar teléfono: " . $e->getMessage();
        return false;
    }
}

function actualizarTelefonoPrincipal($id_cliente, $nuevo_telefono) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $conn->beginTransaction();

        // Marcar todos los teléfonos como no principales
        $stmt1 = $conn->prepare("
            UPDATE telefonos_cliente 
            SET es_principal = 0 
            WHERE id_cliente = ?
        ");
        $stmt1->execute([$id_cliente]);

        // Buscar si el teléfono ya existe
        $stmt2 = $conn->prepare("
            SELECT id_telefono 
            FROM telefonos_cliente 
            WHERE id_cliente = ? AND telefono = ?
        ");
        $stmt2->execute([$id_cliente, $nuevo_telefono]);
        $telefono = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($telefono) {
            // Si existe, actualizar como principal
            $stmt3 = $conn->prepare("
                UPDATE telefonos_cliente 
                SET es_principal = 1 
                WHERE id_telefono = ?
            ");
            $stmt3->execute([$telefono['id_telefono']]);
        } else {
            // Si no existe, agregar como principal
            $stmt4 = $conn->prepare("
                INSERT INTO telefonos_cliente (id_cliente, telefono, es_principal) 
                VALUES (?, ?, 1)
            ");
            $stmt4->execute([$id_cliente, $nuevo_telefono]);
        }

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error al actualizar teléfono principal: " . $e->getMessage();
        return false;
    }
}

function eliminarTelefonoCliente($id_telefono) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("DELETE FROM telefonos_cliente WHERE id_telefono = ?");
        return $stmt->execute([$id_telefono]);
    } catch (PDOException $e) {
        echo "Error al eliminar teléfono: " . $e->getMessage();
        return false;
    }
}

function eliminarCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        // Eliminar teléfonos asociados al cliente
        $stmtTelefonos = $conn->prepare("DELETE FROM telefonos_cliente WHERE id_cliente = ?");
        $stmtTelefonos->execute([$id_cliente]);

        // Eliminar cliente
        $stmtCliente = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        return $stmtCliente->execute([$id_cliente]);
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
        return false;
    }
}


function obtenerDireccionesCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return [];
    try {
        $stmt = $conn->prepare("SELECT id_direccion, direccion, coordenadas, descripcion, es_principal FROM direcciones_cliente WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener direcciones: " . $e->getMessage();
        return [];
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

function agregarDireccionCliente($id_cliente, $direccion, $coordenadas = null, $descripcion = null, $es_principal = false) {
    $conn = obtenerConexion();
    if (!$conn) return false;
    try {
        if ($es_principal) {
            $stmt = $conn->prepare("UPDATE direcciones_cliente SET es_principal = 0 WHERE id_cliente = ?");
            $stmt->execute([$id_cliente]);
        }
        $stmt = $conn->prepare("INSERT INTO direcciones_cliente (id_cliente, direccion, coordenadas, descripcion, es_principal) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$id_cliente, $direccion, $coordenadas, $descripcion, $es_principal]);
    } catch (PDOException $e) {
        echo "Error al agregar dirección: " . $e->getMessage();
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

function obtenerHistorialComprasCliente($id_cliente) {
    $conn = obtenerConexion();
    $sql = "SELECT p.id_pedido, p.fecha_pedido, p.total_pedido, ep.descripcion_estado AS estado
            FROM pedidos p
            JOIN estado_pedido ep ON p.id_estado = ep.id_estado
            WHERE p.id_cliente = :id_cliente
            ORDER BY p.fecha_pedido DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function actualizarDireccionCliente($id_direccion, $direccion, $coordenadas = null, $descripcion = null, $es_principal = false) {
    $conn = obtenerConexion();
    if (!$conn) return false;
    try {
        if ($es_principal) {
            $stmt = $conn->prepare("UPDATE direcciones_cliente SET es_principal = 0 WHERE id_cliente = (SELECT id_cliente FROM direcciones_cliente WHERE id_direccion = ?)");
            $stmt->execute([$id_direccion]);
        }
        $stmt = $conn->prepare("UPDATE direcciones_cliente SET direccion = ?, coordenadas = ?, descripcion = ?, es_principal = ? WHERE id_direccion = ?");
        return $stmt->execute([$direccion, $coordenadas, $descripcion, $es_principal, $id_direccion]);
    } catch (PDOException $e) {
        echo "Error al actualizar dirección: " . $e->getMessage();
        return false;
    }
}

function eliminarDireccionCliente($id_direccion) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $stmt = $conn->prepare("DELETE FROM direcciones_cliente WHERE id_direccion = ?");
        return $stmt->execute([$id_direccion]);
    } catch (PDOException $e) {
        echo "Error al eliminar dirección: " . $e->getMessage();
        return false;
    }
}

function actualizarDescripcionCliente($id_cliente, $nueva_descripcion) {
    $conexion = obtenerConexion();
    if (!$conexion) {
        return false; 
    }
    try {
        $sql = "UPDATE clientes SET descripcion_cliente = :descripcion WHERE id_cliente = :id_cliente";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':descripcion', $nueva_descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error al actualizar la descripción: " . $e->getMessage();
        return false;
    }
}


function establecerDireccionPrincipal($id_cliente, $id_direccion) {
    $conn = obtenerConexion();
    if (!$conn) return false;

    try {
        $conn->beginTransaction();
        $stmt1 = $conn->prepare("UPDATE direcciones_cliente SET es_principal = FALSE WHERE id_cliente = ?");
        $stmt1->execute([$id_cliente]);

        $stmt2 = $conn->prepare("UPDATE direcciones_cliente SET es_principal = TRUE WHERE id_direccion = ?");
        $stmt2->execute([$id_direccion]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error al establecer dirección principal: " . $e->getMessage();
        return false;
    }
}

function obtenerHistorialCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return [];
    try {
        $stmt = $conn->prepare("
            SELECT p.id_pedido, p.fecha_pedido, p.total_pedido, ep.descripcion_estado AS estado, dc.direccion
            FROM pedidos p
            JOIN estado_pedido ep ON p.id_estado = ep.id_estado
            JOIN direcciones_cliente dc ON p.id_direccion = dc.id_direccion
            WHERE p.id_cliente = ?
            ORDER BY p.fecha_pedido DESC
        ");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener historial de compras: " . $e->getMessage();
        return [];
    }
}

function obtenerClientePorId($id_cliente) {
    $conn = obtenerConexion();
    $sql = "SELECT * FROM clientes WHERE id_cliente = :id_cliente";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerTelefonosPorCliente($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return [];

    try {
        $stmt = $conn->prepare("SELECT id_telefono, telefono, es_principal FROM telefonos_cliente WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener teléfonos: " . $e->getMessage();
        return [];
    }
}

function obtenerTelefonoPrincipal($id_cliente) {
    $conn = obtenerConexion();
    if (!$conn) return null;

    try {
        $stmt = $conn->prepare("
            SELECT telefono 
            FROM telefonos_cliente 
            WHERE id_cliente = ? AND es_principal = 1
            LIMIT 1
        ");
        $stmt->execute([$id_cliente]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['telefono'] : null; // Asegúrate de que 'telefono' sea el nombre correcto
    } catch (PDOException $e) {
        echo "Error al obtener teléfono principal: " . $e->getMessage();
        return null;
    }
}

function obtenerDireccionesPorCliente($id_cliente) {
    $conn = obtenerConexion();
    $sql = "SELECT * FROM direcciones_cliente WHERE id_cliente = :id_cliente";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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


function actualizarEstadoPedido($id_pedido, $id_estado, $id_empleado = null) {
    $conexion = obtenerConexion();

    try {
        // Iniciar transacción
        $conexion->beginTransaction();

        // Actualizar el estado del pedido en la tabla 'pedidos'
        $sql = "UPDATE pedidos SET id_estado = :id_estado WHERE id_pedido = :id_pedido";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
        $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();

        // Insertar el cambio de estado en 'historial_estado_pedidos'
        $sqlHistorial = "INSERT INTO historial_estado_pedidos (id_pedido, id_estado, fecha_cambio, id_empleado)
                         VALUES (:id_pedido, :id_estado, NOW(), :id_empleado)";
        $stmtHistorial = $conexion->prepare($sqlHistorial);
        $stmtHistorial->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmtHistorial->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
        $stmtHistorial->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
        $stmtHistorial->execute();

        // Confirmar transacción
        $conexion->commit();

        return true;
    } catch (Exception $e) {
        // Si ocurre un error, deshacer transacción
        $conexion->rollBack();
        echo "Error al actualizar el estado del pedido: " . $e->getMessage();
        return false;
    }
}

function obtenerHistorialEstadoPedido($id_pedido) {
    $conexion = obtenerConexion();
    $sql = "SELECT ep.descripcion_estado AS estado, hep.fecha_cambio, e.nombre_empleado
            FROM historial_estado_pedidos hep
            JOIN estado_pedido ep ON hep.id_estado = ep.id_estado
            LEFT JOIN empleados e ON hep.id_empleado = e.id_empleado
            WHERE hep.id_pedido = :id_pedido
            ORDER BY hep.fecha_cambio ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

function descontarInventario($id_producto, $cantidad) {
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

function obtenerPedidosPorEstado($estado) {
    $conexion = obtenerConexion();
    $sql = "SELECT p.id_pedido, c.nombre_cliente, ep.descripcion_estado AS estado, p.total_pedido AS total, p.fecha_pedido AS fecha
            FROM pedidos p
            JOIN clientes c ON p.id_cliente = c.id_cliente
            JOIN estado_pedido ep ON p.id_estado = ep.id_estado
            WHERE ep.descripcion_estado = :estado";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtenerHistorialPedidos($limite = 5) {
    $conexion = obtenerConexion();
    $sql = "SELECT p.id_pedido, c.nombre_cliente, e.descripcion_estado AS estado, p.total_pedido AS total, p.fecha_pedido AS fecha
            FROM pedidos p
            JOIN clientes c ON p.id_cliente = c.id_cliente
            JOIN estado_pedido e ON p.id_estado = e.id_estado
            ORDER BY p.fecha_pedido DESC
            LIMIT :limite";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProductosBajoInventario($limiteStock = 10) {
    $conexion = obtenerConexion();
    $sql = "SELECT p.nombre_producto, c.nombre_categoria, p.existencias 
            FROM productos p
            JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
            JOIN categorias c ON s.id_categoria = c.id_categoria
            WHERE p.existencias <= :limiteStock";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':limiteStock', $limiteStock, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtenerExistenciasProducto($id_producto) {
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

function verificarContrasena($id_usuario, $contrasena_actual) {
    $conexion = obtenerConexion();
    $sql = "SELECT password_usuario FROM usuarios WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $usuario && $usuario['password_usuario'] === $contrasena_actual;
}

function cambiarContrasena($id_usuario, $nueva_contrasena) {
    $conexion = obtenerConexion();
    $sql = "UPDATE usuarios SET password_usuario = ? WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([$nueva_contrasena, $id_usuario]);
}

function obtenerDatosUsuario($id_usuario) {
    $conn = obtenerConexion(); // Obtener la conexión a la base de datos
    if (!$conn) {
        return false; // Retornar falso si no hay conexión
    }

    try {
        // Consulta SQL corregida y clara
        $sql = "SELECT 
                    u.nombre_usuario,
                    e.nombre_empleado, 
                    e.apellido_empleado, 
                    e.telefono_empleado, 
                    e.numero_documento, 
                    e.tipo_documento, 
                    e.fecha_registro_empleado AS fecha_registro_usuario
                FROM usuarios AS u
                JOIN empleados AS e ON u.id_empleado = e.id_empleado
                WHERE u.id_usuario = :id_usuario";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);

        // Enlazar el parámetro :id_usuario
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retornar el resultado o null si está vacío
        return $resultado ?: null;
    } catch (PDOException $e) {
        // Manejar errores y mostrar mensaje
        echo "Error en la consulta: " . $e->getMessage();
        return false;
    }
}





function obtenerMotorizados() {
    $conexion = obtenerConexion();
    $sql = "SELECT e.id_empleado, e.nombre_empleado, e.apellido_empleado 
            FROM empleados e
            JOIN usuarios u ON e.id_empleado = u.id_empleado
            JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
            JOIN roles r ON ur.id_rol = r.id_rol
            WHERE r.nombre_rol = 'Motorizado'";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function anularPedido($id_pedido) {
    $conexion = obtenerConexion();
    $sql = "UPDATE pedidos SET id_estado = (SELECT id_estado FROM estado_pedido WHERE descripcion_estado = 'Cancelado') WHERE id_pedido = :id_pedido";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    return $stmt->execute();
}

function revertirStock($id_pedido) {
    $conexion = obtenerConexion();

    $sql = "SELECT id_producto, cantidad FROM detalle_pedido WHERE id_pedido = :id_pedido";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as $producto) {
        $sqlUpdate = "UPDATE productos SET existencias = existencias + :cantidad WHERE id_producto = :id_producto";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_producto', $producto['id_producto'], PDO::PARAM_INT);
        $stmtUpdate->execute();
    }
}

function cancelarVenta($id_pedido) {
    if (anularPedido($id_pedido)) {
        revertirStock($id_pedido);
        return true;
    } else {
        return false;
    }
}

?>
