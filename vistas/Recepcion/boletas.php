<?php
session_start();
require_once '../funciones.php';
require_once '../../fpdf/fpdf.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || !in_array('Recepcionista', $_SESSION['roles'])) {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// Variables para mensajes
$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';
$criterio = $_GET['criterio'] ?? '';
$buscar = $_GET['buscar'] ?? '';

// Obtener las boletas según el criterio de búsqueda
$boletas = buscarBoletas($criterio, $buscar);

// Función para buscar boletas
function buscarBoletas($criterio, $buscar)
{
    $conn = obtenerConexion();
    try {
        $sql = "SELECT b.*, c.nombre_cliente, e.nombre_empleado
                FROM boletas b
                JOIN clientes c ON b.id_cliente = c.id_cliente
                JOIN empleados e ON b.id_empleado = e.id_empleado";
        
        if (!empty($criterio) && !empty($buscar)) {
            switch ($criterio) {
                case 'numero_boleta':
                    $sql .= " WHERE b.numero_boleta LIKE :buscar";
                    break;
                case 'nombre_cliente':
                    $sql .= " WHERE c.nombre_cliente LIKE :buscar";
                    break;
                case 'fecha_boleta':
                    $sql .= " WHERE DATE(b.fecha_boleta) = :buscar";
                    break;
                default:
                    throw new Exception("Criterio de búsqueda no válido.");
            }
        }

        $sql .= " ORDER BY b.fecha_boleta DESC";
        $stmt = $conn->prepare($sql);

        if (!empty($criterio) && !empty($buscar)) {
            if ($criterio === 'fecha_boleta') {
                $stmt->bindValue(':buscar', $buscar);
            } else {
                $stmt->bindValue(':buscar', '%' . $buscar . '%');
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al buscar boletas: " . $e->getMessage();
        return [];
    }
}

// Generar PDF de boleta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_boleta']) && isset($_POST['generar_pdf'])) {
    $idBoleta = $_POST['id_boleta'];
    generarPDFBoleta($idBoleta);
    exit();
}

// Función para generar el PDF de una boleta
function generarPDFBoleta($idBoleta)
{
    $conn = obtenerConexion();
    try {
        $sql = "SELECT b.*, c.nombre_cliente, c.apellido_cliente, e.nombre_empleado, e.apellido_empleado
                FROM boletas b
                JOIN clientes c ON b.id_cliente = c.id_cliente
                JOIN empleados e ON b.id_empleado = e.id_empleado
                WHERE b.id_boleta = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idBoleta]);
        $boleta = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$boleta) {
            throw new Exception("Boleta no encontrada.");
        }

        $detallesPedido = obtenerDetallesPedido($boleta['id_pedido']);

        $pdf = new FPDF();
        $pdf->AddPage();

        // Cabecera
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 10, 'Boleta de Pago', 0, 1, 'C', true);
        $pdf->Ln(10);

        // Información general de la boleta
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Numero de Boleta:', 0, 0);
        $pdf->Cell(60, 10, $boleta['numero_boleta'], 0, 1);
        $pdf->Cell(40, 10, 'Fecha:', 0, 0);
        $pdf->Cell(60, 10, $boleta['fecha_boleta'], 0, 1);
        $pdf->Cell(40, 10, 'Cliente:', 0, 0);
        $pdf->Cell(60, 10, $boleta['nombre_cliente'] . ' ' . $boleta['apellido_cliente'], 0, 1);
        $pdf->Cell(40, 10, 'Empleado:', 0, 0);
        $pdf->Cell(60, 10, $boleta['nombre_empleado'] . ' ' . $boleta['apellido_empleado'], 0, 1);
        $pdf->Ln(10);

        // Detalles del pedido
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, 'Producto', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Precio', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        foreach ($detallesPedido as $detalle) {
            $pdf->Cell(100, 10, $detalle['nombre_producto'], 1);
            $pdf->Cell(30, 10, $detalle['cantidad'], 1, 0, 'C');
            $pdf->Cell(30, 10, 'S/ ' . number_format($detalle['precio_unitario'], 2), 1, 0, 'C');
            $pdf->Cell(30, 10, 'S/ ' . number_format($detalle['subtotal'], 2), 1, 1, 'C');
        }

        // Total
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(200, 255, 200);
        $pdf->Cell(130, 10, 'Total de la Boleta:', 1, 0, 'R', true);
        $pdf->Cell(60, 10, 'S/ ' . number_format($boleta['total_boleta'], 2), 1, 1, 'C', true);

        // Pie de página
        $pdf->SetY(-30);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Gracias por su compra en Diego Gas.', 0, 1, 'C');

        $pdf->Output('I', 'Boleta_' . $boleta['numero_boleta'] . '.pdf');
    } catch (Exception $e) {
        echo "Error al generar la boleta: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Boletas - Diego Gas</title>
    <link rel="stylesheet" href="../../recursos/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'navbar_recepcion.php'; ?>

    <div class="container mt-4">
        <h1>Gestión de Boletas</h1>

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="criterio" class="form-select">
                        <option value="numero_boleta" <?php echo $criterio === 'numero_boleta' ? 'selected' : ''; ?>>Número de Boleta</option>
                        <option value="nombre_cliente" <?php echo $criterio === 'nombre_cliente' ? 'selected' : ''; ?>>Nombre de Cliente</option>
                        <option value="fecha_boleta" <?php echo $criterio === 'fecha_boleta' ? 'selected' : ''; ?>>Fecha de Emisión</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($buscar); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header bg-primary text-white">Lista de Boletas</div>
            <div class="card-body">
                <?php if (!empty($boletas)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Boleta</th>
                                <th>Numero</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Empleado</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($boletas as $boleta): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($boleta['id_boleta']); ?></td>
                                    <td><?php echo htmlspecialchars($boleta['numero_boleta']); ?></td>
                                    <td><?php echo htmlspecialchars($boleta['fecha_boleta']); ?></td>
                                    <td><?php echo htmlspecialchars($boleta['nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($boleta['nombre_empleado']); ?></td>
                                    <td>S/ <?php echo number_format($boleta['total_boleta'], 2); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id_boleta" value="<?php echo $boleta['id_boleta']; ?>">
                                            <button type="submit" name="generar_pdf" class="btn btn-primary btn-sm">Generar PDF</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se encontraron boletas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
