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
    echo "<p>Πρέπει να συνδεθείτε για να δείτε τα μηνύματά σας.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

$messages = [
    'system' => [],  
    'sent' => [],     
    'replies' => []   
];

// Υπολογισμός του count των αδιάβαστων μηνυμάτων
$unread_count = 0;

// Μηνύματα από το σύστημα
$sql = "SELECT id, user_id, 'Μήνυμα από το σύστημα' AS subject, message, created_at, is_read, 'system' AS type 
        FROM messages WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $messages['system'][] = $row;
    if ($row['is_read'] == 0) {
        $unread_count++;
    }
}
$stmt->close();

// Μηνύματα που έχει στείλει ο χρήστης
$sql = "SELECT id, user_id, 'Μήνυμα που έχετε στείλει' AS subject, message, submitted_at AS created_at, 1 AS is_read, 'sent' AS type 
        FROM contact_messages WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $messages['sent'][] = $row;
}
$stmt->close();

// Απαντήσεις του διαχειριστή
$sql = "SELECT mr.id, mr.message_id, 'Απάντηση από διαχειριστή' AS subject, 
               mr.reply_text AS message, mr.replied_at AS created_at, 
               mr.is_read, 'replies' AS type, 
               cm.message AS original_message
        FROM message_replies mr
        JOIN contact_messages cm ON mr.message_id = cm.id
        WHERE cm.user_id = ?
        ORDER BY mr.replied_at DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $row['original_message'] = htmlspecialchars($row['original_message']);
    $messages['replies'][] = $row;
    if ($row['is_read'] == 0) {
        $unread_count++;
    }
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Τα Μηνύματά σας</title>
</head>
<body>
    <div class="content">
        <div class="messages-container">
            <h2 class="messages-title">Τα Μηνύματά σας</h2>

            <div class="messages-layout">
                <!-- ΛΙΣΤΑ ΜΗΝΥΜΑΤΩΝ -->
                <div class="messages-list-container">
                    <?php foreach ($messages as $category => $msgs): ?>
                        <div class="<?= $category ?>-messages category-container">
                            <h3 class="category-title" onclick="toggleAccordion('<?= $category ?>')">
                                <?= $category === 'system' ? '<i class="fas fa-cogs"></i> Μηνύματα από το σύστημα' : ($category === 'sent' ? '<i class="fas fa-paper-plane"></i> Μηνύματα που έχετε στείλει' : '<i class="fas fa-reply"></i> Απαντήσεις από τον διαχειριστή') ?>
                            </h3>
                            <hr class="category-divider">
                            
                            <div class="messages-content <?= $category ?>-content" style="display: <?= $category === 'sent' ? 'none' : 'block' ?>;">
                                <?php if (!empty($msgs)): ?>
                                    <?php foreach ($msgs as $message): ?>
                                        <?php 
                                            $message_class = $message['is_read'] ? 'message-read' : 'message-unread';
                                            $icon = $message['is_read'] ? 'fas fa-envelope-open' : 'fas fa-envelope';
                                        ?>
                                        <div class="message-item <?= $message_class ?>" data-id="<?= $message['id'] ?>" 
                                            data-original-message="<?= isset($message['original_message']) ? htmlspecialchars($message['original_message']) : '' ?>">
                                            <div class="message-text" style="display: none;"><?= htmlspecialchars($message['message']) ?></div>
                                            <div class="message-header">
                                                <?php if ($category !== 'sent'): ?>
                                                    <i class="<?= $icon ?>"></i>
                                                <?php endif; ?>
                                                <h4>Μήνυμα</h4>
                                                <button class="delete-button" onclick="deleteMessage('<?= $message['id'] ?>', '<?= $category ?>')">X</button>
                                            </div>
                                            <div class="message-date-time">
                                                <small>Ημερομηνία: <?= date("d/m/Y H:i:s", strtotime($message['created_at'])) ?></small>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="empty-category">Δεν υπάρχουν μηνύματα σε αυτή την κατηγορία.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ΠΕΡΙΟΧΗ ΑΝΑΓΝΩΣΗΣ ΜΗΝΥΜΑΤΟΣ -->
                <div class="message-body">
                    <div id="message-content">
                        <p>Επιλέξτε ένα μήνυμα για να το διαβάσετε.</p>
                    </div>
                    <div id="reply-section">
                        <textarea id="reply-text" placeholder="Γράψτε την απάντησή σας εδώ..." disabled></textarea>
                        <button id="reply-button" onclick="sendReply()" disabled>Αποστολή Απάντησης</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <!-- Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Είστε σίγουρος/η ότι θέλετε να διαγράψετε αυτό το μήνυμα;</p>
            <button id="confirmDelete" class="confirm-button">Ναι</button>
            <button id="cancelDelete" class="cancel-button">Όχι</button>
        </div>
    </div>

    <script>
    let currentMessageId = null;
    let currentCategory = null;

    // Get the modal
    const modal = document.getElementById("deleteModal");

    // Get the <span> element that closes the modal
    const span = document.getElementsByClassName("close")[0];

    // Get the confirm and cancel buttons
    const confirmDelete = document.getElementById("confirmDelete");
    const cancelDelete = document.getElementById("cancelDelete");

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks on the cancel button, close the modal
    cancelDelete.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function toggleAccordion(category) {
        const content = document.querySelector(`.${category}-content`);
        if (content.style.display === "none") {
            content.style.display = "block";
        } else {
            content.style.display = "none";
        }
    }

