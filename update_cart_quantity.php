<?php
session_start();
include 'cartDB_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

if ($cart_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Μη έγκυρη ποσότητα ή προϊόν.']);
    exit();
}

// Έλεγχος αποθέματος
$sql = "SELECT p.price, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ? AND c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $cart_id, $user_id);
$stmt->execute();
$stmt->bind_result($price, $stock);
$stmt->fetch();
$stmt->close();

if ($quantity > $stock) {
    echo json_encode(['success' => false, 'message' => 'Δεν υπάρχει αρκετό απόθεμα για το προϊόν.']);
    exit();
}

// Ενημέρωση ποσότητας στο καλάθι
$sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iii', $quantity, $cart_id, $user_id);
$stmt->execute();
$stmt->close();

// Υπολογισμός νέου συνόλου και υποσυνόλου
$sql = "SELECT SUM(c.quantity * p.price) AS total, SUM(c.quantity) AS total_items FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($total, $total_items);
$stmt->fetch();
$stmt->close();

$subtotal = $price * $quantity;

echo json_encode([
    'success' => true, 
    'subtotal' => floatval($subtotal), 
    'total' => floatval($total), 
    'total_items' => intval($total_items)
]);

?>
