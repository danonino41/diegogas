<?php
session_start();
require_once '../funciones.php';

if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Obtener datos de los pedidos por estado
$pedidosPendientes = obtenerPedidosPorEstado('Pendiente') ?? [];
$pedidosConfirmados = obtenerPedidosPorEstado('Confirmado') ?? [];
$pedidosPreparados = obtenerPedidosPorEstado('Preparado') ?? [];
$pedidosEnRuta = obtenerPedidosPorEstado('En Ruta') ?? [];
$pedidosEntregados = obtenerPedidosPorEstado('Entregado') ?? [];
$pedidosCancelados = obtenerPedidosPorEstado('Cancelado') ?? [];


// Datos adicionales
$usuario = obtenerDatosUsuario($_SESSION['id_usuario']);

function obtenerPedidosDelDia()
{
    $conexion = obtenerConexion();
    if (!$conexion) {
        throw new Exception("No se pudo establecer la conexión a la base de datos.");
    }

    try {
        $fechaHoy = date('Y-m-d');
        $sql = "SELECT 
                    p.id_pedido, 
                    c.nombre_cliente, 
                    ep.nombre_estado AS estado, 
                    p.total_pedido AS total, 
                    p.fecha_pedido AS fecha
                FROM pedidos p
                JOIN clientes c ON p.id_cliente = c.id_cliente
                JOIN estado_pedido ep ON p.id_estado = ep.id_estado
                WHERE DATE(p.fecha_pedido) = :fechaHoy
                ORDER BY p.fecha_pedido ASC";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':fechaHoy', $fechaHoy, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener pedidos del día: " . $e->getMessage();
        return [];
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Recepcionista - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Bienvenido <?php echo htmlspecialchars($usuario['nombre_empleado'] ?? 'Usuario no encontrado') . "!!"; ?></h1>
        <h4>Fecha: <span id="fecha_actual"></span></h4>
        <h4>Hora: <span id="hora_actual"></span></h4>

        <?php include_once 'cartilla_navbar_recepcion.php'; ?>

        <h3>Historial de Pedidos del Día</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pedidosDelDia = obtenerPedidosDelDia();
                    if (!empty($pedidosDelDia)) {
                        foreach ($pedidosDelDia as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                                <td>
                                    <a href="detalles_pedido.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn btn-primary btn-sm">
                                        Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                    } else { ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron pedidos para el día de hoy.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <script>
            function fechaActual() {
                const ahora = new Date();
                const dia = ahora.getDate().toString().padStart(2, '0');
                const mes = (ahora.getMonth() + 1).toString().padStart(2, '0');
                const ano = ahora.getFullYear().toString();
                document.getElementById('fecha_actual').textContent = `${dia}-${mes}-${ano}`;
            }
            fechaActual();

            function actualizarHora() {
                const ahora = new Date();
                const hora = ahora.getHours().toString().padStart(2, '0');
                const minutos = ahora.getMinutes().toString().padStart(2, '0');
                const segundos = ahora.getSeconds().toString().padStart(2, '0');
                document.getElementById('hora_actual').textContent = `${hora}:${minutos}:${segundos}`;
            }
            actualizarHora();
            setInterval(actualizarHora, 1000);
        </script>
</body>

</html>