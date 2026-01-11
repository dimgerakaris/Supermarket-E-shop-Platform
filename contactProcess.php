<?php
include 'cartDB_connection.php';

// Έλεγχος αν η φόρμα υποβλήθηκε
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Ανάκτηση του username και user_id από τον πίνακα registration
    $user_sql = "SELECT id, username FROM registration WHERE email = '$email'";
    $user_result = $conn->query($user_sql);

    if ($user_result === false) {
        echo "<p>Σφάλμα κατά την εκτέλεση του ερωτήματος: " . $conn->error . "</p>";
        exit();
    }

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['id'];
        $username = $user_row['username'];
    } else {
        echo "<p>Δεν βρέθηκε χρήστης με αυτό το email.</p>";
        echo "<a href='contact.php'>Επιστροφή στη Φόρμα Επικοινωνίας</a>";
        exit();
    }

    // Εισαγωγή δεδομένων στη βάση
    $sql = "INSERT INTO contact_messages (user_id, username, email, message) VALUES ('$user_id', '$username', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Ανακατεύθυνση σε νέα σελίδα επιτυχίας
        header("Location: contactSuccess.php");
        exit();
    } else {
        echo "<p>Σφάλμα κατά την αποθήκευση του μηνύματος: " . $conn->error . "</p>";
    }

    $conn->close();
}
?>