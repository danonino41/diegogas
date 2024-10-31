<?php
include_once "vistas/encabezado.php";
include_once "incluidosnavbar.php";
include_once "incluidos/funciones.php";
session_start();

// Verifica si el usuario está logueado
if (empty($_SESSION['usuario'])) {
    header("location: login.php");
    exit;
}

// Verificar si se está buscando un producto específico
$nombreProducto = isset($_POST['nombreProducto']) ? $_POST['nombreProducto'] : null;

// Obtener los productos según el nombre buscado
$productos = obtenerProductos($nombreProducto);

// Datos para las cartas de resumen
$cartas = [
    ["titulo" => "No. Productos", "icono" => "fa fa-box", "total" => count($productos), "color" => "#3578FE"],
    ["titulo" => "Total productos", "icono" => "fa fa-shopping-cart", "total" => obtenerNumeroProductos(), "color" => "#4F7DAF"],
    ["titulo" => "Total inventario", "icono" => "fa fa-money-bill", "total" => "$" . obtenerTotalInventario(), "color" => "#1FB824"],
];
?>
<div class="container mt-3">
    <h1>
        <a class="btn btn-success btn-lg" href="agregar_producto.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Productos
    </h1>
    
    <?php include_once "cartas_totales.php"; ?>

    <form action="" method="post" class="input-group mb-3 mt-3">
        <input autofocus name="nombreProducto" type="text" class="form-control" placeholder="Escribe el nombre o código del producto que deseas buscar" aria-label="Nombre producto" aria-describedby="button-addon2">
        <button type="submit" name="buscarProducto" class="btn btn-primary" id="button-addon2">
            <i class="fa fa-search"></i>
            Buscar
        </button>
    </form>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>Ganancia</th>
                <th>Existencia</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($productos as $producto) { ?>
                <tr>
                    <td><?= $producto['id_producto']; ?></td>
                    <td><?= $producto['marca_producto']; ?></td>
                    <td><?= '$' . $producto['precio_compra_producto']; ?></td>
                    <td><?= '$' . $producto['precio_venta_producto']; ?></td>
                    <td><?= '$' . ($producto['precio_venta_producto'] - $producto['precio_compra_producto']); ?></td>
                    <td><?= $producto['existencias_producto']; ?></td>
                    <td>
                        <a class="btn btn-info" href="editar_producto.php?id=<?= $producto['id_producto']; ?>">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="eliminar_producto.php?id=<?= $producto['id_producto']; ?>">
                            <i class="fa fa-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
