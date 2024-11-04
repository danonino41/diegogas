<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/estilosmenu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <?php include_once 'productos_dashboard.php'; ?>


    <div class="container mt-4">
        <h1>Productos</h1>

        <?php if ($mensaje): ?>
        <div class="alert alert-info" id="alert" style="opacity: 1;
            transition: opacity 1s ease;">
            <?php echo $mensaje; ?>
        </div>
        <?php endif; ?>

        <form method="GET" action="productos_recepcion.php" class="mb-3">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar producto por nombre, categoría o subcategoría" value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i> Lista de Productos
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio Venta</th>
                                <th>Existencias</th>
                                <th>Subcategoría</th>
                                <th>Categoría</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron productos.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['precio_venta']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['existencias']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nombre_subcategoria']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nombre_categoria']); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarProducto"
                                                data-id="<?php echo $producto['id_producto']; ?>"
                                                data-nombre="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                data-precio_venta="<?php echo htmlspecialchars($producto['precio_venta']); ?>"
                                                data-existencias="<?php echo htmlspecialchars($producto['existencias']); ?>"
                                                data-id_subcategoria="<?php echo htmlspecialchars($producto['id_subcategoria']); ?>"
                                                data-id_proveedor="<?php echo htmlspecialchars($producto['id_proveedor']); ?>">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <a href="productos_recepcion.php?eliminar=<?php echo $producto['id_producto']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </a>
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

    <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="productos_recepcion.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="producto-id" name="id">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="producto-nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio_venta" class="form-label">Precio Venta</label>
                            <input type="number" class="form-control" id="producto-precio_venta" name="precio_venta" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="existencias" class="form-label">Existencias</label>
                            <input type="number" class="form-control" id="producto-existencias" name="existencias" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_subcategoria" class="form-label">Subcategoría</label>
                            <select class="form-select" id="producto-id_subcategoria" name="id_subcategoria" required>
                                <?php foreach ($subcategorias as $subcategoria): ?>
                                    <option value="<?php echo $subcategoria['id_subcategoria']; ?>">
                                        <?php echo htmlspecialchars($subcategoria['nombre_subcategoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_proveedor" class="form-label">Proveedor</label>
                            <select class="form-select" id="producto-id_proveedor" name="id_proveedor" required>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['id_proveedor']; ?>">
                                        <?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="editar_producto">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modalEditarProducto = document.getElementById("modalEditarProducto");
            const productoIdInput = document.getElementById("producto-id");
            const productoNombreInput = document.getElementById("producto-nombre");
            const productoPrecioVentaInput = document.getElementById("producto-precio_venta");
            const productoExistenciasInput = document.getElementById("producto-existencias");
            const productoIdSubcategoriaSelect = document.getElementById("producto-id_subcategoria");
            const productoIdProveedorSelect = document.getElementById("producto-id_proveedor");

            modalEditarProducto.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                productoIdInput.value = button.getAttribute("data-id");
                productoNombreInput.value = button.getAttribute("data-nombre");
                productoPrecioVentaInput.value = button.getAttribute("data-precio_venta");
                productoExistenciasInput.value = button.getAttribute("data-existencias");
                productoIdSubcategoriaSelect.value = button.getAttribute("data-id_subcategoria");
                productoIdProveedorSelect.value = button.getAttribute("data-id_proveedor");
            });
        });

        setTimeout(() => {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.opacity = '0';
            }
        },1000);
    </script>
</body>
</html>
