<?php
// Ξεκινάμε το session
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_email'])) {
    $_SESSION['user_name'] = 'Guest';
    $_SESSION['user_email'] = 'guest@exampl.com';
}


include 'load_header.php';
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
    <title>Home Page</title>
</head>
<body>
    <div class="content">
        <section id="Home">
            <div class="main">
                <div class="mtext">
                    <h1>Η <span>ΚΑΛΥΤΕΡΗ</span> ποιότητα, στις 
                        <span>ΚΑΛΥΤΕΡΕΣ</span> τιμές!!</h1>
                    <div class="buttons-container">
                        <button class="explore-btn">
                            <a href="products.php" style="text-decoration: none; color: inherit;">Εξερευνήστε τα προϊόντα μας!</a>
                        </button>
                        <hr style="width: 50%; border: 1px solid #ff6600; margin: 15px auto;">
                        <button class="explore-btn">
                            <a href="cart.php" style="text-decoration: none; color: inherit;">Για παραγγελίες πατήστε εδώ!</a>
                        </button>
                    </div>
                </div>
                <div class="m_image">
                    <img src="PHOTO/m_photo1.png" alt="Main Photo">
                </div>
            </div>
        </section>
    </div>
    <?php include 'footer.php'; ?>
    <script src="myscript.js"></script>
</body>
</html>