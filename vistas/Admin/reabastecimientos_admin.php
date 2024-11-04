<?php
session_start();
require_once '../funciones.php';

// Verificar sesión y rol
if (!isset($_SESSION['id_usuario']) || !in_array('Administrador', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Manejo de datos
$mensaje = '';
$productos = obtenerProductosConDetalles();
$proveedores = obtenerProveedores();
$reabastecimientos = obtenerReabastecimientosConDetalles();

// Agregar un reabastecimiento y sus detalles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_reabastecimiento'])) {
    $id_proveedor = $_POST['id_proveedor'];
    $fecha_reabastecimiento = date('Y-m-d');
    $total_reabastecimiento = $_POST['total_reabastecimiento'];
    $id_usuario = $_SESSION['id_usuario'];

    // Insertar el reabastecimiento en la base de datos
    $id_reabastecimiento = agregarReabastecimiento($id_proveedor, $fecha_reabastecimiento, $total_reabastecimiento, $id_usuario);

    if ($id_reabastecimiento) {
        // Agregar cada detalle de reabastecimiento y actualizar existencias
        foreach ($_POST['detalles'] as $detalle) {
            $id_producto = $detalle['id_producto'];
            $cantidad = $detalle['cantidad'];
            $precio_compra = $detalle['precio_compra'];

            // Agregar detalle del reabastecimiento
            if (agregarDetalleReabastecimiento($id_reabastecimiento, $id_producto, $cantidad, $precio_compra)) {
                // Actualizar existencias del producto
                actualizarExistenciasProducto($id_producto, $cantidad);
            }
        }
        $mensaje = "Reabastecimiento agregado y existencias actualizadas correctamente.";
    } else {
        $mensaje = "Error al agregar reabastecimiento.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reabastecimientos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once "../../incluidos/encabezado_admin.php";?>

    <div class="container mt-4">
        <h1>Reabastecimientos</h1>

        <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <!-- Botón para abrir el modal de agregar reabastecimiento -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarReabastecimiento">
            <i class="fas fa-plus"></i> Agregar Reabastecimiento
        </button>

        <!-- Tabla de Reabastecimientos -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Lista de Reabastecimientos
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Total</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reabastecimientos as $id_reabastecimiento => $reabastecimiento): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reabastecimiento['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($reabastecimiento['proveedor']); ?></td>
                                <td><?php echo number_format($reabastecimiento['total'], 2); ?></td>
                                <td>
                                    <ul>
                                        <?php foreach ($reabastecimiento['detalles'] as $detalle): ?>
                                        <li>
                                            Producto: <?php echo htmlspecialchars($detalle['producto']); ?>,
                                            Cantidad: <?php echo $detalle['cantidad']; ?>,
                                            Precio Compra: <?php echo number_format($detalle['precio_compra'], 2); ?>,
                                            Subtotal: <?php echo number_format($detalle['subtotal'], 2); ?>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="modalAgregarReabastecimiento" tabindex="-1" aria-labelledby="modalAgregarReabastecimientoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="reabastecimientos_admin.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAgregarReabastecimientoLabel">Agregar Reabastecimiento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="id_proveedor" class="form-label">Proveedor</label>
                                <select class="form-select" id="id_proveedor" name="id_proveedor" required>
                                    <?php foreach ($proveedores as $proveedor): ?>
                                        <option value="<?php echo $proveedor['id_proveedor']; ?>"><?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <label class="form-label">Detalles de Productos</label>
                            <div id="detalleProductos">
                                <div class="row mb-2">
                                    <div class="col-3">
                                        <select name="detalles[0][id_producto]" class="form-select" required onchange="actualizarDetalleProducto(this)">
                                            <option value="">Seleccione un producto</option>
                                            <?php foreach ($productos as $producto): ?>
                                                <option value="<?php echo $producto['id_producto']; ?>"
                                                        data-precio-compra="<?php echo $producto['precio_compra']; ?>"
                                                        data-precio-venta="<?php echo $producto['precio_venta']; ?>"
                                                        data-subcategoria="<?php echo htmlspecialchars($producto['nombre_subcategoria']); ?>"
                                                        data-proveedor="<?php echo htmlspecialchars($producto['nombre_proveedor']); ?>">
                                                    <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" name="detalles[0][subcategoria]" class="form-control" placeholder="Subcategoría" readonly>
                                    </div>
                                    <div class="col-2">
                                        <input type="text" name="detalles[0][proveedor]" class="form-control" placeholder="Proveedor" readonly>
                                    </div>
                                    <div class="col-2">
                                        <input type="number" name="detalles[0][precio_compra]" class="form-control" placeholder="Precio Compra" readonly>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" name="detalles[0][cantidad]" class="form-control" placeholder="Cantidad" required onchange="calcularSubtotal(this)">
                                    </div>
                                    <div class="col-2">
                                        <input type="text" name="detalles[0][subtotal]" class="form-control" placeholder="Subtotal" readonly>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" onclick="agregarDetalleProducto()">Añadir Producto</button>

                            <!-- Campo de total general -->
                            <div class="mb-3 mt-3">
                                <label for="total_reabastecimiento" class="form-label">Total General</label>
                                <input type="text" class="form-control" id="total_reabastecimiento" name="total_reabastecimiento" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" name="agregar_reabastecimiento">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let detalleIndex = 1;
        let totalGeneral = 0;

        function actualizarDetalleProducto(selectElement) {
            const selectedOption = selectElement.selectedOptions[0];
            const row = selectElement.closest('.row');
            
            row.querySelector('input[name$="[subcategoria]"]').value = selectedOption.getAttribute('data-subcategoria');
            row.querySelector('input[name$="[proveedor]"]').value = selectedOption.getAttribute('data-proveedor');
            row.querySelector('input[name$="[precio_compra]"]').value = selectedOption.getAttribute('data-precio-compra');
            
            // Resetear subtotal y cantidad en caso de cambio de producto
            row.querySelector('input[name$="[cantidad]"]').value = '';
            row.querySelector('input[name$="[subtotal]"]').value = '';
            calcularTotalGeneral();
        }

        function calcularSubtotal(inputElement) {
            const row = inputElement.closest('.row');
            const cantidad = parseFloat(inputElement.value);
            const precioCompra = parseFloat(row.querySelector('input[name$="[precio_compra]"]').value);
            
            const subtotal = cantidad * precioCompra;
            row.querySelector('input[name$="[subtotal]"]').value = subtotal.toFixed(2);

            calcularTotalGeneral();
        }

        function calcularTotalGeneral() {
            const subtotales = document.querySelectorAll('input[name$="[subtotal]"]');
            totalGeneral = Array.from(subtotales).reduce((total, input) => {
                return total + (parseFloat(input.value) || 0);
            }, 0);
            document.getElementById("total_reabastecimiento").value = totalGeneral.toFixed(2);
        }

        function agregarDetalleProducto() {
            const container = document.getElementById("detalleProductos");
            const newDetalle = `
                <div class="row mb-2">
                    <div class="col-3">
                        <select name="detalles[${detalleIndex}][id_producto]" class="form-select" required onchange="actualizarDetalleProducto(this)">
                            <option value="">Seleccione un producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['id_producto']; ?>"
                                        data-precio-compra="<?php echo $producto['precio_compra']; ?>"
                                        data-precio-venta="<?php echo $producto['precio_venta']; ?>"
                                        data-subcategoria="<?php echo htmlspecialchars($producto['nombre_subcategoria']); ?>"
                                        data-proveedor="<?php echo htmlspecialchars($producto['nombre_proveedor']); ?>">
                                    <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <input type="text" name="detalles[${detalleIndex}][subcategoria]" class="form-control" placeholder="Subcategoría" readonly>
                    </div>
                    <div class="col-2">
                        <input type="text" name="detalles[${detalleIndex}][proveedor]" class="form-control" placeholder="Proveedor" readonly>
                    </div>
                    <div class="col-2">
                        <input type="number" name="detalles[${detalleIndex}][precio_compra]" class="form-control" placeholder="Precio Compra" readonly>
                    </div>
                    <div class="col-1">
                        <input type="number" name="detalles[${detalleIndex}][cantidad]" class="form-control" placeholder="Cantidad" required onchange="calcularSubtotal(this)">
                    </div>
                    <div class="col-2">
                        <input type="text" name="detalles[${detalleIndex}][subtotal]" class="form-control" placeholder="Subtotal" readonly>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', newDetalle);
            detalleIndex++;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
