<?php
// Gestión de productos seleccionados
$productosSeleccionados = $_SESSION['productos_seleccionados'] ?? [];
$busqueda = $_GET['busqueda'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$subcategoria = $_GET['subcategoria'] ?? '';
$ordenar_por_precio = $_GET['ordenar_por_precio'] ?? '';

// Obtener productos con filtros
$productos = obtenerProductos($busqueda, $categoria, $subcategoria, $ordenar_por_precio);

// Manejo de acciones sobre productos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar_producto'])) {
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
                'subtotal' => $subtotal,
            ];
        }

        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }

    if (isset($_POST['actualizar_precio'])) {
        $id_producto = $_POST['id_producto'];
        $nuevo_precio_venta = $_POST['nuevo_precio_venta'];

        if (isset($productosSeleccionados[$id_producto])) {
            $productosSeleccionados[$id_producto]['precio_venta'] = $nuevo_precio_venta;
            $productosSeleccionados[$id_producto]['subtotal'] = $nuevo_precio_venta * $productosSeleccionados[$id_producto]['cantidad'];
        }

        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }

    if (isset($_POST['aumentar_cantidad'])) {
        $id_producto = $_POST['id_producto'];

        if (isset($productosSeleccionados[$id_producto])) {
            $productosSeleccionados[$id_producto]['cantidad'] += 1;
            $productosSeleccionados[$id_producto]['subtotal'] = $productosSeleccionados[$id_producto]['precio_venta'] * $productosSeleccionados[$id_producto]['cantidad'];
        }

        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }

    if (isset($_POST['reducir_cantidad'])) {
        $id_producto = $_POST['id_producto'];

        if (isset($productosSeleccionados[$id_producto]) && $productosSeleccionados[$id_producto]['cantidad'] > 1) {
            $productosSeleccionados[$id_producto]['cantidad'] -= 1;
            $productosSeleccionados[$id_producto]['subtotal'] = $productosSeleccionados[$id_producto]['precio_venta'] * $productosSeleccionados[$id_producto]['cantidad'];
        }

        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }

    if (isset($_POST['quitar_producto'])) {
        $id_producto = $_POST['id_producto'];
        unset($productosSeleccionados[$id_producto]);

        $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    }
}
?>

<!-- Selección de productos -->
<div>
    <h2>Seleccionar Productos</h2>

    <!-- Filtro de búsqueda -->
    <form method="GET" action="#productos" class="mb-3">
        <div class="input-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar producto por nombre, categoría o subcategoría" value="<?php echo htmlspecialchars($busqueda); ?>">
            <select class="form-select" name="categoria">
                <option value="">Seleccione Categoría</option>
                <?php foreach (obtenerCategorias() as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['nombre_categoria']); ?>" <?php echo $categoria == $cat['nombre_categoria'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select class="form-select" name="subcategoria">
                <option value="">Seleccione Subcategoría</option>
                <?php foreach (obtenerSubcategorias() as $subcat): ?>
                    <option value="<?php echo htmlspecialchars($subcat['nombre_subcategoria']); ?>" <?php echo $subcategoria == $subcat['nombre_subcategoria'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($subcat['nombre_subcategoria']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select class="form-select" name="ordenar_por_precio">
                <option value="">Ordenar por Precio</option>
                <option value="ASC" <?php echo $ordenar_por_precio == 'ASC' ? 'selected' : ''; ?>>Ascendente</option>
                <option value="DESC" <?php echo $ordenar_por_precio == 'DESC' ? 'selected' : ''; ?>>Descendente</option>
            </select>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <!-- Alerta si no hay productos -->
    <?php if (empty($productos)): ?>
        <div class="alert alert-warning">
            No se encontraron productos con el criterio de búsqueda.
        </div>
    <?php endif; ?>

    <!-- Mostrar productos disponibles -->
    <div class="card mb-4">
        <div class="card-header">Productos Encontrados</div>
        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            <?php if ($productos): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Existencias</th>
                            <th>Subcategoría</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($producto['precio_venta']); ?></td>
                                <td><?php echo htmlspecialchars($producto['existencias']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre_subcategoria']); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                                <td>
                                    <form method="POST" action="#productos">
                                        <input type="number" name="cantidad" value="1" min="1" class="form-control" style="width: 80px;">
                                        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                                        <input type="hidden" name="nombre_producto" value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                        <input type="hidden" name="precio_venta" value="<?php echo htmlspecialchars($producto['precio_venta']); ?>">
                                </td>
                                <td>
                                    <button type="submit" name="agregar_producto" class="btn btn-success btn-sm">Agregar</button>
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
    <div class="card">
        <div class="card-header">Productos Seleccionados</div>
        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            <?php if (!empty($productosSeleccionados)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Venta (Editable)</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($productosSeleccionados as $id => $producto): ?>
                            <?php $total += $producto['subtotal']; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>
                                    <form method="POST" action="#productos">
                                        <input type="number" name="nuevo_precio_venta"
                                            value="<?php echo htmlspecialchars($producto['precio_venta']); ?>"
                                            step="0.01" class="form-control"
                                            style="width: 100px;">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2" name="actualizar_precio">Actualizar</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline" action="#productos">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" name="reducir_cantidad">-</button>
                                        <?php echo htmlspecialchars($producto['cantidad']); ?>
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" name="aumentar_cantidad">+</button>
                                    </form>
                                </td>
                                <td><?php echo number_format($producto['subtotal'], 2); ?></td>
                                <td>
                                    <form method="POST" action="#productos">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button class="btn btn-danger btn-sm" type="submit" name="quitar_producto">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h5 class="mt-3">Total: S/ <?php echo number_format($total, 2); ?></h5>
            <?php else: ?>
                <p class="text-center">No hay productos seleccionados.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
