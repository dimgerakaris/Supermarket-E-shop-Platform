<?php
include 'cartDB_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Έλεγχος αν ο χρήστης είναι admin
$is_admin = ($_SESSION['role'] === 'admin');

// Ανάκτηση του user_id από την παράμετρο ή από το session
$user_id = $is_admin && isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'];

// Handle AJAX delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από το JSON body
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['delete_order_id']) || !is_numeric($data['delete_order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Μη έγκυρο ID παραγγελίας!']);
        exit();
    }

    $order_id = intval($data['delete_order_id']);

    if ($is_admin) {
        // Αν είναι admin, μπορεί να διαγράψει οποιαδήποτε παραγγελία
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
    } else {
        // Αν είναι πελάτης, μπορεί να διαγράψει ΜΟΝΟ τις δικές του παραγγελίες
        $sql = "DELETE FROM orders WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $order_id, $user_id);
    }

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Αποτυχία διαγραφής!']);
    }

    $stmt->close();
    $conn->close();
    exit();
}



// Determine sort order
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'id_desc';
switch ($sort_order) {
    case 'id_asc':
        $order_by = 'id ASC';
        break;
    case 'id_desc':
        $order_by = 'id DESC';
        break;
    case 'user_id_asc':
        $order_by = 'id ASC';
    break;
    case 'user_id_desc':
        $order_by = 'id DESC';
    break;
    case 'name_asc':
        $order_by = 'fName ASC, lName ASC';
        break;
    case 'name_desc':
        $order_by = 'fName DESC, lName DESC';
        break;
    case 'date_asc':
        $order_by = 'order_date ASC';
        break;
    case 'date_desc':
        $order_by = 'order_date DESC';
        break;
    case 'total_asc':
        $order_by = 'total_amount ASC';
        break;
    case 'total_desc':
        $order_by = 'total_amount DESC';
        break;
    case 'delivery_asc':
        $order_by = 'delivery_method ASC';
        break;
    case 'delivery_desc':
        $order_by = 'delivery_method DESC';
        break;
    case 'payment_asc':
        $order_by = 'payment_method ASC';
        break;
    case 'payment_desc':
        $order_by = 'payment_method DESC';
        break;
    case 'status_asc':
        $order_by = 'status ASC';
        break;
    case 'status_desc':
        $order_by = 'status DESC';
        break;
    default:
        $order_by = 'id DESC';
        break;
}

// Determine the current page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Determine the number of records per page
$records_per_page = isset($_GET['records_per_page']) ? intval($_GET['records_per_page']) : 5;
if ($records_per_page == 0) {
    $records_per_page = 5;
}
$offset = ($page - 1) * $records_per_page;

// Retrieve all orders for the logged-in user with pagination
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY $order_by LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("iii", $user_id, $records_per_page, $offset);
$stmt->execute();
$orders_result = $stmt->get_result();

// Get the total number of orders for pagination
$sql_total = "SELECT COUNT(id) as total FROM orders WHERE user_id = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("i", $user_id);
$stmt_total->execute();
$stmt_total->bind_result($total_orders);
$stmt_total->fetch();
$total_pages = ceil($total_orders / $records_per_page);

// Dictionary for translations
$translations = [
    'card' => 'Χρεωστική/Πιστωτική Κάρτα',
    'cash' => 'Μετρητά',
    'store_pickup' => 'Παραλαβή από το κατάστημα',
    'home_delivery' => 'Κατ\' οίκον παράδοση'
];

// Handle AJAX request for sorting
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $orders = [];
    while ($order = $orders_result->fetch_assoc()) {
        $order['delivery_method'] = $translations[$order['delivery_method']] ?? htmlspecialchars($order['delivery_method'], ENT_QUOTES, 'UTF-8');
        $order['payment_method'] = $translations[$order['payment_method']] ?? htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8');
        $orders[] = $order;
    }
    echo json_encode(['orders' => $orders, 'total_orders' => $total_orders, 'total_pages' => $total_pages]);
    exit();
}

include 'load_header.php';
?>
<?php
// Ορίζει την εναλλαγή μεταξύ ASC και DESC
$new_order = ($sort_order === 'ASC') ? 'desc' : 'asc';

