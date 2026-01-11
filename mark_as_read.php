<?php
include 'cartDB_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$category = $data['category'];

if ($category === 'replies') {
    $sql = "UPDATE message_replies SET is_read = 1 WHERE id = ?";
} elseif ($category === 'messages') {
    $sql = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid category']);
    exit();
}

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No rows updated']);
}

$stmt->close();
$conn->close();
?>