<?php
session_start();
include 'cartDB_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Δεν έχετε συνδεθεί."]);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Μη έγκυρο αίτημα."]);
    exit();
}

$message_id = intval($data['id']);

$sql = "SELECT cm.message AS original_message
        FROM message_replies mr
        JOIN contact_messages cm ON mr.message_id = cm.id
        WHERE mr.id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode(["success" => false, "message" => "Σφάλμα στη βάση δεδομένων."]));
}

$stmt->bind_param("i", $message_id);
$stmt->execute();
$stmt->bind_result($original_message);
$stmt->fetch();
$stmt->close();

if ($original_message) {
    echo json_encode(["success" => true, "original_message" => $original_message]);
} else {
    echo json_encode(["success" => false, "message" => "Το αρχικό μήνυμα δεν βρέθηκε."]);
}

$conn->close();
?>