function showMessage(id, category) {
    console.log("📩 Επιλέχθηκε μήνυμα με ID:", id, "Κατηγορία:", category);
    
    currentMessageId = id;
    currentCategory = category;
    let messageItem = document.querySelector(`.message-item[data-id='${id}']`);

    if (!messageItem) {
        console.error("🔴 Το μήνυμα δεν βρέθηκε:", id);
        document.getElementById('message-content').innerHTML = '<p>Το μήνυμα δεν βρέθηκε ή έχει διαγραφεί.</p>';
        return;
    }

    let messageText = messageItem.querySelector(".message-text").innerText || "Δεν υπάρχει περιεχόμενο";
    let messageDate = messageItem.querySelector(".message-date-time small").innerText.replace("Ημερομηνία: ", "");

    let messageContent = `<h3>Μήνυμα</h3><p>${messageText}</p><small>Ημερομηνία: ${messageDate}</small>`;

    // **Ειδικός χειρισμός για απαντήσεις του διαχειριστή**
    if (category === "replies") {
        let originalMessage = messageItem.getAttribute("data-original-message");
        if (originalMessage) {
            messageContent = `<h4>Αρχικό Μήνυμα</h4><p>${originalMessage}</p><hr>` + messageContent;
        }
    }

    document.getElementById("message-content").innerHTML = messageContent;

    // **Επισήμανση ως διαβασμένο και αλλαγή εικονιδίου φακέλου**
    if (messageItem.classList.contains("message-unread")) {
        markAsRead(id, category, messageItem);
    }
}


