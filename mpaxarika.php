<?php 
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
include 'cartDB_connection.php';

// Ερώτημα για προϊόντα της κατηγορίας 'Mpaxarika'
$sql = "SELECT id, name, image, price, description, stock FROM products WHERE category = 'mpaxarika'";
include 'sort_component.php';
$result = $conn->query($sql);

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Τα Προϊόντα μας - Αλάτια Και Μπαχαρικά</title>
    <LINK REL="stylesheet" type="text/css" href="css/myCSS.css"/>
    <link rel="stylesheet" href="css/menu.css">
    <LINK REL="stylesheet" type="text/css" href="css/subCategoryCSS.css"/>
    <!-- έτοιμες βιβλιοθήκες -->
    <LINK REL="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="cartScript.js" defer></script>

    <link rel="stylesheet" href="css/footer.css">
    <script src="stockCheck.js" defer></script> <!-- Προσθήκη του αρχείου stockCheck.js -->
</head>
<body>
    <section id="Products">
        <h1>Αλάτια Και Μπαχαρικά</h1>
        <div class="products">
            <?php
            // Έλεγχος αν υπάρχουν αποτελέσματα
            if ($result->num_rows > 0) {
                // Εμφάνιση δεδομένων προϊόντων
                while($row = $result->fetch_assoc()) {
                    echo '<div class="product-item">';
                    echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="product-img">';
                    echo '<h2>' . $row['name'] . '</h2>';
                    echo '<p class="price">€' . number_format($row['price'], 2) . '</p>';
                    echo '<p class="description">' . $row['description'] . '</p>';
                    // Προσθήκη ελέγχου ποσότητας αποθέματος
                    echo '<input type="number" id="quantity_' . $row['id'] . '" value="1" min="1" max="' . $row['stock'] . '" class="quantity-input" oninput="checkStock(' . $row['id'] . ', ' . $row['stock'] . ')">'; 
                    // Προσθήκη μηνύματος αποθέματος
                    echo '<p id="stock-message-' . $row['id'] . '" style="display: none; color: red;">Δεν υπάρχει τόσο απόθεμα</p>'; 
                    if ($row['stock'] <= 0) {
                        echo '<p class="out-of-stock-title">Μη διαθέσιμο προϊόν</p>';
                        echo '<p class="out-of-stock" style="background-color:rgb(61, 61, 61); color: white; margin-left: 75px; margin-right: 75px;border: none; padding: 10px 20px; font-size: 14px; cursor: pointer; border-radius: 5px; transition: background-color 0.3s ease;">Μη διαθέσιμο προϊόν</p>';
                    } else {
                        echo '<p class="in-stock">Διαθέσιμο προϊόν</p>';
                        // Προσθήκη ελέγχου αποθέματος κατά την προσθήκη στο καλάθι
                        echo '<button class="add-to-cart" id="add-to-cart-' . $row['id'] . '" onclick="if(validateStock(' . $row['id'] . ', ' . $row['stock'] . ')) { addToCart(' . $row['id'] . ', ' . $row['price'] . '); }">Προσθήκη στο Καλάθι</button>'; 
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>Δεν υπάρχουν προϊόντα στην κατηγορία αυτή.</p>';
            }
            ?>
        </div>
    </section>
</body>
</html>

<?php
include 'footer.php';
?>
