<?php
session_start();
include 'cartDB_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Δεν έχετε συνδεθεί.", "unread_count" => 0]);
    exit();
}

$stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM contact_messages WHERE is_read = 0");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

echo json_encode(["success" => true, "unread_count" => $row['unread_count'] ?? 0]);
?>