document.addEventListener("DOMContentLoaded", function () {
    // Επιλογή όλων των μηνυμάτων
    const messages = document.querySelectorAll(".message-item");

    messages.forEach(message => {
        message.addEventListener("click", function () {
            const messageId = this.getAttribute("data-id");
            const messageText = this.querySelector(".message-text") ? this.querySelector(".message-text").innerText : "Δεν υπάρχει περιεχόμενο";
            const messageDate = this.querySelector(".message-date-time small") ? this.querySelector(".message-date-time small").innerText.replace("Ημερομηνία: ", "") : "Άγνωστη ημερομηνία";

            // Προβολή του περιεχομένου στο message-content
            document.getElementById("message-content").innerHTML = `
                <h3>Μήνυμα</h3>
                <p>${messageText}</p>
                <small>Ημερομηνία: ${messageDate}</small>
            `;

            // Ενημέρωση ότι το μήνυμα έχει διαβαστεί (αν χρειάζεται)
            if (this.classList.contains("message-unread")) {
                markAsRead(messageId);
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Επιλογή όλων των μηνυμάτων
    const messages = document.querySelectorAll(".message-item");

    messages.forEach(message => {
        message.addEventListener("click", function () {
            const messageId = this.getAttribute("data-id");
            const category = this.closest(".category-container").classList[0].replace("-messages", ""); // Παίρνει το category
            const messageText = this.querySelector(".message-text") ? this.querySelector(".message-text").innerText : "Δεν υπάρχει περιεχόμενο";
            const messageDate = this.querySelector(".message-date-time small") ? this.querySelector(".message-date-time small").innerText.replace("Ημερομηνία: ", "") : "Άγνωστη ημερομηνία";

            let messageContent = `<h3>Μήνυμα</h3><p>${messageText}</p><small>Ημερομηνία: ${messageDate}</small>`;

            // Αν είναι απάντηση διαχειριστή, φέρε και το αρχικό μήνυμα του χρήστη
            if (category === "replies") {
                fetch('get_original_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: messageId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageContent += `<hr><h4>Αρχικό Μήνυμα</h4><p>${data.original_message}</p>`;
                    }
                    document.getElementById("message-content").innerHTML = messageContent;
                })
                .catch(error => {
                    console.error('Σφάλμα κατά τη φόρτωση του αρχικού μηνύματος:', error);
                    document.getElementById("message-content").innerHTML = messageContent;
                });
            } else {
                document.getElementById("message-content").innerHTML = messageContent;
            }

            // Αν είναι απάντηση από διαχειριστή, σημείωσε το ως διαβασμένο
            if (this.classList.contains("message-unread") && category === "replies") {
                markAsRead(messageId, category, this);
            }
        });
    });
});

// Σημείωση μηνύματος ως διαβασμένο
function markAsRead(id, category, element) {
    fetch('mark_as_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, category: category })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (element) {
                element.classList.remove('message-unread');
                element.classList.add('message-read');
                const icon = element.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-envelope');
                    icon.classList.add('fa-envelope-open');
                }
            }
            updateUnreadCount();
        } else {
            console.error('Αποτυχία επισήμανσης ως διαβασμένο:', data.message);
        }
    })
    .catch(error => console.error('Σφάλμα:', error));
}

// Μείωση του count των αδιάβαστων μηνυμάτων
function updateUnreadCount() {
    const unreadMessages = document.querySelectorAll('.message-unread').length;
    const messagesCountElement = document.getElementById('messages-count');
    if (messagesCountElement) {
        messagesCountElement.textContent = unreadMessages;
    }
}

    function sendReply() {
        const replyText = document.getElementById('reply-text').value.trim();
        if (!replyText) {
            alert('Παρακαλώ γράψτε μια απάντηση.');
            return;
        }

        fetch('reply_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: currentMessageId, reply_text: replyText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Η απάντηση στάλθηκε επιτυχώς.');
                document.getElementById('reply-text').value = '';
                location.reload();
            } else {
                alert('Η αποστολή απέτυχε: ' + data.message);
                console.error('Server error:', data.error);
            }
        })
        .catch(error => {
            console.error('Σφάλμα:', error);
            alert('Σφάλμα κατά την αποστολή της απάντησης.');
        });
    }

    function deleteMessage(id, category) {
        if (!confirm("Είστε σίγουρος/η ότι θέλετε να διαγράψετε αυτό το μήνυμα;")) {
            return;
        }

        console.log("Προσπάθεια διαγραφής μηνύματος με ID:", id, "και κατηγορία:", category);

        fetch('delete_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id, category: category })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Απάντηση από το delete_message.php:", data);

            if (data.success) {
                alert(data.message);
                document.querySelector(`.message-item[data-id="${id}"]`).remove();
            } else {
                alert("Αποτυχία διαγραφής: " + data.message);
            }
        })
        .catch(error => {
            console.error('Σφάλμα:', error);
            alert('Σφάλμα στη διαγραφή του μηνύματος.');
        });
    }

    </script>
</body>
</html>
