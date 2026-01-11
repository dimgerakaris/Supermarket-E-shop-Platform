<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'cartDB_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['reply_text'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit();
}

$message_id = intval($data['id']);
$reply_text = trim($data['reply_text']);
$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['admin_id']); // Αν υπάρχει admin_id, σημαίνει ότι είναι admin

if (empty($reply_text)) {
    echo json_encode(['success' => false, 'message' => 'Το μήνυμα απάντησης δεν μπορεί να είναι κενό.']);
    exit();
}

if ($is_admin) {
    // Αν ο χρήστης είναι διαχειριστής, αποθηκεύουμε την απάντηση στον πίνακα message_replies
    $stmt = $conn->prepare("INSERT INTO message_replies (message_id, admin_id, reply_text, replied_at, is_read, user_id) VALUES (?, ?, ?, NOW(), 0, ?)");
    $stmt->bind_param('iisi', $message_id, $_SESSION['admin_id'], $reply_text, $user_id);
} else {
    // Αν ο χρήστης είναι απλός χρήστης, προσθέτουμε την απάντηση στον πίνακα message_replies
    $stmt = $conn->prepare("INSERT INTO message_replies (message_id, user_id, reply_text, replied_at, is_read) VALUES (?, ?, ?, NOW(), 0)");
    $stmt->bind_param('iis', $message_id, $user_id, $reply_text);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Η απάντηση καταχωρήθηκε επιτυχώς.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Η καταχώρηση της απάντησης απέτυχε.', 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
