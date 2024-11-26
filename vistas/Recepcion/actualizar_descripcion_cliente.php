<?php
require_once '../funciones.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$id_cliente = $data['id_cliente'] ?? null;
$descripcion_cliente = $data['descripcion_cliente'] ?? null;

if (!$id_cliente || !$descripcion_cliente) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$conn = obtenerConexion();
$sql = "UPDATE clientes SET descripcion_cliente = :descripcion WHERE id_cliente = :id";
$stmt = $conn->prepare($sql);

if ($stmt->execute([':descripcion' => $descripcion_cliente, ':id' => $id_cliente])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la descripción.']);
}
