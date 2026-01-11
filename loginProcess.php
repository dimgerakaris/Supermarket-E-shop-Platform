<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'cartDB_connection.php';


// Έλεγχος αν υποβλήθηκε η φόρμα
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Χωρίς escaping γιατί συγκρίνεται hashed

    // Αναζήτηση χρήστη με το email
    $sql = "SELECT id, username, password, role FROM registration WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Έλεγχος αν το password είναι σωστό
        if (password_verify($password, $user['password'])) {
            // Αποθήκευση πληροφοριών χρήστη στο session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $email;
            $_SESSION['role'] = $user['role']; // Αποθήκευση του ρόλου (user/admin)

            // Ανακατεύθυνση ανάλογα με τον ρόλο
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Λάθος password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Το email δεν βρέθηκε.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
