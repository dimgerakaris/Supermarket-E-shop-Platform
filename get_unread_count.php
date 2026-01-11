<?php
session_start();
include 'cartDB_connection.php'; // Database connection file

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get unread count from both messages and message_replies tables
$sql = "SELECT 
            (SELECT COUNT(*) FROM messages WHERE user_id = ? AND is_read = 0) +
            (SELECT COUNT(*) FROM message_replies WHERE user_id = ? AND is_read = 0) AS unread_count";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    exit();
}
$row = $result->fetch_assoc();

if ($row === null) {
    echo json_encode(['success' => false, 'message' => 'No unread messages found.']);
} else {
    echo json_encode(['success' => true, 'unread_count' => $row['unread_count']]);
}

$stmt->close();
$conn->close();
?>