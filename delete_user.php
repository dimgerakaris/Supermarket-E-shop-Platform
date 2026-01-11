<?php
session_start();
include 'cartDB_connection.php';

header('Content-Type: application/json');

// Έλεγχος αν ο χρήστης είναι admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Μη εξουσιοδοτημένη πρόσβαση!"]);
    exit();
}

// Έλεγχος αν έχει σταλεί ID μέσω POST
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(["success" => false, "message" => "Μη έγκυρο ID χρήστη!"]);
    exit();
}

$user_id = intval($_POST['id']);

// Διαγραφή του χρήστη από τη βάση δεδομένων
$stmt = $conn->prepare("DELETE FROM registration WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Αποτυχία διαγραφής χρήστη!"]);
}

$stmt->close();
$conn->close();
?>
