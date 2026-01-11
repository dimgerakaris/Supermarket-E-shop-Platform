<?php
include 'cartDB_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id']) || !isset($data['stock'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit();
}

$id = (int) $data['id'];
$stock = (int) $data['stock'];

$sql = "UPDATE products SET stock = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $stock, $id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
$conn->close();
?>
