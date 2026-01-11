<?php
session_start();
include 'cartDB_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['total_items' => 0]);
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['total_items' => $row['total_items'] ?? 0]);
$conn->close();
?>
