<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ενεργοποίηση προβολής σφαλμάτων
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'cartDB_connection.php';

// Υπολογισμός cart count
$cart_count = 0;
$totalAmount = 0; // Αρχικοποίηση της μεταβλητής $totalAmount
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $cart_count = $row['total_items'] ?? 0;
    }
    $stmt->close();
}

// Count unread messages
// Count unread messages and replies
$messages_count = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
        SELECT 
            (SELECT COUNT(*) FROM messages WHERE user_id = ? AND is_read = 0) +
            (SELECT COUNT(*) FROM message_replies WHERE user_id = ? AND is_read = 0) AS unread_count
    ");
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $messages_count = $row['unread_count'] ?? 0;
    }
    $stmt->close();
}


// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greek SuperMarket</title>
    <link rel="stylesheet" href="css/myCSS.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="cartScript.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <div class="logotypo">
                <a href="index.php">
                    <img src="PHOTO/logotypo.PNG" alt="My Logo Image!">
                </a>
            </div>

            <ul>
                <li><a href="index.php" class="index-link">Αρχική</a></li>
                <li><a href="products.php" class="products-link">Προϊόντα</a></li>
                <li><a href="about.php">Σχετικά με εμάς</a></li>
                <li><a href="contact.php">Επικοινωνία</a></li>              
            </ul>

            <div class="icon">
                <div class="profile-dropdown">
                    <a href="#" class="profile-icon"><i class="fas fa-user"></i></a>
                    <div class="dropdown-content">
                        <a href="profile.php">Προφίλ</a>
                        <a href="orders.php">Παραγγελίες</a>
                    </div>
                </div>
                <div class="messages-wrapper">
                    <a href="messages.php" class="messages-icon">
                        <i class="fas fa-envelope"></i>
                        <span id="messages-count"><?php echo $messages_count ?? 0; ?></span>
                    </a>
                </div>
                <div class="cart-wrapper">
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count"><?php echo $cart_count ?? 0; ?></span>
                    </a>
                    <div >
                        <div class="cart-dropdown" id="mini-cart-content">
                            <h3 class="cart-title">Το Καλάθι σας</h3>
                            <div class="cart-items">
                                <?php
                                if (isset($_SESSION['user_id'])) {
                                    $user_id = $_SESSION['user_id'];
                                    $stmt = $conn->prepare("
                                        SELECT c.id, 
                                            p.name AS product_name, 
                                            p.image AS product_image, 
                                            c.quantity, 
                                            c.price
                                        FROM cart c
                                        JOIN products p ON c.product_id = p.id
                                        WHERE c.user_id = ?
                                    ");

                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $itemTotal = $row['price'] * $row['quantity'];
                                            $totalAmount += $itemTotal;
                                            echo '<div class="cart-item">';
                                            echo '<img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '" class="cart-item-image">';
                                            echo '<div class="cart-item-details">';
                                            echo '<h4 class="cart-item-name">' . $row['product_name'] . '</h4>';
                                            echo '<p class="cart-item-quantity"><strong>Ποσότητα: ' . $row['quantity'] . '</strong></p>';
                                            echo '<p class="cart-item-price"><strong>Τιμή: €' . number_format($row['price'], 2) . '</strong></p>';
                                            echo '<p class="cart-item-total"><strong>Σύνολο: €' . number_format($itemTotal, 2) . '</strong></p>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo "<p>Το καλάθι σας είναι άδειο.</p>";
                                    }

                                    $stmt->close();
                                } else {
                                    echo "<p>Παρακαλώ συνδεθείτε για να δείτε το καλάθι σας.</p>";
                                }
                                ?>
                            </div>
                            <p style="color:#FF5722; font-size:12px; font-weight:bold;">
                                <i class="fas fa-exclamation-circle"></i> Για να επεξεργαστείτε την ποσότητα των προϊόντων, μεταβείτε στο καλάθι αγορών σας.
                            </p>
                            <div class="cart-total">
                                <h4>Σύνολο: €<?php echo number_format($totalAmount, 2); ?></h4>
                            </div>
                            <div class="cart-buttons">
                                <a href="cart.php" class="btn">Μετάβαση στο Καλάθι</a>
                                <button class="btn continue-shopping-btn" onclick="document.querySelector('.cart-dropdown').style.display='none'">Συνέχεια Αγορών</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-box">
                <p>Username: <span><?php echo $_SESSION['user_name'] ?? 'Guest'; ?></span></p>
                <p>Email: <span><?php echo $_SESSION['user_email'] ?? 'guest@example.com'; ?></span></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="logout-btn">Log Out</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn">Σύνδεση</a>
                    <a href="register.php" class="btn">Εγγραφή</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
</body>
</html>