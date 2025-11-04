<?php
include __DIR__ . '/conn.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $conn->prepare("SELECT id, nombre, telefono, fecha, hora, servicio, notas, status FROM citas ORDER BY fecha ASC, hora ASC");
    $stmt->execute();
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($citas, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB error']);
}
$conn = null;
?>