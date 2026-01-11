<?php
session_start();
include 'cartDB_connection.php'; // Database connection file

header('Content-Type: application/json'); // Ορισμός του τύπου περιεχομένου ως JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_id = intval($_POST['message_id']);
    $reply_text = $conn->real_escape_string($_POST['reply']);
    $email = $conn->real_escape_string($_POST['email']);
    $user_id = intval($_POST['user_id']);
    
    // DEBUGGING: Δες αν τα δεδομένα λαμβάνονται σωστά
    error_log("Received email: " . $email);
    error_log("Received user_id: " . $user_id);
    
    // Assuming you have admin_id stored in session
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Δεν έχετε συνδεθεί ως διαχειριστής.']);
        exit();
    }
    
    $admin_id = intval($_SESSION['admin_id']);

    $sql = "INSERT INTO message_replies (message_id, admin_id, reply_text, replied_at, email, user_id) VALUES ($message_id, $admin_id, '$reply_text', NOW(), '$email', $user_id)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Η απάντηση αποθηκεύτηκε επιτυχώς.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Σφάλμα κατά την αποθήκευση της απάντησης: ' . $conn->error]);
    }

    $conn->close();
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}
?>

<script>
// Άνοιγμα του modal
function openReplyModal(messageId, username, email, userId, message, date) {
    console.log("Set email value:", email); // DEBUGGING: Δες αν το email περνάει σωστά
    console.log("Set user_id value:", userId); // DEBUGGING: Δες αν το user_id περνάει σωστά

    document.getElementById('message_id').value = messageId;
    document.getElementById('user_id').value = userId;
    document.getElementById('email').value = email;

    document.getElementById('messageId').innerText = messageId;
    document.getElementById('messageUsername').innerText = username;
    document.getElementById('messageEmail').innerText = email; 
    document.getElementById('messageContent').innerText = message;
    document.getElementById('messageDate').innerText = date;
    
    document.getElementById('replyModal').style.display = 'block';
}

// Προσθήκη event listener για το submit της φόρμας
document.getElementById('replyForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Αποφυγή της προεπιλεγμένης υποβολής της φόρμας
    const formData = new FormData(this);
    console.log('Υποβολή φόρμας:', Object.fromEntries(formData.entries())); // Εμφάνιση των δεδομένων της φόρμας στην κονσόλα

    // Υποβολή της φόρμας μέσω AJAX
    fetch('save_reply.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Απάντηση από τον server:', data);
        if (data.status === 'success') {
            // Επανεκκίνηση της σελίδας ή κλείσιμο του modal
            document.getElementById('replyModal').style.display = 'none';
            location.reload();
        } else {
            console.error('Σφάλμα:', data.message);
        }
    })
    .catch(error => console.error('Σφάλμα:', error));
});
</script>