<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1; 
}
include 'header-admin.php'; // Admin header
include 'cartDB_connection.php'; // Database connection file

$conn = new mysqli($host, $user, $password, $database);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Υπολογισμός του count των αδιάβαστων μηνυμάτων
$unread_count = 0;
$sql_unread = "SELECT COUNT(*) AS unread_count FROM contact_messages WHERE is_read = 0";
$result_unread = $conn->query($sql_unread);
if ($result_unread) {
    $row_unread = $result_unread->fetch_assoc();
    $unread_count = $row_unread['unread_count'] ?? 0;
}

// Φιλτράρισμα δεδομένων
$filter = "";

// Αναζήτηση με βάση το ID
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = intval($_GET['id']);
    $filter .= " AND contact_messages.id = $id";
}

// Αναζήτηση με βάση το username
if (isset($_GET['username']) && $_GET['username'] !== '') {
    $username = $conn->real_escape_string($_GET['username']);
    $filter .= " AND registration.username LIKE '%$username%'";
}

// Αναζήτηση με βάση το email
if (isset($_GET['email']) && $_GET['email'] !== '') {
    $email = $conn->real_escape_string($_GET['email']);
    $filter .= " AND contact_messages.email LIKE '%$email%'";
}

// Αναζήτηση με βάση την ημερομηνία
if (isset($_GET['date']) && $_GET['date'] !== '') {
    $date = $conn->real_escape_string($_GET['date']);
    $filter .= " AND DATE(contact_messages.submitted_at) = '$date'";
}

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$records_per_page = isset($_GET['records_per_page']) ? max(1, intval($_GET['records_per_page'])) : 10;
$offset = ($page - 1) * $records_per_page;

// Υπολογισμός συνολικών μηνυμάτων
$sql_total = "SELECT COUNT(*) AS total FROM contact_messages 
              JOIN registration ON contact_messages.user_id = registration.id 
              WHERE 1=1 $filter";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_messages = $row_total['total'] ?? 0;
$total_pages = max(1, ceil($total_messages / $records_per_page));

// Ανάκτηση των μηνυμάτων με pagination
$sql = "SELECT contact_messages.*, registration.username, registration.email 
    FROM contact_messages 
    JOIN registration ON contact_messages.user_id = registration.id
    WHERE 1=1 $filter 
    ORDER BY contact_messages.submitted_at DESC 
    LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

$messages = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Διαχείριση Μηνυμάτων Πελατών</title>
    <link rel="stylesheet" href="css/admin-messages.css">
    <link rel="stylesheet" href="css/orders.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .message-unread {
            background-color: #FFD580 !important; /* Λιγότερο έντονο πορτοκαλί χρώμα για αδιάβαστα μηνύματα */
        }
        .message-read {
            background-color: #FFFFFF; /* Λευκό χρώμα για διαβασμένα μηνύματα */
        }
        .messages-container {
            width: 80%;
            margin: 0 auto;
        }
        .messages-title {
            text-align: center;
            margin: 20px 0;
        }
        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .filters label {
            margin-right: 10px;
        }
        .filters input {
            margin-right: 20px;
        }
        .messages-table {
            width: 100%;
            border-collapse: collapse;
        }
        .messages-table th, .messages-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .messages-table th {
            /* background-color: #f2f2f2; */
            text-align: left;
        }
        .messages-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #000;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
    </style>