// Παίρνει όλα τα υπάρχοντα query parameters και τα ενημερώνει δυναμικά
$query_params = $_GET;
?>
<link rel="stylesheet" href="css/orders-user.css">
<div class="orders-container">
    <h2 class="orders-title">Οι Παραγγελίες σας</h2>
    <div class="orders-list">
        <div class="order-box header">
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'id_asc') ? 'id_desc' : 'id_asc'])); ?>">
                    <strong>Αριθμός Παραγγελίας</strong>
                    <?php if ($sort_order == 'id_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'id_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'user_id_asc') ? 'user_id_desc' : 'user_id_asc'])); ?>">
                    <strong>Αναγνωριστικό Πελάτη</strong>
                    <?php if ($sort_order == 'user_id_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'user_id_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'name_asc') ? 'name_desc' : 'name_asc'])); ?>">
                    <strong>Ονοματεπώνυμο Πελάτη</strong>
                    <?php if ($sort_order == 'name_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'name_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'date_asc') ? 'date_desc' : 'date_asc'])); ?>">
                    <strong>Ημερομηνία</strong>
                    <?php if ($sort_order == 'date_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'date_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'total_asc') ? 'total_desc' : 'total_asc'])); ?>">
                    <strong>Σύνολο</strong>
                    <?php if ($sort_order == 'total_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'total_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'delivery_asc') ? 'delivery_desc' : 'delivery_asc'])); ?>">
                    <strong>Μέθοδος Παράδοσης</strong>
                    <?php if ($sort_order == 'delivery_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'delivery_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'payment_asc') ? 'payment_desc' : 'payment_asc'])); ?>">
                    <strong>Μέθοδος Πληρωμής</strong>
                    <?php if ($sort_order == 'payment_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'payment_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column">
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'status_asc') ? 'status_desc' : 'status_asc'])); ?>">
                    <strong>Κατάσταση</strong>
                    <?php if ($sort_order == 'status_asc'): ?>
                        <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                    <?php elseif ($sort_order == 'status_desc'): ?>
                        <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                    <?php endif; ?>
                </a>
            </div>
            <div class="order-column"><strong>Ενέργειες</strong></div>
        </div>
        <div id="orders-content">
            <?php while ($order = $orders_result->fetch_assoc()): ?>
            <div class="order-box">
                <div class="order-column"><?php echo $order['id']; ?></div>
                <div class="order-column"><?php echo $order['user_id']; ?></div>
                <div class="order-column">
                    <?php echo htmlspecialchars($order['fName'] . ' ' . $order['lName'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="order-column"><?php echo (new DateTime($order['order_date']))->format('d/m/Y H:i:s'); ?></div>
                <div class="order-column">€<?php echo number_format($order['total_amount'], 2); ?></div>
                <div class="order-column"><?php echo $translations[$order['delivery_method']] ?? htmlspecialchars($order['delivery_method'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="order-column"><?php echo $translations[$order['payment_method']] ?? htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="order-column">
                    <?php if ($order['status'] == 'Εκκρεμεί'): ?>
                        <span class="status pending">Εκκρεμεί <i class="fas fa-spinner fa-spin"></i></span>
                    <?php elseif ($order['status'] == 'Ολοκληρώθηκε'): ?>
                        <span class="status completed">Ολοκληρώθηκε <i class="fas fa-check-circle"></i></span>
                    <?php endif; ?>
                </div>
                <div class="order-column">
                    <div class="tooltip">
                        <a href="order_preview.php?order_id=<?php echo $order['id']; ?>" class="view-order" title="Προβολή Παραγγελίας"><i class="fas fa-eye"></i></a>
                        <span class="tooltiptext">Προβολή Παραγγελίας</span>
                    </div>
                    &nbsp;&nbsp;
                    <div class="tooltip">
                        <a href="#" class="delete-order" data-order-id="<?php echo $order['id']; ?>" title="Διαγραφή"><i class="fas fa-trash-alt"></i></a>
                        <span class="tooltiptext">Διαγραφή Παραγγελίας</span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    // Παίρνει το τρέχον URL και διατηρεί τα υπάρχοντα query parameters
    $base_url = strtok($_SERVER["REQUEST_URI"], '?'); // Παίρνει το βασικό URL χωρίς παραμέτρους
    $query_params = $_GET; // Παίρνει όλα τα υπάρχοντα GET parameters
    ?>

    <div class="pagination">
        <span class="total-count">Σύνολο παραγγελιών: <?php echo $total_orders; ?></span>
        <br>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php
            // Ανανεώνει μόνο την παράμετρο "page", χωρίς να αλλάζει τις υπόλοιπες παραμέτρους
            $query_params['page'] = $i;
            $new_url = $base_url . '?' . http_build_query($query_params);
            ?>
            <a href="<?php echo $new_url; ?>" class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

        <?php
            // Παίρνει το τρέχον URL και διατηρεί τα υπάρχοντα query parameters
            $base_url = strtok($_SERVER["REQUEST_URI"], '?'); // Παίρνει το βασικό URL χωρίς παραμέτρους
            $query_params = $_GET; // Παίρνει όλα τα υπάρχοντα GET parameters
        ?>

        <div class="records-per-page">
            <form method="GET" action="">
                <label for="records_per_page">Εγγραφές ανά σελίδα:</label>
                <select name="records_per_page" id="records_per_page" onchange="updateRecordsPerPage()">
                    <?php foreach ([5, 10, 20, 50, 100] as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo ($records_per_page == $option) ? 'selected' : ''; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="<?php echo $total_orders; ?>" <?php echo ($records_per_page == $total_orders) ? 'selected' : ''; ?>>Όλες</option>
                </select>
                <input type="hidden" name="page" value="1"> <!-- Πάντα επιστρέφει στην 1η σελίδα -->
            </form>
        </div>

        <script>
        function updateRecordsPerPage() {
            let select = document.getElementById('records_per_page');
            let selectedValue = select.value;

            // Παίρνει το τρέχον URL και παραμέτρους
            let url = new URL(window.location.href);
            
            // Ορίζει το νέο records_per_page και επιστρέφει στη 1η σελίδα
            url.searchParams.set('records_per_page', selectedValue);
            url.searchParams.set('page', '1');

            // Ανακατευθύνει στο νέο URL
            window.location.href = url.toString();
        }
        </script>

</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Παραγγελία #<span id="deleteOrderId"></span></h3>
        <p>Ημερομηνία: <span id="deleteOrderDate"></span></p>
        <p>Σύνολο: <span id="deleteOrderTotal"></span></p>
        <hr>
        <p><i class="fas fa-exclamation-triangle" style="color: red; font-size: 24px;"></i> Είστε σίγουροι ότι θέλετε να διαγράψετε αυτήν την παραγγελία;</p>
        <button id="confirmDelete" class="confirm-button">Διαγραφή</button>
        <button id="cancelDelete" class="cancel-button">Ακύρωση</button>
    </div>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close-success">&times;</span>
        <h3>Παραγγελία #<span id="deletedOrderId"></span></h3>
        <p>Ημερομηνία: <span id="deletedOrderDate"></span></p>
        <p>Σύνολο: €<span id="deletedOrderTotal"></span></p>
        <hr>
        <p id="deletedOrderMessage"><i class="fas fa-check-circle" style="color: green;"></i> Η παραγγελία #<span id="deletedOrderId"></span> διαγράφηκε επιτυχώς.</p>
        <button id="closeSuccess" class="confirm-button">Κλείσιμο</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    const successModal = document.getElementById('successModal');
    const spanDelete = document.getElementsByClassName('close')[0];
    const spanSuccess = document.getElementsByClassName('close-success')[0];
    const confirmDelete = document.getElementById('confirmDelete');
    const closeSuccess = document.getElementById('closeSuccess');

    let deleteOrderId = '';
    let deleteOrderDate = '';
    let deleteOrderTotal = '';
    let deleteOrderCustomer = '';

    document.querySelectorAll('.delete-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderBox = this.closest('.order-box');

            deleteOrderId = this.getAttribute('data-order-id');
            deleteOrderDate = orderBox.querySelector('.order-column:nth-child(4)')?.innerText || 'Άγνωστη';
            deleteOrderTotal = orderBox.querySelector('.order-column:nth-child(5)')?.innerText.replace('€', '') || '0.00';
            deleteOrderCustomer = orderBox.querySelector('.order-column:nth-child(3)')?.innerText || 'Άγνωστος';

            if (document.getElementById('deleteOrderId')) {
                document.getElementById('deleteOrderId').innerText = deleteOrderId;
            }
            if (document.getElementById('deleteOrderDate')) {
                document.getElementById('deleteOrderDate').innerText = deleteOrderDate;
            }
            if (document.getElementById('deleteOrderTotal')) {
                document.getElementById('deleteOrderTotal').innerText = `€${deleteOrderTotal}`;
            }
            if (document.getElementById('deleteOrderCustomer')) {
                document.getElementById('deleteOrderCustomer').innerText = deleteOrderCustomer;
            }

            deleteModal.style.display = 'block';
        });
    });

    spanDelete.onclick = function() {
        deleteModal.style.display = 'none';
    }

    document.getElementById('cancelDelete').onclick = function() {
        deleteModal.style.display = 'none';
    }

    confirmDelete.onclick = function() {
        fetch('orders.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ delete_order_id: deleteOrderId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`.delete-order[data-order-id="${deleteOrderId}"]`)?.closest('.order-box')?.remove();
                deleteModal.style.display = 'none';

                if (document.getElementById('deletedOrderId')) {
                    document.getElementById('deletedOrderId').innerText = deleteOrderId;
                }
                if (document.getElementById('deletedOrderDate')) {
                    document.getElementById('deletedOrderDate').innerText = deleteOrderDate;
                }
                if (document.getElementById('deletedOrderTotal')) {
                    document.getElementById('deletedOrderTotal').innerText = `€${deleteOrderTotal}`;
                }
                if (document.getElementById('deletedOrderCustomer')) {
                    document.getElementById('deletedOrderCustomer').innerText = deleteOrderCustomer;
                }
                if (document.getElementById('deletedOrderMessage')) {
                    document.getElementById('deletedOrderMessage').innerHTML = `<i class="fas fa-check-circle" style="color: green;"></i> Η παραγγελία #${deleteOrderId} του πελάτη ${deleteOrderCustomer} διαγράφηκε επιτυχώς.`;
                }

                successModal.style.display = 'block';
            } else {
                alert('Η διαγραφή απέτυχε: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Σφάλμα:', error);
            alert('Παρουσιάστηκε σφάλμα κατά τη διαγραφή.');
        });
    }

    spanSuccess.onclick = function() {
        successModal.style.display = 'none';
    }

    closeSuccess.onclick = function() {
        successModal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
        }
        if (event.target == successModal) {
            successModal.style.display = 'none';
        }
    }
});



</script>

<?php
include 'footer.php';
$stmt_total->close();
$conn->close();
?>