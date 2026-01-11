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
$sql = "SELECT fName, lName, number, odos, polh, tk FROM registration WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fName = $row['fName'];
    $lName = $row['lName'];
    $number = $row['number'];
    $odos = $row['odos'];
    $polh = $row['polh'];
    $tk = $row['tk'];
} else {
    echo "Δεν βρέθηκαν πληροφορίες χρήστη.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_order'])) {
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

    // Καθαρισμός καλαθιού
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Εισαγωγή παραγγελίας στη βάση δεδομένων
    $delivery_method = $_POST['delivery_method'];
    $payment_method = $_POST['payment_method'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO orders (user_id, delivery_method, payment_method, address, city, postal_code, phone, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $delivery_method, $payment_method, $address, $city, $postal_code, $phone);
    $stmt->execute();

    // Λήψη του ID της νέας παραγγελίας
    $order_id = $stmt->insert_id;

    // Εισαγωγή προϊόντων παραγγελίας στη βάση δεδομένων
    $sql = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $productId = $row['product_id'];
        $quantity = $row['quantity'];

        $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("iii", $order_id, $productId, $quantity);
        $stmt2->execute();
        $stmt2->close();
    }

    echo "<p>Η παραγγελία σας ολοκληρώθηκε με επιτυχία!</p>";
    echo "<a href='products.php'>Επιστροφή στα Προϊόντα</a>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Παράδοση και Πληρωμή</title>
    <link rel="stylesheet" href="css/delivery_payment.css">
    <style>
        .valid-check {
            display: none;
            color: green;
            position: absolute;
            right: 10px;
            bottom: 15px;
        }
        .valid-check.visible {
            display: inline;
        }
        .input-container {
            position: relative;
        }
        .input-container input.invalid {
            border-color: red;
        }
        .error-message {
            color: red;
            display: none;
        }
        .error-message.visible {
            display: block;
        }
        .total-box {
            background-color: #FF5722;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
<link rel="stylesheet" href="css/footer.css"></head>
<body>
<div class="delivery-container">
    <h1>Παράδοση και Πληρωμή</h1>

    
    <form method="post" action="complete_order.php" onsubmit="return validateForm()">
        <div class="address-section section-box">
            <h2 class="customer-title">Στοιχεία Πελάτη</h2>
            <p><strong>Όνομα:</strong> <?php echo htmlspecialchars($fName . ' ' . $lName); ?></p>
            <p><strong>Τηλέφωνο:</strong> <?php echo htmlspecialchars($number); ?></p>
            <p><strong>Διεύθυνση:</strong> <?php echo htmlspecialchars($odos); ?></p>
            <p><strong>Πόλη:</strong> <?php echo htmlspecialchars($polh); ?></p>
            <p><strong>Τ.Κ.:</strong> <?php echo htmlspecialchars($tk); ?></p>

            <!-- Κρυφά πεδία -->
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($odos); ?>">
            <input type="hidden" name="city" value="<?php echo htmlspecialchars($polh); ?>">
            <input type="hidden" name="postal_code" value="<?php echo htmlspecialchars($tk); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($number); ?>">
        </div>

        <div class="store-pickup-section section-box">
            <h2 class="pickup-title">Παράδοση Παραγγελίας</h2>
            <div class="delivery-options">
                <label>
                    <input type="radio" name="delivery_method" value="store_pickup" required> Παραλαβή από το κατάστημα
                </label>
                <label>
                    <input type="radio" name="delivery_method" value="home_delivery" required> Παράδοση στο χώρο μου
                </label>
            </div>
        </div>

        <div class="payment-section section-box">
            <h2 class="payment-title">Επιλογή Πληρωμής</h2>
            <div class="payment-options">
                <label>
                    <input type="radio" name="payment_method" value="card" required onclick="showCardForm()"> Πιστωτική/Χρεωστική Κάρτα
                </label>
                <label>
                    <input type="radio" name="payment_method" value="cash" required onclick="hideCardForm()"> Μετρητά κατά την παράδοση
                </label>
            </div>
            <!-- Φόρμα στοιχείων κάρτας (αρχικά κρυφή) -->
            <div id="cardForm" style="display: none; margin-top: 15px;">
                <h3>Στοιχεία Κάρτας</h3>
                <div class="input-container">
                    <label for="cardNumber">Αριθμός Κάρτας:</label>
                    <input type="text" name="cardNumber" id="cardNumber" placeholder="1234 5678 9012 3456" oninput="validateCardNumber()">
                    <span id="cardNumberCheck" class="valid-check">✔</span>
                    <div id="cardNumberError" class="error-message">Ο αριθμός της κάρτας πρέπει να είναι 16 ψηφία.</div>
                </div>

                <div class="input-container">
                    <label for="cardName">Όνομα Κατόχου:</label>
                    <input type="text" name="cardName" id="cardName" placeholder="Όνομα Κατόχου" oninput="validateCardName()">
                    <span id="cardNameCheck" class="valid-check">✔</span>
                </div>

                <div class="card-details">
                    <div class="input-container">
                        <label for="expiryDate">Ημερομηνία Λήξης:</label>
                        <input type="text" name="expiryDate" id="expiryDate" placeholder="MM/YY" oninput="validateExpiryDate()">
                        <span id="expiryDateCheck" class="valid-check">✔</span>
                        <div id="expiryDateError" class="error-message">Η ημερομηνία λήξης πρέπει να είναι στη μορφή MM/YY.</div>
                    </div>
                    <div class="input-container">
                        <label for="cvv">CVV:</label>
                        <input type="password" name="cvv" id="cvv" placeholder="123" oninput="validateCVV()">
                        <span id="cvvCheck" class="valid-check">✔</span>
                        <div id="cvvError" class="error-message">Το CVV πρέπει να είναι 3 ψηφία.</div>
                    </div>
                </div>
                <div id="cardErrorMessage" class="error-message">Παρακαλώ συμπληρώστε όλα τα στοιχεία της κάρτας.</div>
            </div>
            <form method="POST" action="delivery_payment.php">
            <!-- Προσθέστε τα πεδία για την παράδοση και την πληρωμή εδώ -->
            <button type="submit" name="complete_order" class="btn complete-order-btn">Ολοκλήρωση Παραγγελίας</button>
        </form>
            </div>
    </form>
</div>
</body>
<script>
function showCardForm() {
    document.getElementById("cardForm").style.display = "block";
}

function hideCardForm() {
    document.getElementById("cardForm").style.display = "none";
}

function validateCardNumber() {
    var cardNumber = document.getElementById("cardNumber").value;
    var cardNumberCheck = document.getElementById("cardNumberCheck");
    var cardNumberError = document.getElementById("cardNumberError");
    if (cardNumber.length === 16) {
        cardNumberCheck.classList.add("visible");
        cardNumberError.classList.remove("visible");
        document.getElementById("cardNumber").classList.remove("invalid");
    } else {
        cardNumberCheck.classList.remove("visible");
        cardNumberError.classList.add("visible");
        document.getElementById("cardNumber").classList.add("invalid");
    }
}

function validateCardName() {
    var cardName = document.getElementById("cardName").value;
    var cardNameCheck = document.getElementById("cardNameCheck");
    if (cardName.length > 0) {
        cardNameCheck.classList.add("visible");
        document.getElementById("cardName").classList.remove("invalid");
    } else {
        cardNameCheck.classList.remove("visible");
        document.getElementById("cardName").classList.add("invalid");
    }
}

function validateExpiryDate() {
    var expiryDate = document.getElementById("expiryDate").value;
    var expiryDateCheck = document.getElementById("expiryDateCheck");
    var expiryDateError = document.getElementById("expiryDateError");
    var regex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (regex.test(expiryDate)) {
        expiryDateCheck.classList.add("visible");
        expiryDateError.classList.remove("visible");
        document.getElementById("expiryDate").classList.remove("invalid");
    } else {
        expiryDateCheck.classList.remove("visible");
        expiryDateError.classList.add("visible");
        document.getElementById("expiryDate").classList.add("invalid");
    }
}

function validateCVV() {
    var cvv = document.getElementById("cvv").value;
    var cvvCheck = document.getElementById("cvvCheck");
    var cvvError = document.getElementById("cvvError");
    if (cvv.length === 3) {
        cvvCheck.classList.add("visible");
        cvvError.classList.remove("visible");
        document.getElementById("cvv").classList.remove("invalid");
    } else {
        cvvCheck.classList.remove("visible");
        cvvError.classList.add("visible");
        document.getElementById("cvv").classList.add("invalid");
    }
}

function validateForm() {
    var cardNumber = document.getElementById("cardNumber").value;
    var cardName = document.getElementById("cardName").value;
    var expiryDate = document.getElementById("expiryDate").value;
    var cvv = document.getElementById("cvv").value;
    var cardErrorMessage = document.getElementById("cardErrorMessage");

    if (document.querySelector('input[name="payment_method"]:checked').value === 'card') {
        var isValid = true;

        if (cardNumber.length !== 16) {
            document.getElementById("cardNumber").classList.add("invalid");
            document.getElementById("cardNumberError").classList.add("visible");
            isValid = false;
        } else {
            document.getElementById("cardNumber").classList.remove("invalid");
            document.getElementById("cardNumberError").classList.remove("visible");
        }

        if (cardName.length === 0) {
            document.getElementById("cardName").classList.add("invalid");
            isValid = false;
        } else {
            document.getElementById("cardName").classList.remove("invalid");
        }

        var regex = /^(0[1-9]|1[0-2])\/\d{2}$/;
        if (!regex.test(expiryDate)) {
            document.getElementById("expiryDate").classList.add("invalid");
            document.getElementById("expiryDateError").classList.add("visible");
            isValid = false;
        } else {
            document.getElementById("expiryDate").classList.remove("invalid");
            document.getElementById("expiryDateError").classList.remove("visible");
        }

        if (cvv.length !== 3) {
            document.getElementById("cvv").classList.add("invalid");
            document.getElementById("cvvError").classList.add("visible");
            isValid = false;
        } else {
            document.getElementById("cvv").classList.remove("invalid");
            document.getElementById("cvvError").classList.remove("visible");
        }

        if (!isValid) {
            cardErrorMessage.classList.add("visible");
            return false;
        } else {
            cardErrorMessage.classList.remove("visible");
        }
    }
    return true;
}
</script>
</html>
<?php
include 'footer.php';
?>