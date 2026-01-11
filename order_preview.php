<?php
include 'cartDB_connection.php'; // Database connection file

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Έλεγχος αν ο χρήστης είναι admin
$is_admin = ($_SESSION['role'] === 'admin');

// Ανάκτηση του user_id από το session
$user_id = $_SESSION['user_id'];

// Ενημέρωση κατάστασης παραγγελίας
if ($is_admin && isset($_POST['update_status'])) {
    $new_status = 'Ολοκληρώθηκε';
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    $update_stmt->close();
    // Ανακατεύθυνση για να ανανεωθεί η σελίδα και να εμφανιστεί η ενημερωμένη κατάσταση
    header("Location: order_preview.php?order_id=$order_id");
    exit();
}

// Ανάκτηση των λεπτομερειών της παραγγελίας
if ($is_admin) {
    $sql = "SELECT o.*, u.fName, u.lName FROM orders o JOIN registration u ON o.user_id = u.id WHERE o.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
} else {
    $sql = "SELECT o.*, u.fName, u.lName FROM orders o JOIN registration u ON o.user_id = u.id WHERE o.id = ? AND o.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $user_id);
}
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit();
}

// Ανάκτηση των προϊόντων της παραγγελίας
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

include 'load_header.php';

?>
<link rel="stylesheet" href="css/order_preview.css">
<link rel="stylesheet" href="css/footer.css">

<div class="order-preview-container">
    <h2 class="order-preview-title">Προεπισκόπηση Παραγγελίας #<?php echo $order['id']; ?></h2>
    <p class="order-date"><strong>Ημερομηνία Παραγγελίας:</strong> <?php echo (new DateTime($order['order_date']))->format('d/m/Y H:i:s'); ?></p>
    <div class="order-details">
        <div class="customer-info">
            <h3 class="order-info-title">Στοιχεία Πελάτη</h3>
            <p><strong>Όνομα Πελάτη:</strong> <?php echo htmlspecialchars($order['fName'] . ' ' . $order['lName'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Διεύθυνση Αποστολής:</strong> <?php echo htmlspecialchars($order['address'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Τηλέφωνο:</strong> <?php echo htmlspecialchars($order['number'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="order-info">
            <h3 class="order-info-title">Στοιχεία Παραγγελίας</h3>
            <p><strong>Αριθμός Παραγγελίας:</strong> #<?php echo $order['id']; ?></p>
            <p><strong>Σύνολο:</strong> €<?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Μέθοδος Πληρωμής:</strong> 
                <?php 
                if ($order['payment_method'] == 'cash') {
                    echo 'Μετρητά';
                } elseif ($order['payment_method'] == 'card') {
                    echo 'Χρεωστική/Πιστωτική Κάρτα';
                } else {
                    echo htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8');
                }
                ?>
            </p>   
            <p><strong>Μέθοδος Παράδοσης:</strong> 
                <?php 
                if ($order['delivery_method'] == 'store_pickup') {
                    echo 'Παραλαβή από το Κατάστημα';
                } elseif ($order['delivery_method'] == 'home_delivery') {
                    echo 'Κατ\' οίκον Παράδοση';
                } else {
                    echo htmlspecialchars($order['delivery_method'], ENT_QUOTES, 'UTF-8');
                }
                ?>
            </p>
        </div>
    </div>
    <div>
        <p class="order-status"><strong>Κατάσταση Παραγγελίας:</strong> 
                    <?php 
                    echo htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8'); 
                    if ($order['status'] == 'Εκκρεμεί') {
                        echo ' <span class="status-icon">&#9888;</span>'; // Warning icon
                    }
                    ?>
        </p>
        <?php if ($is_admin && $order['status'] == 'Εκκρεμεί'): ?>
            <div style="text-align: center;">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="update_status" value="1">
                    <button type="submit" class="btn btn-success">Ολοκλήρωση Παραγγελίας</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <div class="order-items">
        <h3>Προϊόντα Παραγγελίας</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Όνομα Προϊόντος</th>
                        <th>Ποσότητα</th>
                        <th>Τιμή</th>
                        <th>Σύνολο</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>€<?php echo number_format($item['price'], 2); ?></td>
                        <td>€<?php echo number_format($item['total'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="order-total">
            <p><strong>Σύνολο Παραγγελίας:</strong> €<?php echo number_format($order['total_amount'], 2); ?></p>
        </div>
    </div>
</div>

<?php
include 'footer.php';
$conn->close();
?>