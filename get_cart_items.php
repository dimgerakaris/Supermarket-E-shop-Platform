<?php
session_start();
include 'cartDB_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Πρέπει να συνδεθείτε για να δείτε το καλάθι.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT c.id, 
        p.name AS product_name, 
        p.image AS product_image, 
        c.quantity, 
        c.price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode(['status' => 'success', 'items' => $items]);

$conn->close();
?>