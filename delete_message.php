<?php
session_start();
include 'cartDB_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Δεν έχετε συνδεθεί."]);
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;
$is_admin = isset($_SESSION['admin_id']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Μη έγκυρη μέθοδος αιτήματος."]);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

error_log("DELETE REQUEST: " . json_encode($data)); // DEBUGGING
if (!isset($data['id']) || !isset($data['category'])) {
    echo json_encode(["success" => false, "message" => "Μη έγκυρο αίτημα."]);
    exit();
}

$message_id = intval($data['id']);
$category = $data['category'];

// **LOG: Εμφάνιση ID και κατηγορίας μηνύματος**
error_log("Προσπάθεια διαγραφής μηνύματος με ID: " . $message_id . " και κατηγορία: " . $category);

if ($category === 'replies') {
    // **Βήμα 1: Διαγραφή της απάντησης από τον πίνακα message_replies**
    $stmt = $conn->prepare("DELETE FROM message_replies WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        error_log("Η απάντηση διαγράφηκε επιτυχώς. Ελέγχουμε αν το αρχικό μήνυμα πρέπει να διαγραφεί...");
        
        // **Βήμα 2: Εύρεση του αρχικού μηνύματος**
        $stmt = $conn->prepare("SELECT message_id FROM message_replies WHERE id = ?");
        $stmt->bind_param("i", $message_id);
        $stmt->execute();
        $stmt->bind_result($original_message_id);
        $stmt->fetch();
        $stmt->close();

        if (!empty($original_message_id)) {
            // **Βήμα 3: Έλεγχος αν υπάρχουν άλλες απαντήσεις για το ίδιο μήνυμα**
            $stmt = $conn->prepare("SELECT COUNT(*) FROM message_replies WHERE message_id = ?");
            $stmt->bind_param("i", $original_message_id);
            $stmt->execute();
            $stmt->bind_result($reply_count);
            $stmt->fetch();
            $stmt->close();

            if ($reply_count == 0) {
                // **Βήμα 4: Αν δεν υπάρχουν άλλες απαντήσεις, διαγράφουμε και το αρχικό μήνυμα**
                $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
                $stmt->bind_param("i", $original_message_id);
                $stmt->execute();
                $stmt->close();

                error_log("Διαγράφηκε επίσης και το αρχικό μήνυμα με ID: " . $original_message_id);
            }
        }
        echo json_encode(["success" => true, "message" => "Η απάντηση διαγράφηκε επιτυχώς."]);
    } else {
        echo json_encode(["success" => false, "message" => "Αποτυχία διαγραφής απάντησης."]);
    }
    exit();
}

// **Διαγραφή απλού μηνύματος του χρήστη (χωρίς απαντήσεις)**
if ($category === 'sent') {
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $message_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Το μήνυμα διαγράφηκε επιτυχώς."]);
    } else {
        echo json_encode(["success" => false, "message" => "Το μήνυμα δεν βρέθηκε ή δεν μπορείτε να το διαγράψετε."]);
    }
    $stmt->close();
    exit();
}
// **Διαγραφή μηνύματος από τον διαχειριστή**
if ($is_admin) {
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Το μήνυμα διαγράφηκε επιτυχώς."]);
    } else {
        echo json_encode(["success" => false, "message" => "Αποτυχία διαγραφής μηνύματος."]);
    }
    $stmt->close();
    exit();
}

echo json_encode(["success" => false, "message" => "Μη υποστηριζόμενη κατηγορία μηνυμάτων."]);
$conn->close();
exit();
