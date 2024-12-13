<?php
session_start();
require_once '../funciones.php';

// Verificar si el usuario tiene acceso como Motorizado
if (!isset($_SESSION['id_usuario']) || !in_array('Motorizado', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Obtener datos del motorizado
$idEmpleado = $_SESSION['id_usuario'];
$usuario = obtenerDatosUsuario($_SESSION['id_usuario']);

function obtenerPedidosAsignadosMotorizado($idEmpleado)
{
    $conexion = obtenerConexion();
    $sql = "
        SELECT p.id_pedido, 
               c.nombre_cliente, 
               p.total_pedido, 
               p.fecha_pedido, 
               ep.nombre_estado
        FROM pedidos p
        JOIN clientes c ON p.id_cliente = c.id_cliente
        JOIN estado_pedido ep ON p.id_estado = ep.id_estado
        WHERE p.id_empleado = :idEmpleado 
          AND ep.nombre_estado IN ('En Ruta', 'Llegó a Domicilio')
        ORDER BY p.fecha_pedido ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Datos para el dashboard
$pedidosPendientes = obtenerPedidosAsignadosMotorizado($idEmpleado, 'En Ruta');
$pedidosEntregados = obtenerPedidosAsignadosMotorizado($idEmpleado, 'Entregado');
$totalEntregados = count($pedidosEntregados);
$totalPendientes = count($pedidosPendientes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Motorizado - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once 'navbar_motorizado.php'; ?>

    <main class="container mt-4">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario['nombre_empleado'] ?? 'Motorizado'); ?>!</h1>
        <h4>Fecha: <span id="fecha_actual"></span></h4>
        <h4>Hora: <span id="hora_actual"></span></h4>

        <div class="row mt-4">
            <!-- Pedidos Pendientes -->
            <div class="col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-header">Pedidos Pendientes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $totalPendientes; ?> Pedidos</h5>
                        <p>Pedidos asignados en ruta para ser entregados.</p>
                        <a href="pedidos_asignados_motorizado.php" class="btn btn-light">Ver Pedidos</a>
                    </div>
                </div>
            </div>

            <!-- Pedidos Entregados -->
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-header">Pedidos Entregados</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $totalEntregados; ?> Pedidos</h5>
                        <p>Pedidos entregados con éxito.</p>
                        <a href="historial_pedidos_motorizado.php" class="btn btn-light">Ver Historial</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Mostrar fecha actual
        function mostrarFechaActual() {
            const ahora = new Date();
            const dia = ahora.getDate().toString().padStart(2, '0');
            const mes = (ahora.getMonth() + 1).toString().padStart(2, '0');
            const ano = ahora.getFullYear();
            document.getElementById('fecha_actual').textContent = `${dia}-${mes}-${ano}`;
        }
        mostrarFechaActual();

        // Mostrar hora actual
        function mostrarHoraActual() {
            const ahora = new Date();
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            document.getElementById('hora_actual').textContent = `${horas}:${minutos}:${segundos}`;
        }
        mostrarHoraActual();
        setInterval(mostrarHoraActual, 1000);
    </script>
</body>
</html>
