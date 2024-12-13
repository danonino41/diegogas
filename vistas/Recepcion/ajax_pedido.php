<?php
session_start();
require_once '../funciones.php';

// Inicializar o actualizar productos seleccionados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['productos_seleccionados'])) {
        $_SESSION['productos_seleccionados'] = [];
    }

    $productosSeleccionados = &$_SESSION['productos_seleccionados'];

    switch ($data['accion']) {
        case 'agregar':
            $id_producto = $data['id_producto'];
            $nombre = $data['nombre_producto'];
            $precio_venta = $data['precio_venta'];
            $cantidad = $data['cantidad'];

            if (isset($productosSeleccionados[$id_producto])) {
                $productosSeleccionados[$id_producto]['cantidad'] += $cantidad;
                $productosSeleccionados[$id_producto]['subtotal'] += $cantidad * $precio_venta;
            } else {
                $productosSeleccionados[$id_producto] = [
                    'id_producto' => $id_producto,
                    'nombre' => $nombre,
                    'precio_venta' => $precio_venta,
                    'cantidad' => $cantidad,
                    'subtotal' => $cantidad * $precio_venta,
                ];
            }
            break;

        case 'actualizar_precio':
            $id_producto = $data['id_producto'];
            $nuevo_precio = $data['nuevo_precio'];

            if (isset($productosSeleccionados[$id_producto])) {
                $productosSeleccionados[$id_producto]['precio_venta'] = $nuevo_precio;
                $productosSeleccionados[$id_producto]['subtotal'] = $nuevo_precio * $productosSeleccionados[$id_producto]['cantidad'];
            }
            break;

        case 'aumentar':
            $id_producto = $data['id_producto'];

            if (isset($productosSeleccionados[$id_producto])) {
                $productosSeleccionados[$id_producto]['cantidad'] += 1;
                $productosSeleccionados[$id_producto]['subtotal'] = $productosSeleccionados[$id_producto]['precio_venta'] * $productosSeleccionados[$id_producto]['cantidad'];
            }
            break;

        case 'reducir':
            $id_producto = $data['id_producto'];

            if (isset($productosSeleccionados[$id_producto]) && $productosSeleccionados[$id_producto]['cantidad'] > 1) {
                $productosSeleccionados[$id_producto]['cantidad'] -= 1;
                $productosSeleccionados[$id_producto]['subtotal'] = $productosSeleccionados[$id_producto]['precio_venta'] * $productosSeleccionados[$id_producto]['cantidad'];
            }
            break;

        case 'quitar':
            $id_producto = $data['id_producto'];

            unset($productosSeleccionados[$id_producto]);
            break;
    }

    $_SESSION['productos_seleccionados'] = $productosSeleccionados;
    echo json_encode(['status' => 'success']);
    exit;
}

// Respuesta para GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productosSeleccionados = $_SESSION['productos_seleccionados'] ?? [];
    if (empty($productosSeleccionados)) {
        echo '<p class="text-center">No hay productos seleccionados.</p>';
    } else {
        echo '<table class="table table-bordered">';
        echo '<thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Venta</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
              </thead>';
        echo '<tbody>';
        $total = 0;
        foreach ($productosSeleccionados as $id => $producto) {
            $total += $producto['subtotal'];
            echo "<tr>
                    <td>{$producto['nombre']}</td>
                    <td>
                        <input type='number' class='form-control auto-update-precio' value='{$producto['precio_venta']}' data-id='{$id}' step='0.01' style='width: 100px;' />
                    </td>
                    <td>
                        <button class='btn btn-sm btn-secondary btn-reducir' data-id='{$id}'>-</button>
                        {$producto['cantidad']}
                        <button class='btn btn-sm btn-secondary btn-aumentar' data-id='{$id}'>+</button>
                    </td>
                    <td>S/ " . number_format($producto['subtotal'], 2) . "</td>
                    <td><button class='btn btn-sm btn-danger btn-quitar' data-id='{$id}'>Eliminar</button></td>
                  </tr>";
        }
        echo '</tbody>';
        echo '</table>';
        echo "<h5 class='mt-3'>Total: S/ " . number_format($total, 2) . "</h5>";
    }
    exit;
}
?>
