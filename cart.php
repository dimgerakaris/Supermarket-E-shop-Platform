<?php
ob_start();
session_start();
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
include 'cartDB_connection.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    echo "<p>Παρακαλώ συνδεθείτε πρώτα για να δείτε το καλάθι σας.</p>";
    echo "<a href='login.php'>Login</a>";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['checkout'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Έλεγχος αποθέματος
    $sql = "SELECT stock FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($quantity > $stock) {
        $_SESSION['error'] = "Δεν υπάρχει αρκετό απόθεμα για το προϊόν.";
    } else {
        // Προσθήκη προϊόντος στο καλάθι
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiii', $user_id, $productId, $quantity, $quantity);
        $stmt->execute();
        $stmt->close();
    }
}

// Λήψη προϊόντων από το καλάθι
$sql = "SELECT c.id, 
               p.name AS product_name, 
               p.price, 
               c.quantity, 
               p.image AS product_image,
               p.stock AS product_stock
        FROM cart c
        JOIN products p ON p.id = c.product_id
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    // Ανακατεύθυνση στη σελίδα παράδοσης και πληρωμής
    header("Location: delivery_payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Το Καλάθι Μου</title>
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <div class="content">
        <div class="cart-container">
            <div class="cart-header">
                <a href="products.php" class="back-to-products"><i class="fas fa-arrow-left"></i> Επιστροφή στα Προϊόντα</a>
                <h1>Το Καλάθι Μου</h1>
            </div>
            <?php if ($result->num_rows > 0): ?>
                <div class="cart-items">
                <?php 
                $total = 0; 
                $total_items = 0;
                while ($row = $result->fetch_assoc()): 
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                    $total_items += $row['quantity'];
                ?>
                <div class="cart-item">
                    <div class="item-image">
                        <img src="<?php echo !empty($row['product_image']) ? htmlspecialchars($row['product_image'], ENT_QUOTES, 'UTF-8') : 'PHOTO/default.jpg'; ?>" 
                            alt="<?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="item-details">
                        <h2><?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p>Τιμή: <?php echo number_format($row['price'], 2); ?> €</p>
                        <p>Ποσότητα: 
                            <div class="quantity-container">
                                <button onclick="updateQuantity(<?php echo $row['id']; ?>, -1)">-</button>
                                <input type="number" id="quantity-<?php echo $row['id']; ?>" value="<?php echo $row['quantity']; ?>" min="1" max="<?php echo $row['product_stock']; ?>" onchange="updateQuantity(<?php echo $row['id']; ?>, 0)">
                                <button onclick="updateQuantity(<?php echo $row['id']; ?>, 1)">+</button>
                            </div>
                        </p>
                        <p>Σύνολο: <span id="subtotal-<?php echo $row['id']; ?>"><?php echo number_format($subtotal, 2); ?></span> €</p>
                        <?php if (isset($_SESSION['error'])): ?>
                            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="item-actions">
                        <a href="deleteFromCart.php?id=<?php echo $row['id']; ?>">Διαγραφή</a>
                    </div>
                </div>
                <?php endwhile; ?>
                </div>
                <div class="cart-total" style="font-size:16px;">
                    <p>Σύνολο Προϊόντων: <span id="total-items"><?php echo $total_items; ?></span></p>
                    <br>              
                    <p>Συνολικό Ποσό: <span id="original-total"><?php echo number_format($total, 2); ?></span>€</p>
                </div>
                <div class="cart-footer">
                    <?php if ($_SESSION['role'] !== 'admin'): ?>
                        <form method="POST" action="cart.php">
                            <button type="submit" name="checkout" class="btn proceed-btn">Συνέχεια στην Παράδοση και Πληρωμή</button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php else: ?>
                <p>Το καλάθι σας είναι άδειο.</p>
            <?php endif; ?>
        </div>
        <script>
        function updateQuantity(cartId, quantity) {
            if (quantity < 1) {
                alert('Η ποσότητα πρέπει να είναι τουλάχιστον 1.');
                return;
            }
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cart_quantity.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('quantity-' + cartId).value = quantity;
                        document.getElementById('subtotal-' + cartId).innerText = response.subtotal.toFixed(2);
                        document.getElementById('original-total').innerText = response.total.toFixed(2);
                        document.getElementById('total-items').innerText = response.total_items;
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send('cart_id=' + cartId + '&quantity=' + quantity);
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            var modals = document.getElementsByClassName('modal');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        }
        </script>
    </div>
    <?php include 'footer.php'; ?>
    <script>
function changeQuantity(cartId, change) {
    let input = document.getElementById(`quantity-${cartId}`);
    let newQuantity = parseInt(input.value) + change;

    if (newQuantity >= 1) {
        input.value = newQuantity;
        updateQuantity(cartId, newQuantity);
    }
}

function updateQuantity(cartId, change) {
    let quantityInput = document.getElementById('quantity-' + cartId);
    let newQuantity = parseInt(quantityInput.value) + change;

    if (newQuantity < 1) {
        newQuantity = 1;
    }

    quantityInput.value = newQuantity;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_cart_quantity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById('subtotal-' + cartId).innerText = response.subtotal.toFixed(2);
                document.getElementById('original-total').innerText = response.total.toFixed(2);
                document.getElementById('total-items').innerText = response.total_items;

                let cartIcon = document.querySelector('#cart-count'); // Βεβαιώσου ότι είναι το σωστό ID
                if (cartIcon) {
                    cartIcon.innerText = response.total_items;
                }

                // Καλεί τη `updateCartCount()` και `updateMiniCart()` για να ενημερωθεί το cart στο header!
                updateCartCount();
                updateMiniCart();
            } else {
                alert(response.message);
            }
        }
    };
    xhr.send('cart_id=' + cartId + '&quantity=' + newQuantity);
}


function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json()) // Διαβάζουμε JSON
        .then(data => {
            document.getElementById('cart-count').innerText = data.total_items; 
        })
        .catch(error => console.error('Error updating cart count:', error));
}

function updateMiniCart() {
    fetch('header.php') // Φέρνει το header.php
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let newMiniCart = doc.querySelector("#mini-cart-content"); // Παίρνει το ενημερωμένο καλάθι

            if (newMiniCart) {
                document.querySelector("#mini-cart-content").innerHTML = newMiniCart.innerHTML; // Αντικαθιστά το παλιό
            }
        })
        .catch(error => console.error('Error updating mini cart:', error));
}


</script>

</body>
</html>

<?php
// Κλείσιμο της σύνδεσης
$conn->close();
ob_end_flush();
?>