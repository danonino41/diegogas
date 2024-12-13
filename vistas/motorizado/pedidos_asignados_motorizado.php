<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Motorizado', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Función para obtener pedidos asignados según el estado
function obtenerPedidosAsignadosMotorizado($idEmpleado, $estado)
{
    $conexion = obtenerConexion();
    $sql = "SELECT 
                p.id_pedido, 
                c.nombre_cliente, 
                dc.direccion, 
                ep.nombre_estado AS estado, 
                p.total_pedido
            FROM pedidos p
            JOIN clientes c ON p.id_cliente = c.id_cliente
            LEFT JOIN direcciones_cliente dc ON p.id_direccion_envio = dc.id_direccion
            JOIN estado_pedido ep ON p.id_estado = ep.id_estado
            WHERE p.id_empleado = :idEmpleado AND ep.nombre_estado = :estado
            ORDER BY p.fecha_pedido ASC";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener el ID del empleado asociado al usuario
function obtenerIdEmpleadoPorUsuario($idUsuario)
{
    $conexion = obtenerConexion();
    $sql = "SELECT id_empleado FROM usuarios WHERE id_usuario = :idUsuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'], $_POST['nuevo_estado'])) {
    $idPedido = $_POST['id_pedido'];
    $nuevoEstado = $_POST['nuevo_estado'];
    $idEmpleado = obtenerIdEmpleadoPorUsuario($_SESSION['id_usuario']);

    if (actualizarEstadoPedido($idPedido, $nuevoEstado, $idEmpleado)) {
        header("Location: pedidos_asignados_motorizado.php?mensaje=Estado actualizado correctamente");
    } else {
        header("Location: pedidos_asignados_motorizado.php?error=No se pudo actualizar el estado del pedido");
    }
    exit();
}

// Obtener datos del motorizado
$idEmpleado = obtenerIdEmpleadoPorUsuario($_SESSION['id_usuario']);
$pedidosEnRuta = obtenerPedidosAsignadosMotorizado($idEmpleado, 'En Ruta');
$pedidosLlegados = obtenerPedidosAsignadosMotorizado($idEmpleado, 'Llegó a Domicilio');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Asignados - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_motorizado.php'; ?>

    <div class="container mt-4">
        <h1>Pedidos Asignados</h1>

        <!-- Pedidos en Ruta -->
        <h3>Pedidos En Ruta</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosEnRuta)): ?>
                        <?php foreach ($pedidosEnRuta as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>S/ <?php echo number_format($pedido['total_pedido'], 2); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="5"> <!-- Llegó a Domicilio -->
                                        <button type="submit" class="btn btn-info btn-sm">Llegó a Domicilio</button>
                                    </form>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="7"> <!-- Cancelado -->
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No tienes pedidos en ruta.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pedidos Llegados a Domicilio -->
        <h3>Pedidos Llegados a Domicilio</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosLlegados)): ?>
                        <?php foreach ($pedidosLlegados as $pedido): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>S/ <?php echo number_format($pedido['total_pedido'], 2); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="6"> <!-- Entregado -->
                                        <button type="submit" class="btn btn-success btn-sm">Entregar</button>
                                    </form>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <input type="hidden" name="nuevo_estado" value="7"> <!-- Cancelado -->
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No tienes pedidos en estado "Llegó a Domicilio".</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
