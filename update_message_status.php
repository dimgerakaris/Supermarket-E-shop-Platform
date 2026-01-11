<?php
session_start();
include 'cartDB_connection.php'; // Database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message_id = intval($data['id']);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Update is_read status in both messages and message_replies tables
    $sql = "UPDATE messages SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $message_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $sql_replies = "UPDATE message_replies SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt_replies = $conn->prepare($sql_replies);
    $stmt_replies->bind_param("ii", $message_id, $user_id);
    $stmt_replies->execute();
    $stmt_replies->close();

    echo json_encode(['success' => true]);

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>