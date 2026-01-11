<?php
session_start();
include 'cartDB_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p>Το καλάθι σας είναι άδειο.</p>';
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT p.name, p.price, c.quantity, p.image 
        FROM cart c
        JOIN products p ON p.id = c.product_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$total_items = 0;

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total_price += $subtotal;
    $total_items += $row['quantity'];

    echo '<div class="cart-item">';
    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
    echo '<p>' . htmlspecialchars($row['name']) . '</p>';
    echo '<p>Ποσότητα: ' . $row['quantity'] . '</p>';
    echo '<p>Τιμή: ' . number_format($row['price'], 2) . ' €</p>';
    echo '<p>Σύνολο: ' . number_format($subtotal, 2) . ' €</p>';
    echo '</div>';
}

echo '<p><strong>Σύνολο: ' . number_format($total_price, 2) . ' €</strong></p>';
$stmt->close();
$conn->close();
?>