</head>
<body>

    <div class="messages-container">
        <h2 class="messages-title">Διαχείριση Μηνυμάτων Πελατών</h2>

        <!-- Φίλτρα αναζήτησης -->
        <form method="GET" action="">
            <div class="filters">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">

                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">

                <label for="date">Ημερομηνία:</label>
                <input type="date" name="date" id="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">

                <button type="submit">Αναζήτηση</button>
            </div>
        </form>

        <!-- Πίνακας Μηνυμάτων -->
        <table class="messages-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Μήνυμα</th>
                    <th>Ημερομηνία</th>
                    <th>Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr class="<?php echo $message['is_read'] == 0 ? 'message-unread' : 'message-read'; ?>" data-id="<?php echo $message['id']; ?>">
                        <td><?php echo $message['id']; ?></td>
                        <td><?php echo htmlspecialchars($message['username']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td title="<?php echo htmlspecialchars($message['message']); ?>">
                            <?php echo substr(htmlspecialchars($message['message']), 0, 50) . (strlen($message['message']) > 50 ? '...' : ''); ?>
                        </td>
                        <td><?php echo (new DateTime($message['submitted_at']))->format('d/m/Y H:i:s'); ?></td>
                        <td>
                            <a href="javascript:void(0);" class="reply-message" title="Απάντηση"
                                onclick="openReplyModal(
                                    <?php echo $message['id']; ?>, 
                                    '<?php echo addslashes(htmlspecialchars($message['username'], ENT_QUOTES, 'UTF-8')); ?>', 
                                    '<?php echo addslashes(htmlspecialchars($message['email'], ENT_QUOTES, 'UTF-8')); ?>',
                                    '<?php echo $message['user_id']; ?>',
                                    `<?php echo nl2br(addslashes(htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8'))); ?>`, 
                                    '<?php echo (new DateTime($message['submitted_at']))->format('d/m/Y H:i:s'); ?>')">
                                <i class="fas fa-reply"></i>
                            </a>
                            <a href="javascript:void(0);" class="delete-message" onclick="deleteMessage(<?php echo $message['id']; ?>, 'contact_messages')" title="Διαγραφή">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Σελιδοποίηση -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <p class="total-messages">Σύνολο Μηνυμάτων: <?php echo $total_messages; ?></p>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <!-- Modal για την απάντηση μηνυμάτων -->
    <div id="replyModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="replyModalTitle">Απάντηση Μηνύματος #<span id="messageId"></span></h2>
            <div class="message-details">
                <p><strong>Username:</strong> <span id="messageUsername"></span></p>
                <p><strong>Email:</strong> <span id="messageEmail"></span></p>
                <p><strong>Ημερομηνία:</strong> <span id="messageDate"></span></p>
            </div>
            <div class="message-content">
                <p><strong>Μήνυμα:</strong></p>
                <p id="messageContent"></p>
            </div>
            <div class="replies-content">
                <h3>Απαντήσεις:</h3>
                <div id="repliesList"></div>
            </div>
            <form id="replyForm" method="POST" action="save_reply.php">
                <input type="hidden" name="message_id" id="message_id">
                <input type="hidden" name="user_id" id="user_id">
                <input type="hidden" name="email" id="email">
                <label for="reply" class="reply-button">Απάντηση:</label>
                <textarea name="reply" id="reply" rows="5" required></textarea>
                <button type="submit">Αποστολή</button>
            </form>

        </div>
    </div>

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
            document.getElementById('messageContent').innerHTML = message.replace(/\n/g, "<br>");
            document.getElementById('messageDate').innerText = date;
            
            // Φόρτωση απαντήσεων
            loadReplies(messageId);

            // Σήμανση του μηνύματος ως διαβασμένο
            markAsRead(messageId, 'messages');

            document.getElementById('replyModal').style.display = 'block';
        }

        // Κλείσιμο του modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('replyModal').style.display = 'none';
        }

        // Κλείσιμο του modal όταν ο χρήστης κάνει κλικ έξω από αυτό
        window.onclick = function(event) {
            if (event.target == document.getElementById('replyModal')) {
                document.getElementById('replyModal').style.display = 'none';
            }
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

        // Φόρτωση απαντήσεων
        function loadReplies(messageId) {
            fetch('get_replies.php?message_id=' + messageId)
                .then(response => response.json())
                .then(data => {
                    const repliesList = document.getElementById('repliesList');
                    repliesList.innerHTML = '';
                    if (data.replies && data.replies.length > 0) {
                        data.replies.forEach(reply => {
                            const replyElement = document.createElement('div');
                            replyElement.classList.add('reply-item');
                            replyElement.innerHTML = `
                                <p><strong>Απάντηση:</strong> ${reply.reply_text}</p>
                                <p><small>Ημερομηνία: ${new Date(reply.replied_at).toLocaleString()}</small></p>
                            `;
                            repliesList.appendChild(replyElement);
                        });
                    } else {
                        repliesList.innerHTML = '<p>Δεν υπάρχουν απαντήσεις.</p>';
                    }
                })
                .catch(error => console.error('Σφάλμα:', error));
        }

        // Σήμανση του μηνύματος ως διαβασμένο
        function markAsRead(messageId, category) {
            fetch('mark_as_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: messageId, category: category })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messageRow = document.querySelector(`tr[data-id="${messageId}"]`);
                    if (messageRow) {
                        messageRow.classList.remove('message-unread');
                        messageRow.classList.add('message-read');
                    }
                    updateUnreadCount();
                } else {
                    console.error('Failed to mark message as read:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Ενημέρωση του counter των αδιάβαστων μηνυμάτων
        function updateUnreadCount() {
            const unreadMessages = document.querySelectorAll('.message-unread').length;
            const unreadCountElement = document.getElementById('messages-count');
            if (unreadCountElement) {
                unreadCountElement.textContent = unreadMessages;
            }
        }

        function deleteMessage(messageId) {
            if (!confirm("Είστε σίγουρος/η ότι θέλετε να διαγράψετε αυτό το μήνυμα;")) {
                return;
            }

            fetch('delete_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: messageId, category: "contact_messages" }) // Ενημέρωσε το category
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Δες τι επιστρέφει ο server
                if (data.success) {
                    alert(data.message);
                    document.querySelector(`tr[data-id="${messageId}"]`).remove();
                } else {
                    alert("Αποτυχία διαγραφής μηνύματος: " + data.message);
                }
            })
            .catch(error => console.error('Σφάλμα:', error));
        }


    </script>
</body>
</html>