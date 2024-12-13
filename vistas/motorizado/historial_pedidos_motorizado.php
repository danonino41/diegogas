<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso como Motorizado
if (!isset($_SESSION['id_usuario']) || !in_array('Motorizado', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Función para obtener historial de pedidos asignados al motorizado
function obtenerHistorialPedidosMotorizado($idEmpleado)
{
    $conexion = obtenerConexion();
    $sql = "SELECT 
                p.id_pedido, 
                c.nombre_cliente, 
                dc.direccion, 
                ep.nombre_estado AS estado, 
                p.total_pedido, 
                p.fecha_pedido
            FROM pedidos p
            JOIN clientes c ON p.id_cliente = c.id_cliente
            LEFT JOIN direcciones_cliente dc ON p.id_direccion_envio = dc.id_direccion
            JOIN estado_pedido ep ON p.id_estado = ep.id_estado
            WHERE p.id_empleado = :idEmpleado AND (ep.nombre_estado = 'Entregado' OR ep.nombre_estado = 'Cancelado')
            ORDER BY p.fecha_pedido DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener el ID del empleado asociado al usuario
function obtenerIdEmpleadoPorUsuario($idUsuario)
{
    $conexion = obtenerConexion();
    $sql = "SELECT id_empleado FROM usuarios WHERE id_usuario = :idUsuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Obtener datos del motorizado y su historial de pedidos
$idEmpleado = obtenerIdEmpleadoPorUsuario($_SESSION['id_usuario']);
$historialPedidos = obtenerHistorialPedidosMotorizado($idEmpleado);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos - Motorizado</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_motorizado.php'; ?>

    <div class="container mt-4">
        <h1>Historial de Pedidos</h1>
        <p>Aquí puedes ver el historial de los pedidos entregados o cancelados.</p>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($historialPedidos)): ?>
                        <?php foreach ($historialPedidos as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>S/ <?php echo number_format($pedido['total_pedido'], 2); ?></td>
                                <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                                <td>
                                    <a href="detalles_pedido.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn btn-primary btn-sm">
                                        Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron pedidos en tu historial.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../../recursos/js/cliente.js"></script>
</body>

</html>
