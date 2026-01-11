<?php
// Εμφάνιση σφαλμάτων για debugging (μόνο για τοπική ανάπτυξη, όχι σε παραγωγή)
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'cartDB_connection.php';

// Χειρισμός της εγγραφής
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Λήψη δεδομένων από τη φόρμα
    $fName = $conn->real_escape_string($_POST['fName']);
    $lName = $conn->real_escape_string($_POST['lName']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $repeat = $conn->real_escape_string($_POST['repeat']);
    $number = $conn->real_escape_string($_POST['number']);
    $odos = $conn->real_escape_string($_POST['odos']);
    $polh = $conn->real_escape_string($_POST['polh']);
    $tk = $conn->real_escape_string($_POST['tk']);

    // Έλεγχος αν τα passwords ταιριάζουν
    if ($password !== $repeat) {
        echo "<p style='color: red;'>Τα πεδία 'Password' και 'Repeat Password' δεν ταιριάζουν.</p>";
        exit();
    }

    // Hash του password για ασφάλεια
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Εισαγωγή δεδομένων στη βάση
    $sql = "INSERT INTO registration (fName, lName, username, email, password, number, odos, polh, tk)
            VALUES ('$fName', '$lName', '$username', '$email', '$hashed_password', '$number', '$odos', '$polh', '$tk')";

    if ($conn->query($sql) === TRUE) {
        // Ανακατεύθυνση σε σελίδα επιτυχίας
        header("Location: success.php");
        exit();
    } else {
        echo "Σφάλμα κατά την εισαγωγή δεδομένων: " . $conn->error;
    }
}

// Κλείσιμο σύνδεσης
$conn->close();
?>
