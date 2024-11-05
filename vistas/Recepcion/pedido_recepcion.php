<?php
session_start();
require_once '../funciones.php';

// Verificar sesión y rol del usuario
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

$mensaje = '';
$clientes = [];
$productosSeleccionados = $_SESSION['productos_seleccionados'] ?? [];
$clienteSeleccionado = $_SESSION['cliente_seleccionado'] ?? null;

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Buscar clientes
    if (isset($_POST['buscar_cliente'])) {
        $busqueda = $_POST['busqueda_cliente'];
        $clientes = obtenerClientes($busqueda);

        if (empty($clientes)) {
            $mensaje = "No se encontraron clientes con el criterio de búsqueda.";
        }
    }

    // Seleccionar cliente y guardarlo en sesión
    if (isset($_POST['seleccionar_cliente'])) {
        $clienteSeleccionado = [
            'id_cliente' => $_POST['id_cliente'],
            'nombre_cliente' => $_POST['nombre_cliente'],
            'descripcion_cliente' => $_POST['descripcion_cliente'],
            'telefono_cliente' => $_POST['telefono_cliente'],
            'direccion_cliente' => $_POST['direccion_cliente'],
        ];
        $_SESSION['cliente_seleccionado'] = $clienteSeleccionado;
    }

    // Quitar cliente
    if (isset($_POST['quitar_cliente'])) {
        unset($_SESSION['cliente_seleccionado']);
        $clienteSeleccionado = null;
    }

    // Agregar producto seleccionado a la sesión
    if (isset($_POST['agregar_producto'])) {
        $productosSeleccionados = $_SESSION['productos_seleccionados'] ?? [];

        $id_producto = $_POST['id_producto'];
        $nombre = $_POST['nombre_producto'];
        $precio_venta = $_POST['precio_venta'];
        $cantidad = $_POST['cantidad'];
        $subtotal = $precio_venta * $cantidad;

        if (isset($productosSeleccionados[$id_producto])) {
            $productosSeleccionados[$id_producto]['cantidad'] += $cantidad;
            $productosSeleccionados[$id_producto]['subtotal'] += $subtotal;
        } else {
            $productosSeleccionados[$id_producto] = [
                'id_producto' => $id_producto,
                'nombre' => $nombre,
                'precio_venta' => $precio_venta,
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ];
        }
    }
        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }

    if (isset($_POST['quitar_producto'])) {
        $id_producto = $_POST['id_producto'];
        $productosSeleccionados = $_SESSION['productos_seleccionados'] ?? [];
        unset($productosSeleccionados[$id_producto]);
        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }

    if (isset($_POST['confirmar_pedido'])) {
        if (isset($clienteSeleccionado['id_cliente']) && !empty($productosSeleccionados)) {
            $id_cliente = $clienteSeleccionado['id_cliente'];
            $id_usuario = $_SESSION['id_usuario'];
            $total_pedido = calcularTotalPedido($productosSeleccionados);

            if (!isset($_POST['tipoPago']) || !isset($_POST['tipoDespacho'])) {
                $mensaje = "Error: Tipo de pago y despacho son obligatorios.";
                return;
            }

            $id_pago = $_POST['tipoPago'];
            $id_despacho = $_POST['tipoDespacho'];

            foreach ($productosSeleccionados as $producto) {
                $id_producto = $producto['id_producto'];
                $cantidad = $producto['cantidad'];
                
                $existencias = obtenerExistenciasProducto($id_producto);
                if ($existencias < $cantidad) {
                    $mensaje = "Error: No hay suficiente inventario para el producto " . htmlspecialchars($producto['nombre']);
                    return;
                }
            }

            $id_pedido = crearPedido($id_cliente, $id_usuario, $total_pedido, $id_pago, $id_despacho);

            if ($id_pedido) {
                foreach ($productosSeleccionados as $producto) {
                    $id_producto = $producto['id_producto'];
                    $cantidad = $producto['cantidad'];
                    $precio_venta = $producto['precio_venta'];

                    if (agregarDetallePedido($id_pedido, $id_producto, $cantidad, $precio_venta)) {
                        descontarInventario($id_producto, $cantidad);
                    } else {
                        $mensaje = "Error al agregar detalle de pedido para el producto: " . htmlspecialchars($producto['nombre']);
                    }
                }

                unset($_SESSION['cliente_seleccionado']);
                unset($_SESSION['productos_seleccionados']);
                $clienteSeleccionado = null;
                $productosSeleccionados = [];
                $mensaje = "Pedido creado exitosamente.";
            } else {
                $mensaje = "Error al crear pedido. Verifique los datos y vuelva a intentarlo.";
            }
        } else {
            if (!isset($clienteSeleccionado['id_cliente'])) {
                $mensaje = "Error: No se ha seleccionado un cliente.";
            } elseif (empty($productosSeleccionados)) {
                $mensaje = "Error: No hay productos seleccionados para el pedido.";
            }
        }
    }


    $productos = obtenerProductosConDetalles();
    $metodosPago = obtenerMetodosPago();
    $tiposDespacho = obtenerTiposDespacho();


    $mensaje = '';
    $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

    $productos = obtenerProductos($busqueda);
    $subcategorias = obtenerSubcategorias();
    $proveedores = obtenerProveedores();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_producto'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio_venta = $_POST['precio_venta'];
    $existencias = $_POST['existencias'];
    $id_subcategoria = $_POST['id_subcategoria'];
    $id_proveedor = $_POST['id_proveedor'];

    if (actualizarProducto($id, $nombre, $precio_venta, $existencias, $id_subcategoria, $id_proveedor)) {
        $mensaje = "Producto actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar producto.";
    }
    $productos = obtenerProductos();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;

    $apellido = null;
    $coordenadas = null;
    $email = null;

    if ($nombre && $descripcion && $telefono && $direccion) {
        if (agregarCliente($nombre, $apellido, $telefono, $direccion, $coordenadas, $email, $descripcion)) {
            $mensaje = "Cliente agregado correctamente.";
        } else {
            $mensaje = "Error al agregar cliente.";
        }
    } else {
        $mensaje = "Por favor completa todos los campos obligatorios.";
    }
    $clientes = obtenerClientes();
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1 style="color:green">Realizar Pedido</h1>

        <?php if ($mensaje): ?>
        <div class="alert alert-warning">
            <?php echo $mensaje; ?>
        </div>
        <?php endif; ?>

        <BR>

        <h1>Seleccionar Clientes</h1>
        <form method="POST">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="busqueda_cliente" placeholder="Buscar cliente" required>
                <button class="btn btn-primary" type="submit" name="buscar_cliente">Buscar</button>
            </div>
        </form>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-2"></i> Lista de Clientes</span>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarClienteModal">Agregar
                    Cliente</button>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <?php if ($clienteSeleccionado): ?>
                <h5>Cliente Seleccionado:</h5>
                <p><strong>Nombre:</strong>
                    <?php echo htmlspecialchars($clienteSeleccionado['nombre_cliente']); ?>
                </p>
                <p><strong>Descripción:</strong>
                    <?php echo htmlspecialchars($clienteSeleccionado['descripcion_cliente']); ?>
                </p>
                <p><strong>Teléfono:</strong>
                    <?php echo htmlspecialchars($clienteSeleccionado['telefono_cliente']); ?>
                </p>
                <p><strong>Dirección:</strong>
                    <?php echo htmlspecialchars($clienteSeleccionado['direccion_cliente']); ?>
                </p>
                <form method="POST">
                    <button class="btn btn-warning" type="submit" name="quitar_cliente">Quitar</button>
                </form>
                <?php else: ?>
                <?php if (!empty($clientes)): ?>
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($cliente['nombre_cliente']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($cliente['descripcion_cliente']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($cliente['telefono_cliente']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($cliente['direccion_cliente']); ?>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id_cliente"
                                        value="<?php echo $cliente['id_cliente']; ?>">
                                    <input type="hidden" name="nombre_cliente"
                                        value="<?php echo $cliente['nombre_cliente']; ?>">
                                    <input type="hidden" name="descripcion_cliente"
                                        value="<?php echo $cliente['descripcion_cliente']; ?>">
                                    <input type="hidden" name="telefono_cliente"
                                        value="<?php echo $cliente['telefono_cliente']; ?>">
                                    <input type="hidden" name="direccion_cliente"
                                        value="<?php echo $cliente['direccion_cliente']; ?>">
                                    <button class="btn btn-success btn-sm" type="submit"
                                        name="seleccionar_cliente">Seleccionar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center">No se encontraron clientes.</p>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal fade" id="agregarClienteModal" tabindex="-1" aria-labelledby="agregarClienteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarClienteModalLabel">Agregar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="agregar_cliente">Guardar
                                Cliente</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <!-- Seleccionar Productos -->
        <h1 class="mb-3">Seleccionar productos</h1>
        <form method="GET" action="pedido_recepcion.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control"
                    placeholder="Buscar producto por nombre, categoría o subcategoría"
                    value="<?php echo htmlspecialchars($busqueda); ?>" aria-label="Buscar producto">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>

        <div class="card mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-table me-2"></i> Lista de Productos
                </div>
                <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio Venta</th>
                                    <th>Existencias</th>
                                    <th>Subcategoría</th>
                                    <th>Categoría</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No se encontraron productos.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($producto['precio_venta']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($producto['existencias']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($producto['nombre_subcategoria']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($producto['nombre_categoria']); ?>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" class="d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="id_producto"
                                                value="<?php echo $producto['id_producto']; ?>">
                                            <input type="hidden" name="nombre_producto"
                                                value="<?php echo $producto['nombre_producto']; ?>">
                                            <input type="hidden" name="precio_venta"
                                                value="<?php echo $producto['precio_venta']; ?>">
                                            <input type="number" name="cantidad" value="1" min="1"
                                                class="form-control text-center" style="width: 60px;"
                                                aria-label="Cantidad de <?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm ms-1" type="submit"
                                            name="agregar_producto">
                                            <i class="fas fa-plus"></i> Agregar
                                        </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Productos Seleccionados -->
        <div class="card mb-4">
            <div class="card-header">Productos Seleccionados</div>
            <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                <div class="table-responsive">
                    <?php if (!empty($productosSeleccionados)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio Venta</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($productosSeleccionados as $id => $producto): 
                                $total += $producto['subtotal'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </td>
                                <td>S/
                                    <?php echo htmlspecialchars($producto['precio_venta']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($producto['cantidad']); ?>
                                </td>
                                <td>S/
                                    <?php echo htmlspecialchars($producto['subtotal']); ?>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button class="btn btn-danger btn-sm" type="submit"
                                            name="quitar_producto">Quitar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <h5>Total Pedido: S/
                        <?php echo $total; ?>
                    </h5>
                    <?php else: ?>
                    <p>No hay productos seleccionados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- Tipos de Pago y Despacho -->
        <div class="card mb-4">
            <div class="card-header">Opciones de Pago y Despacho</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="tipoPago" class="form-label">Método de Pago</label>
                        <select id="tipoPago" name="tipoPago" class="form-select">
                            <?php foreach ($metodosPago as $pago): ?>
                            <option value="<?php echo $pago['id_pago']; ?>">
                                <?php echo $pago['descripcion_pago']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tipoDespacho" class="form-label">Tipo de Despacho</label>
                        <select id="tipoDespacho" name="tipoDespacho" class="form-select">
                            <?php foreach ($tiposDespacho as $despacho): ?>
                            <option value="<?php echo $despacho['id_tipo_despacho']; ?>">
                                <?php echo $despacho['descripcion_despacho']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="confirmar_pedido">Confirmar Pedido</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>