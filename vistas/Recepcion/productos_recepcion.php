<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=debes_iniciar_sesion");
    exit();
}

$mensaje = '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$subcategoria = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : '';
$ordenar_por_precio = isset($_GET['ordenar_por_precio']) ? $_GET['ordenar_por_precio'] : '';

// Obtener productos con los filtros
$productos = obtenerProductos($busqueda, $categoria, $subcategoria, $ordenar_por_precio);
$subcategorias = obtenerSubcategorias();
$categorias = obtenerCategorias();  // Obtener las categorías desde la base de datos
$proveedores = obtenerProveedores();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_producto'])) {
    $id = $_POST['id'];
    $precio_venta = $_POST['precio_venta'];
    if ($_SESSION['roles'][0] == 'Recepcionista') {
        if (actualizarProducto($id, $precio_venta)) {
            $mensaje = "Precio actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el precio.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>
    <?php include_once 'productos_dashboard.php'; ?>

    <div class="container mt-4">
        <h1>Productos</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info" id="alert" style="opacity: 1; transition: opacity 1s ease;">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Filtro de búsqueda -->
        <form method="GET" action="productos_recepcion.php" class="mb-3">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar producto por nombre, categoría o subcategoría" value="<?php echo htmlspecialchars($busqueda); ?>">

                <!-- Filtro de Categorías -->
                <select class="form-select" name="categoria" id="categoria">
                    <option value="">Seleccione Categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['nombre_categoria']; ?>" <?php echo $categoria == $cat['nombre_categoria'] ? 'selected' : ''; ?>>
                            <?php echo $cat['nombre_categoria']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtro de Subcategorías -->
                <select class="form-select" name="subcategoria" id="subcategoria">
                    <option value="">Seleccione Subcategoría</option>
                    <?php
                    if ($categoria == 'Balones de Gas') {
                        // Si la categoría seleccionada es "Balones de Gas"
                        $subcats = ['Balón de Gas 5 kg', 'Balón de Gas 10 kg', 'Balón de Gas 15 kg', 'Balón de Gas 45 kg'];
                    } elseif ($categoria == 'Accesorios') {
                        // Si la categoría es "Accesorios"
                        $subcats = ['Mangueras', 'Válvulas', 'Abrazaderas'];
                    } else {
                        $subcats = [];
                    }

                    foreach ($subcats as $subcat): ?>
                        <option value="<?php echo $subcat; ?>" <?php echo $subcategoria == $subcat ? 'selected' : ''; ?>>
                            <?php echo $subcat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Ordenar por Precio -->
                <select class="form-select" name="ordenar_por_precio">
                    <option value="">Ordenar por Precio</option>
                    <option value="ASC" <?php echo $ordenar_por_precio == 'ASC' ? 'selected' : ''; ?>>Ascendente</option>
                    <option value="DESC" <?php echo $ordenar_por_precio == 'DESC' ? 'selected' : ''; ?>>Descendente</option>
                </select>

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
                            <label for="precio_venta" class="form-label">Precio Venta</label>
                            <input type="number" class="form-control" id="producto-precio_venta" name="precio_venta" step="0.01" required>
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
        document.addEventListener("DOMContentLoaded", function() {
            // Detectar cambios en los filtros
            const categoriaSelect = document.getElementById("categoria");
            const subcategoriaSelect = document.getElementById("subcategoria");
            const inputBusqueda = document.getElementById("inputBusqueda");
            const ordenarSelect = document.getElementById("ordenar_por_precio");

            // Función para hacer submit en el formulario cuando se cambia un filtro
            function actualizarProductos() {
                const form = document.getElementById("filtroForm");
                form.submit(); // Enviar el formulario con los valores actuales
            }

            // Detectar cambio en la categoría
            categoriaSelect.addEventListener("change", function() {
                // Cuando cambia la categoría, actualizar las subcategorías dinámicamente
                const categoria = categoriaSelect.value;

                // Actualizar subcategorías según la categoría seleccionada
                let subcategoriasHTML = '<option value="">Seleccione Subcategoría</option>';
                if (categoria === "Balones de Gas") {
                    subcategoriasHTML += `
                <option value="Balón de Gas 5 kg">Balón de Gas 5 kg</option>
                <option value="Balón de Gas 10 kg">Balón de Gas 10 kg</option>
                <option value="Balón de Gas 15 kg">Balón de Gas 15 kg</option>
                <option value="Balón de Gas 45 kg">Balón de Gas 45 kg</option>
            `;
                } else if (categoria === "Accesorios") {
                    subcategoriasHTML += `
                <option value="Mangueras">Mangueras</option>
                <option value="Válvulas">Válvulas</option>
                <option value="Abrazaderas">Abrazaderas</option>
            `;
                }

                subcategoriaSelect.innerHTML = subcategoriasHTML;
                actualizarProductos(); // Actualizar productos
            });

            // Detectar cambio en subcategoría
            subcategoriaSelect.addEventListener("change", actualizarProductos);

            // Detectar cambio en búsqueda
            inputBusqueda.addEventListener("input", actualizarProductos);

            // Detectar cambio en orden de precio
            ordenarSelect.addEventListener("change", actualizarProductos);
        });

        document.addEventListener("DOMContentLoaded", () => {
            const modalEditarProducto = document.getElementById("modalEditarProducto");
            const productoIdInput = document.getElementById("producto-id");
            const productoPrecioVentaInput = document.getElementById("producto-precio_venta");

            modalEditarProducto.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                productoIdInput.value = button.getAttribute("data-id");
                productoPrecioVentaInput.value = button.getAttribute("data-precio_venta");
            });
        });

        setTimeout(() => {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.opacity = '0';
            }
        }, 1000);
    </script>
</body>

</html>