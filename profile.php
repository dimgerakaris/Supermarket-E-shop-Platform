<?php
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
include 'cartDB_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<p>Πρέπει να συνδεθείτε για να δείτε το προφίλ σας.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Ανάκτηση στοιχείων χρήστη από τη βάση
$sql = "SELECT username, email, fName, lName, odos, number, polh, tk FROM registration WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p>Σφάλμα: Δεν βρέθηκαν στοιχεία χρήστη.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/profile.css">
    <title>Το Προφίλ Μου</title>
</head>
<body>
    <div class="content">
        <div class="profile-container">
            <h1 class="profile-title">Το Προφίλ Μου</h1>
            <div class="profile-details">
                <div class="profile-section">
                    <h2>Στοιχεία Χρήστη</h2>
                    <div class="profile-box">
                        <strong>Όνομα:</strong>
                        <p><?php echo htmlspecialchars($user['fName']); ?></p>
                    </div>
                    <div class="profile-box">
                        <strong>Επώνυμο:</strong>
                        <p><?php echo htmlspecialchars($user['lName']); ?></p>
                    </div>
                    <div class="profile-box">
                        <strong>Username:</strong>
                        <p><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>
                </div>
                <div class="profile-section">
                    <h2>Στοιχεία Διεύθυνσης</h2>
                    <div class="profile-box">
                        <strong>Οδός:</strong>
                        <p><?php echo htmlspecialchars($user['odos']); ?></p>
                    </div>
                    
                    <div class="profile-box">
                        <strong>Πόλη:</strong>
                        <p><?php echo htmlspecialchars($user['polh']); ?></p>
                    </div>
                    <div class="profile-box">
                        <strong>Τ.Κ.:</strong>
                        <p><?php echo htmlspecialchars($user['tk']); ?></p>
                    </div>
                </div>
                <div class="profile-section">
                    <h2>Στοιχεία Επικοινωνίας</h2>
                    <div class="profile-box">
                        <strong>Τηλέφωνο Επικοινωνίας:</strong>
                        <p><?php echo htmlspecialchars($user['number']); ?></p>
                    </div>
                    <div class="profile-box">
                        <strong>Email:</strong>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <a href="editProfile.php" class="btn-edit">Επεξεργασία Προφίλ</a>
                <a href="index.php" class="btn-return">Επιστροφή στην Αρχική</a>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>