<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

$mensaje = '';
$clientes = [];


if (isset($_POST['buscar_cliente'])) {
    $busqueda = $_POST['busqueda_cliente'];
    $clientes = obtenerClientes($busqueda);

    if (empty($clientes)) {
        $mensaje = "No se encontraron clientes con el criterio de búsqueda.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $resultado = agregarCliente($nombre, '', $telefono, $direccion, '', '', ''); 

    if ($resultado) {
        $mensaje = "Cliente agregado correctamente.";
        $clientes = obtenerClientes($nombre);
    } else {
        $mensaje = "Error al agregar cliente.";
    }
}

$productos = obtenerProductosConDetalles();
$productosSeleccionados = []; 

if (isset($_POST['buscar_producto'])) {
    $busqueda = $_POST['busqueda_producto'];
    $productos = obtenerProductosConDetalles($busqueda);

    if (empty($productos)) {
        $mensaje = "No se encontraron productos con el criterio de búsqueda.";
    }
}

if (isset($_POST['agregar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre_producto'];
    $precio_venta = $_POST['precio_venta'];
    $cantidad = $_POST['cantidad'];
    $subtotal = $precio_venta * $cantidad;
    $productosSeleccionados[$id_producto] = [
        'nombre' => $nombre,
        'precio_venta' => $precio_venta,
        'cantidad' => $cantidad,
        'subtotal' => $subtotal
    ];
    $_SESSION['productos_seleccionados'] = $productosSeleccionados;
}

if (isset($_POST['quitar_producto'])) {
    $id_producto = $_POST['id_producto'];
    unset($productosSeleccionados[$id_producto]);
    $_SESSION['productos_seleccionados'] = $productosSeleccionados;
}

if (isset($_POST['actualizar_precio_cantidad'])) {
    $id_producto = $_POST['id_producto'];
    $nuevo_precio = $_POST['nuevo_precio'];
    $nueva_cantidad = $_POST['nueva_cantidad'];
    if (isset($productosSeleccionados[$id_producto])) {
        $productosSeleccionados[$id_producto]['precio_venta'] = $nuevo_precio;
        $productosSeleccionados[$id_producto]['cantidad'] = $nueva_cantidad;
        $productosSeleccionados[$id_producto]['subtotal'] = $nuevo_precio * $nueva_cantidad;
        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }
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
    <style>
        .alert-fixed-bottom {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 1000;
            min-width: 300px;
        }
    </style>
</head>
<body>
<?php include_once 'navbar_recepcion.php'; ?>
    <div class="container mt-4">
        <h1>Realizar Pedido</h1>
        
        <?php if ($mensaje): ?>
        <div class="alert alert-warning"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">Seleccionar Cliente</div>
            <div class="card-body">
                <div id="clienteSeleccionado" style="display: none;">
                    <h5>Cliente Seleccionado:</h5>
                    <p><strong>Nombre:</strong> <span id="clienteNombre"></span></p>
                    <p><strong>Apellido:</strong> <span id="clienteApellido"></span></p>
                    <p><strong>Descripción:</strong> <span id="clienteDescripcion"></span></p>
                    <p><strong>Teléfono:</strong> <span id="clienteTelefono"></span></p>
                    <p><strong>Dirección:</strong> <span id="clienteDireccion"></span></p>
                    <p><strong>Email:</strong> <span id="clienteEmail"></span></p>
                    <p><strong>Fecha de Registro:</strong> <span id="clienteFechaRegistro"></span></p>
                    <button class="btn btn-warning" onclick="quitarCliente()">Quitar</button>
                </div>

                <div id="buscarCliente">
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="busqueda_cliente" placeholder="Buscar cliente por nombre, teléfono o dirección" required>
                            <button class="btn btn-primary" type="submit" name="buscar_cliente">Buscar</button>
                        </div>
                    </form>

                    <?php if (!empty($clientes)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Descripción</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Email</th>
                                <th>Fecha de Registro</th>
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
                                    <?php echo htmlspecialchars($cliente['apellido_cliente']); ?>
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
                                    <?php echo htmlspecialchars($cliente['email_cliente']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($cliente['fecha_registro_cliente']); ?>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="seleccionarCliente(<?php echo htmlspecialchars(json_encode($cliente)); ?>)">Seleccionar</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>No se encontraron clientes. ¿Desea agregar uno nuevo?</p>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">Agregar Cliente</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="agregar_cliente" value="1">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="alertaClienteSeleccionado" class="alert alert-success alert-fixed-bottom" role="alert">
            Cliente seleccionado con éxito.
        </div>

        <div class="card mb-4">
            <div class="card-header">Seleccionar Producto</div>
            <div class="card-body">
                <form method="POST">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="busqueda_producto" placeholder="Buscar producto por nombre, categoría o subcategoría" required>
                        <button class="btn pulgarcito" type="submit" name="buscar_producto">Buscar</button>
                    </div>
                </form>

                <?php if (!empty($productos)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Precio Venta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_subcategoria']); ?></td>
                            <td>S/ <?php echo htmlspecialchars($producto['precio_venta']); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <input type="hidden" name="nombre_producto" value="<?php echo $producto['nombre_producto']; ?>">
                                    <input type="hidden" name="precio_venta" value="<?php echo $producto['precio_venta']; ?>">
                                    <input type="number" name="cantidad" value="1" min="1" class="form-control d-inline" style="width: 60px;">
                                    <button class="btn btn-success btn-sm" type="submit" name="agregar_producto">Agregar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Productos Seleccionados -->
        <div class="card mb-4">
            <div class="card-header">Productos Seleccionados</div>
            <div class="card-body">
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
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>S/ <?php echo htmlspecialchars($producto['precio_venta']); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td>S/ <?php echo htmlspecialchars($producto['subtotal']); ?></td>
                            <td>
                                <!-- Formulario para actualizar precio y cantidad -->
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                    <input type="number" name="nuevo_precio" value="<?php echo $producto['precio_venta']; ?>" min="1" step="0.01" class="form-control mb-2" placeholder="Nuevo precio">
                                    <input type="number" name="nueva_cantidad" value="<?php echo $producto['cantidad']; ?>" min="1" class="form-control mb-2" placeholder="Nueva cantidad">
                                    <button class="btn btn-primary btn-sm" type="submit" name="actualizar_precio_cantidad">Actualizar</button>
                                </form>

                                <!-- Botón para quitar producto -->
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                    <button class="btn btn-danger btn-sm" type="submit" name="quitar_producto">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <h5>Total Pedido: S/ <?php echo $total; ?></h5>
                <?php else: ?>
                    <p>No hay productos seleccionados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    let clienteSeleccionado = null;

    function seleccionarCliente(cliente) {
        clienteSeleccionado = cliente;
        document.getElementById("clienteNombre").textContent = cliente.nombre_cliente;
        document.getElementById("clienteApellido").textContent = cliente.apellido_cliente;
        document.getElementById("clienteDescripcion").textContent = cliente.descripcion_cliente;
        document.getElementById("clienteTelefono").textContent = cliente.telefono_cliente;
        document.getElementById("clienteDireccion").textContent = cliente.direccion_cliente;
        document.getElementById("clienteEmail").textContent = cliente.email_cliente;
        document.getElementById("clienteFechaRegistro").textContent = cliente.fecha_registro_cliente;

        document.getElementById("clienteSeleccionado").style.display = "block";
        document.getElementById("buscarCliente").style.display = "none";

        mostrarAlerta();
    }

    function quitarCliente() {
        clienteSeleccionado = null;
        document.getElementById("clienteSeleccionado").style.display = "none";
        document.getElementById("buscarCliente").style.display = "block";
    }

    function mostrarAlerta() {
        const alerta = document.getElementById("alertaClienteSeleccionado");
        alerta.style.display = "block"; 

        setTimeout(() => {
            alerta.style.display = "none";
        }, 2000);
    }
    </script>
</body>
</html>

