<?php include 'load_header.php'; ?>

<?php
// Ξεκινάμε το session
//session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος και έχει τον ρόλο admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Επιλογή header ανάλογα με το ρόλο του χρήστη
if ($_SESSION['role'] === 'admin') {
    include 'header-admin.php';
} else {
    include 'header.php';
}

// Έλεγχος και αρχικοποίηση των session μεταβλητών αν δεν υπάρχουν
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    $_SESSION['user_name'] = 'Guest';
    $_SESSION['user_email'] = 'guest@exampl.com';
}

include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="content">
        <section id="Home">
            <div class="main">
                <div class="mtext">
                    <h1>Καλώς ήρθατε στο <span>Admin Panel!</span></h1>
                    <div class="buttons-container">
                        <button class="explore-btn">
                            <a href="products.php" style="text-decoration: none; color: inherit;">Διαχείριση Προϊόντων</a>
                        </button>
                        <hr style="width: 50%; border: 1px solid #ff6600; margin: 15px auto;">
                        <button class="explore-btn">
                            <a href="orders.php" style="text-decoration: none; color: inherit;">Διαχείριση Παραγγελιών</a>
                        </button>
                    </div>
                </div>
                <div class="m_image">
                    <img src="PHOTO/m_photo1.png" alt="Admin Panel">
                </div>
            </div>
        </section>
    </div>
    <?php include 'footer.php'; ?>
    <script src="myscript.js"></script>
</body>
</html>
