<?php
session_start();
include 'cartDB_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? 0;

if ($product_id) {
    $sql = "DELETE FROM cart WHERE id = $product_id AND user_id = $user_id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Το προϊόν αφαιρέθηκε από το καλάθι.</p>";
    } else {
        echo "<p>Σφάλμα: " . $conn->error . "</p>";
    }
}

header("Location: cart.php");
exit();
?>
