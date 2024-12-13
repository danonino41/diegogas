<?php
session_start();
require_once '../funciones.php';

// Verificar sesión y rol del usuario
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Inicializar variables
$mensaje = '';
$clientes = [];
$productos = [];
$clienteSeleccionado = $_SESSION['cliente_seleccionado'] ?? null;
$productosSeleccionados = $_SESSION['productos_seleccionados'] ?? [];
$tipoPagoSeleccionado = $_SESSION['tipoPagoSeleccionado'] ?? '';
$tipoDespachoSeleccionado = $_SESSION['tipoDespachoSeleccionado'] ?? '';
$direccionEnvioSeleccionada = $_POST['direccion_envio'] ?? null;

// Funciones del cliente y pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirmar pedido
    if (isset($_POST['confirmar_pedido'])) {
        $tipoPagoSeleccionado = $_POST['tipoPago'];
        $tipoDespachoSeleccionado = $_POST['tipoDespacho'];
        $direccionEnvioSeleccionada = $_POST['direccion_envio'];

        if (!$clienteSeleccionado || empty($productosSeleccionados)) {
            $mensaje = "Error: Debe seleccionar un cliente y al menos un producto para confirmar el pedido.";
        } elseif (empty($tipoPagoSeleccionado) || empty($tipoDespachoSeleccionado) || empty($direccionEnvioSeleccionada)) {
            $mensaje = "Error: Debe seleccionar un método de pago, tipo de despacho y dirección de envío.";
        } else {
            $id_cliente = $clienteSeleccionado['id_cliente'];
            $id_usuario = $_SESSION['id_usuario'];
            $total_pedido = array_sum(array_column($productosSeleccionados, 'subtotal'));

            // Registrar pedido en la base de datos
            $id_pedido = crearPedido($id_cliente, $id_usuario, $total_pedido, $tipoPagoSeleccionado, $tipoDespachoSeleccionado, $direccionEnvioSeleccionada);

            if ($id_pedido) {
                foreach ($productosSeleccionados as $producto) {
                    agregarDetallePedido($id_pedido, $producto['id_producto'], $producto['cantidad'], $producto['precio_venta']);
                    descontarInventario($producto['id_producto'], $producto['cantidad']);
                }

                // Limpiar selección
                unset($_SESSION['productos_seleccionados'], $_SESSION['cliente_seleccionado'], $_SESSION['tipoPagoSeleccionado'], $_SESSION['tipoDespachoSeleccionado']);
                $productosSeleccionados = [];
                $clienteSeleccionado = null;
                $tipoPagoSeleccionado = '';
                $tipoDespachoSeleccionado = '';
                $direccionEnvioSeleccionada = null;
                $mensaje = "Pedido creado exitosamente. ID del pedido: $id_pedido.";
            } else {
                $mensaje = "Error: No se pudo crear el pedido. Intente nuevamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>
    <div class="container mt-4">
        <h1>Realizar Pedido</h1>

        <hr>

        <!-- Mostrar mensajes -->
        <?php if ($mensaje): ?>
            <div class="alert alert-warning">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Selección de cliente -->
        <?php include 'seleccion_cliente.php'; ?>

        <hr>

        <!-- Selección de productos -->
        <?php include 'seleccion_producto.php'; ?>

        <hr>

        <!-- Opciones de Pago, Despacho y Dirección -->
        <h2>Opciones de Pago, Despacho y Dirección</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="tipoPago" class="form-label">Método de Pago</label>
                <select id="tipoPago" name="tipoPago" class="form-select" required>
                    <option value="">Seleccione Método de Pago</option>
                    <?php foreach (obtenerMetodosPago() as $metodoPago): ?>
                        <option value="<?php echo $metodoPago['id_pago']; ?>" <?php echo $tipoPagoSeleccionado == $metodoPago['id_pago'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($metodoPago['descripcion_pago']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipoDespacho" class="form-label">Tipo de Despacho</label>
                <select id="tipoDespacho" name="tipoDespacho" class="form-select" required>
                    <option value="">Seleccione Tipo de Despacho</option>
                    <?php foreach (obtenerTiposDespacho() as $tipoDespacho): ?>
                        <option value="<?php echo $tipoDespacho['id_tipo_despacho']; ?>" <?php echo $tipoDespachoSeleccionado == $tipoDespacho['id_tipo_despacho'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipoDespacho['descripcion_despacho']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="direccion_envio" class="form-label">Dirección de Envío</label>
                <select id="direccion_envio" name="direccion_envio" class="form-select" required>
                    <option value="">Seleccione Dirección de Envío</option>
                    <?php foreach ($clienteSeleccionado['direcciones'] as $direccion): ?>
                        <option value="<?php echo htmlspecialchars($direccion['id_direccion']); ?>" <?php echo $direccionEnvioSeleccionada == $direccion['id_direccion'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($direccion['direccion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="confirmar_pedido" class="btn btn-success">Confirmar Pedido</button>
        </form>
    </div>
    <br>
</body>

</html>