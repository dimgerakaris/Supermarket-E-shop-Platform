<?php
session_start();
include 'cartDB_connection.php';

if (!isset($_GET['id'])) {
    header("Location: /ΠΤΥΧΙΑΚΗ/customers-orders.php");
    exit;
}

$order_id = intval($_GET['id']);

$conn = new mysqli($host, $user, $password, $database);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}

// Διαγραφή από order_items πρώτα
$conn->query("DELETE FROM order_items WHERE order_id = $order_id");

// Διαγραφή από orders
$conn->query("DELETE FROM orders WHERE id = $order_id");

$conn->close();

// Σωστή ανακατεύθυνση
header("Location: /ΠΤΥΧΙΑΚΗ/customers-orders.php");
exit;
