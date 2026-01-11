<?php
ob_start();
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
include 'cartDB_connection.php';

// Ορισμός κωδικοποίησης χαρακτήρων
$conn->set_charset("utf8mb4");

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    echo "<p>Παρακαλώ συνδεθείτε πρώτα για να επεξεργαστείτε το προφίλ σας.</p>";
    echo "<a href='login.php'>Login</a>";
    exit();
}

// Ανάκτηση στοιχείων χρήστη από τη βάση
$user_id = $_SESSION['user_id'];
$sql = "SELECT fName, lName, username, email, number, odos, number, polh, tk FROM registration WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p>Σφάλμα: Δεν βρέθηκαν στοιχεία χρήστη.</p>";
    exit();
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName = $conn->real_escape_string($_POST['fName'] ?? '');
    $lName = $conn->real_escape_string($_POST['lName'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $odos = $conn->real_escape_string($_POST['odos'] ?? '');
    $number = $conn->real_escape_string($_POST['number'] ?? '');
    $polh = $conn->real_escape_string($_POST['polh'] ?? '');
    $tk = $conn->real_escape_string($_POST['tk'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($password)) {
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE registration SET fName = '$fName', lName = '$lName', email = '$email', odos = '$odos', number = '$number', polh = '$polh', tk = '$tk', password = '$hashed_password' WHERE id = $user_id";

            if ($conn->query($update_sql) === TRUE) {
                // Message for password change
                $message = "Ο κωδικός σας άλλαξε επιτυχώς! Θα μεταφερθείτε στη σελίδα σύνδεσης.";           
                session_destroy();
                //Ανακατεύθυνση μετά από 3 δευτερόλεπτα
                echo "<meta http-equiv='refresh' content='3;url=login.php'>";
            } else {
                $message = "Σφάλμα κατά την ενημέρωση: " . $conn->error;
            }
        } else {
            // Αν οι κωδικοί δεν ταιριάζουν
            $message = "Οι κωδικοί δεν ταιριάζουν. Προσπαθήστε ξανά.";
        }
    } else {
        // Ενημέρωση στοιχείων χωρίς αλλαγή κωδικού
        $update_sql = "UPDATE registration SET fName = '$fName', lName = '$lName', email = '$email', odos = '$odos', number = '$number', polh = '$polh', tk = '$tk' WHERE id = $user_id";

        if ($conn->query($update_sql) === TRUE) {
            $message = "Τα στοιχεία σας ενημερώθηκαν με επιτυχία!";
            // Ανακατεύθυνση στη σελίδα προφίλ μετά από 3 δευτερόλεπτα
            echo "<meta http-equiv='refresh' content='3;url=profile.php'>";
        } else {
            $message = "Σφάλμα κατά την ενημέρωση: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επεξεργασία Προφίλ</title>
    <link rel="stylesheet" type="text/css" href="css/myCSS.css"/>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" type="text/css" href="css/subCategoryCSS.css"/>
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <div class="profile-edit-container">
        <h1 class="profile-edit-title">Επεξεργασία Προφίλ</h1>
        <?php if (!empty($message)) : ?>
            <div class="success-message">
                <p><?php echo $message; ?></p>
                <p>Ανακατευθύνεστε στη σελίδα του Προφίλ σας...</p>
            </div>
        <?php endif; ?>
        <form method="POST" class="profile-edit-form">
            <div class="form-section">
                <h2>Στοιχεία Χρήστη</h2>
                <div class="form-group">
                    <label for="fName">Όνομα:</label>
                    <input type="text" name="fName" id="fName" value="<?php echo htmlspecialchars($user['fName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lName">Επώνυμο:</label>
                    <input type="text" name="lName" id="lName" value="<?php echo htmlspecialchars($user['lName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
            </div>
            <div class="form-section">
                <h2>Στοιχεία Διεύθυνσης</h2>
                <div class="form-group">
                    <label for="odos">Οδός:</label>
                    <input type="text" name="odos" id="odos" value="<?php echo htmlspecialchars($user['odos']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="number">Αριθμός:</label>
                    <input type="text" name="number" id="number" value="<?php echo htmlspecialchars($user['number']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="polh">Πόλη:</label>
                    <input type="text" name="polh" id="polh" value="<?php echo htmlspecialchars($user['polh']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="tk">Τ.Κ.:</label>
                    <input type="text" name="tk" id="tk" value="<?php echo htmlspecialchars($user['tk']); ?>" required>
                </div>
            </div>
            <div class="form-section">
                <h2>Στοιχεία Επικοινωνίας</h2>
                <div class="form-group">
                    <label for="phone">Τηλέφωνο:</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['number']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>
            <div class="form-section">
                <h2>Αλλαγή Κωδικού</h2>
                <div class="form-group">
                    <label for="password">Νέος Κωδικός:</label>
                    <input type="password" name="password" id="password" placeholder="Εισάγετε νέο κωδικό" class="password-input">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Επιβεβαίωση Κωδικού:</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Επιβεβαιώστε τον νέο κωδικό" class="password-input">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save">Αποθήκευση Αλλαγών</button>
                <a href="profile.php" class="btn-cancel">Ακύρωση</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php
include 'footer.php';
ob_end_flush();
?>