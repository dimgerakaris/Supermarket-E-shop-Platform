<?php
include 'cartDB_connection.php';

if (isset($_GET['message_id'])) {
    $message_id = intval($_GET['message_id']);

    $sql = "SELECT reply_text, replied_at FROM message_replies WHERE message_id = ? ORDER BY replied_at ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $replies = [];
    while ($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success', 'replies' => $replies]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid message ID']);
}
?>