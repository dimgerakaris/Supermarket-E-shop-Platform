<?php
session_start();
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
include 'cartDB_connection.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    echo "<p>Παρακαλώ συνδεθείτε πρώτα για να ολοκληρώσετε την παραγγελία σας.</p>";
    echo "<a href='login.php'>Login</a>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Ανάκτηση πληροφοριών χρήστη από τη βάση δεδομένων
$sql = "SELECT fName, lName, number, odos AS address, polh AS city, tk AS postal_code 
        FROM registration 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $fName = $user_data['fName'];
    $lName = $user_data['lName'];
    $number = $user_data['number'];
    $address = $user_data['address'];
    $city = $user_data['city'];
    $postal_code = $user_data['postal_code'];
} else {
    echo "Δεν βρέθηκαν στοιχεία χρήστη.";
    exit();
}

// Έλεγχος αν έχουν σταλεί οι επιλογές παράδοσης και πληρωμής
if (empty($_POST['delivery_method']) || empty($_POST['payment_method'])) {
    echo "Παρακαλώ επιλέξτε τρόπο παράδοσης και πληρωμής.";
    exit();
}

$delivery_method = $_POST['delivery_method'];
$payment_method = $_POST['payment_method'];

// Αν επιλεγεί πληρωμή με κάρτα, αποθηκεύουμε επιπλέον στοιχεία
$card_last_digits = NULL;
$card_expiry_date = NULL;
$payment_token = NULL;

if ($payment_method === 'card') {
    if (empty($_POST['cardNumber']) || empty($_POST['expiryDate']) || empty($_POST['cvv'])) {
        echo "Παρακαλώ συμπληρώστε όλα τα στοιχεία της κάρτας.";
        exit();
    }
    $card_last_digits = substr($_POST['cardNumber'], -4); // Παίρνουμε τα τελευταία 4 ψηφία της κάρτας
    $card_expiry_date = $_POST['expiryDate'];
    $payment_token = md5(uniqid(rand(), true)); // Μοναδικό token
} else {
    $card_last_digits = '';
    $card_expiry_date = '';
    $payment_token = '';
}

// Υπολογισμός συνολικού ποσού από το καλάθι
$sql = "SELECT c.product_id, p.name AS product_name, c.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$order_items = [];

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total_amount += $subtotal;
    $order_items[] = $row; // Αποθήκευση για εισαγωγή στον πίνακα order_items
}

// Εισαγωγή στην orders
$sql = "INSERT INTO orders (user_id, delivery_method, payment_method, total_amount, number, address, city, postal_code, fName, lName, card_last_digits, card_expiry_date, payment_token, order_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issdsssssssss", $user_id, $delivery_method, $payment_method, $total_amount, $number, $address, $city, $postal_code, $fName, $lName, $card_last_digits, $card_expiry_date, $payment_token);

$stmt->execute();

// Λήψη του order_id
$order_id = $stmt->insert_id;

// Εισαγωγή στον πίνακα order_items
foreach ($order_items as $item) {
    $sql = "INSERT INTO order_items (order_id, product_name, quantity, price, total) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $subtotal = $item['price'] * $item['quantity'];
    $stmt->bind_param("isidd", $order_id, $item['product_name'], $item['quantity'], $item['price'], $subtotal);
    $stmt->execute();
}

// Λήψη προϊόντων από το καλάθι για μείωση αποθέματος
$sql = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $productId = $row['product_id'];
    $quantity = $row['quantity'];

    // Μείωση αποθέματος
    $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param('ii', $quantity, $productId);
    $stmt2->execute();
    $stmt2->close();
}

// Καθαρισμός του καλαθιού
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$conn->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ολοκλήρωση Παραγγελίας</title>
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
<div class="content">
    <div style="text-align: center; margin-top: 50px;">
        <img src="PHOTO/success_icon.png" alt="Success" style="width: 100px; height: 100px;"><br><br>
        <h2 style="color: #FF5722;">Η παραγγελία σας ολοκληρώθηκε με επιτυχία!</h2>
        <br><br>
        <button style="padding: 10px 20px; margin: 10px; background-color: #FF5722; font-size:16px; color: white; border: none; border-radius: 5px; cursor: pointer;" 
                onclick="window.location.href='order_preview.php?order_id=<?php echo $order_id; ?>'">
            Προεπισκόπηση Παραγγελίας
        </button>
        <button style="padding: 10px 20px; margin: 10px; background-color: #FF5722; font-size:16px; color: white; border: none; border-radius: 5px; cursor: pointer;" 
                onclick="window.location.href='products.php'">
           Επιστροφή στα Προϊόντα
        </button>
    </div>
</div>

<?php
include 'footer.php';
?>
</body>
</html>