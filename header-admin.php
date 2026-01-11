<?php 

// Ενεργοποίηση προβολής σφαλμάτων
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'cartDB_connection.php';

// Count unread messages
$messages_count = 0;
if (isset($_SESSION['admin_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM contact_messages WHERE is_read = 0");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $messages_count = $row['unread_count'] ?? 0;
    }
    $stmt->close();
}

// Count new orders (only pending orders)
$new_orders_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) as new_orders FROM orders WHERE status = 'Εκκρεμεί'");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $new_orders_count = $row['new_orders'] ?? 0;
}
$stmt->close();

// Handle logout
if (isset($_POST['logout'])) {
    session_start();
    
    // Καθαρισμός όλων των μεταβλητών της συνεδρίας
    $_SESSION = [];

    // Αν υπάρχει cookie συνεδρίας, το διαγράφουμε
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Καταστρέφουμε τη συνεδρία
    session_destroy();

    // Διαγραφή του καλαθιού αν υπάρχει αποθηκευμένο στο session
    unset($_SESSION['cart']);

    // Διαγραφή του καλαθιού αν είναι σε cookie
    setcookie('cart', '', time() - 3600, '/');

    // Ανακατεύθυνση στο login.php
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/myCSS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .a {
            text-decoration: none;
            font-size: 24px;
            position: relative;
        }
        .notification-icon {
            position: relative;
            display: inline-block;
            margin-right: 20px;
            color: #333;
            text-decoration: none;
            font-size: 24px;
            position: relative;
        }
        .notification-icon .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ff6600;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logotypo">
                <a href="admin_dashboard.php">
                    <img src="PHOTO/logotypo.PNG" alt="My Logo Image!">
                </a>
            </div>

            <ul>
                <li><a href="admin_dashboard.php" class="index-link">Αρχική</a></li>
                <li><a href="manage_stock.php" class="products-link">Απόθεμα Προϊόντων</a></li>
                <li><a href="customers.php" class="customers-link">Πελάτες</a></li>
                <li><a href="customers-orders.php" class="orders-link">Παραγγελίες</a></li>
                <!-- <li><a href="about.php">Σχετικά με εμάς</a></li>
                <li><a href="contact.php">Επικοινωνία</a></li>  -->
            </ul>
            
            <div class="icon">
                <div class="profile-dropdown">
                    <a href="profile.php" class="profile-icon"><i class="fas fa-user"></i></a>
                    <div class="dropdown-content">
                        <!-- <a href="profile.php">Προφίλ</a> -->
                        <!-- <a href="orders.php">Παραγγελίες</a> -->
                    </div>
                </div>
                <div class="messages-wrapper">
                    <a href="admin_messages.php" class="messages-icon">
                        <i class="fas fa-envelope"></i>
                            <span id="messages-count"><?php echo $messages_count; ?></span>

                    </a>
                </div>
                <div class="notification-icon">
                    <a href="customers-orders.php">
                        <i class="fas fa-bell"></i>
                        <?php if ($new_orders_count > 0): ?>
                            <span class="badge"><?php echo $new_orders_count; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <div class="user-box">
                <p>Admin: <span><?php echo $_SESSION['user_name'] ?? 'Guest'; ?></span></p>
                <p>Email: <span><?php echo $_SESSION['user_email'] ?? 'guest@example.com'; ?></span></p>
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="logout-btn">Log Out</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn">Logout</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateMessageCounter() {
            fetch('fetch_unread_messages.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messageCounter = document.getElementById('messages-count');
                    if (messageCounter) {
                        messageCounter.textContent = data.unread_count > 0 ? data.unread_count : '';
                        messageCounter.style.display = data.unread_count > 0 ? 'inline-block' : 'none';
                    }
                }
            })
            .catch(error => console.error('Σφάλμα στη φόρτωση μηνυμάτων:', error));
        }

        updateMessageCounter(); 
        setInterval(updateMessageCounter, 10000);
    });
    </script>

</body>
</html>