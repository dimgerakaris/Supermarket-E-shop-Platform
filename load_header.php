<?php
// Ξεκινάμε το session αν δεν έχει ξεκινήσει
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Επιλογή κατάλληλου header ανάλογα με τον ρόλο του χρήστη
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'header-admin.php';
} else {
    include 'header.php';
}
?>
