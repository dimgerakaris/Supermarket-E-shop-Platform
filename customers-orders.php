<?php
session_start();
include 'header-admin.php'; // Admin header
include 'cartDB_connection.php'; // Database connection file

$conn = new mysqli($host, $user, $password, $database);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of new orders (only pending)
$sql_new_orders = "SELECT COUNT(*) AS new_orders FROM orders WHERE status = 'Εκκρεμεί'";
$result_new_orders = $conn->query($sql_new_orders);
$new_orders_count = 0;
if ($result_new_orders) {
    $row_new_orders = $result_new_orders->fetch_assoc();
    $new_orders_count = $row_new_orders['new_orders'] ?? 0;
}


// Fetch orders with optional filters and sorting
$filter = "";
$sort = "";

// Filters
if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
    $user_id = intval($_GET['user_id']);
    $filter .= " AND orders.user_id = $user_id";
}

if (isset($_GET['email']) && $_GET['email'] !== '') {
    $email = $conn->real_escape_string($_GET['email']);
    $filter .= " AND registration.email LIKE '%$email%'";
}

if (isset($_GET['total_amount']) && $_GET['total_amount'] !== '') {
    $total_amount = floatval($_GET['total_amount']);
    $filter .= " AND orders.total_amount = $total_amount";
}

if (isset($_GET['username']) && $_GET['username'] !== '') {
    $username = $conn->real_escape_string($_GET['username']);
    $filter .= " AND registration.username LIKE '%$username%'";
}

// Sorting
$allowed_sort_fields = ['orders.id', 'orders.order_date'];
$sort_by = isset($_GET['sort_by']) && in_array($_GET['sort_by'], $allowed_sort_fields) ? $_GET['sort_by'] : 'orders.order_date';
$sort_order = isset($_GET['sort_order']) && strtolower($_GET['sort_order']) == 'desc' ? 'ASC' : 'DESC';


$sort = " ORDER BY $sort_by $sort_order";

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$records_per_page = isset($_GET['records_per_page']) ? max(1, intval($_GET['records_per_page'])) : 10;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of orders
$sql_total = "SELECT COUNT(*) AS total FROM orders JOIN registration ON orders.user_id = registration.id WHERE 1=1 $filter";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_orders = $row_total['total'] ?? 0;
$total_pages = max(1, ceil($total_orders / $records_per_page));

// Fetch orders with pagination
$sql = "SELECT orders.*, registration.email, registration.username 
        FROM orders 
        JOIN registration ON orders.user_id = registration.id 
        WHERE 1=1 $filter 
        ORDER BY $sort_by $sort_order 
        LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

$orders = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
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
    <title>Διαχείριση Παραγγελιών Πελατών</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/orders.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .order-pending {
            background-color: #FFD580; /* Λιγότερο έντονο πορτοκαλί χρώμα για μη εκτελεσμένες παραγγελίες */
        }
    </style>
</head>
<body>
    <div class="orders-container">
        <h2 class="orders-title">Διαχείριση Παραγγελιών Πελατών</h2>
        <form method="GET" action="">
        <div class="filters">
            <div class="filter-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            </div>

            <div class="filter-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
            </div>

            <div class="filter-group">
                <label for="total_amount">Σύνολο:</label>
                <input type="text" name="total_amount" id="total_amount" value="<?php echo isset($_GET['total_amount']) ? htmlspecialchars($_GET['total_amount']) : ''; ?>">
            </div>

            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Αναζήτηση
            </button>
        </div>

            <input type="hidden" name="sort_by" value="<?php echo $sort_by; ?>">
            <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>">
            <input type="hidden" name="records_per_page" value="<?php echo $records_per_page; ?>">
        </form>
        <div class="orders-list">
            <div class="order-box header">
                <div class="order-column">
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'orders.id', 'sort_order' => ($sort_by == 'orders.id' && $sort_order == 'ASC') ? 'asc' : 'asc'])); ?>">
                        <strong>ID</strong>
                        <?php if ($sort_by == 'orders.id' && $sort_order == 'ASC'): ?>
                            <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                        <?php elseif ($sort_by == 'orders.id' && $sort_order == 'DESC'): ?>
                            <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="order-column">
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'registration.email', 'sort_order' => ($sort_by == 'registration.email' && $sort_order == 'ASC') ? 'desc' : 'asc'])); ?>">
                        <strong>Email</strong>
                        <?php if ($sort_by == 'registration.email' && $sort_order == 'ASC'): ?>
                            <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                        <?php elseif ($sort_by == 'registration.email' && $sort_order == 'DESC'): ?>
                            <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="order-column">
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'registration.username', 'sort_order' => ($sort_by == 'registration.username' && $sort_order == 'ASC') ? 'desc' : 'asc'])); ?>">
                        <strong>Username</strong>
                        <?php if ($sort_by == 'registration.username' && $sort_order == 'ASC'): ?>
                            <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                        <?php elseif ($sort_by == 'registration.username' && $sort_order == 'DESC'): ?>
                            <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="order-column">
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'orders.total_amount', 'sort_order' => ($sort_by == 'orders.total_amount' && $sort_order == 'ASC') ? 'desc' : 'asc'])); ?>">
                        <strong>Σύνολο</strong>
                        <?php if ($sort_by == 'orders.total_amount' && $sort_order == 'ASC'): ?>
                            <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                        <?php elseif ($sort_by == 'orders.total_amount' && $sort_order == 'DESC'): ?>
                            <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="order-column">
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort_by' => 'orders.order_date', 'sort_order' => ($sort_by == 'orders.order_date' && $sort_order == 'ASC') ? 'desc' : 'asc'])); ?>">
                        <strong>Ημερομηνία</strong>
                        <?php if ($sort_by == 'orders.order_date' && $sort_order == 'ASC'): ?>
                            <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                        <?php elseif ($sort_by == 'orders.order_date' && $sort_order == 'DESC'): ?>
                            <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="order-column"><strong>Κατάσταση</strong></div>
                <div class="order-column"><strong>Ενέργειες</strong></div>
            </div>
            <?php foreach ($orders as $order): ?>
                <div class="order-box <?php echo ($order['status'] == 'Εκκρεμεί') ? 'order-pending' : 'order-completed'; ?>">
                <div class="order-column"><?php echo $order['id']; ?></div>
                <div class="order-column"><?php echo htmlspecialchars($order['email']); ?></div>
                <div class="order-column"><?php echo htmlspecialchars($order['username']); ?></div>
                <div class="order-column">€<?php echo number_format($order['total_amount'], 2); ?></div>
                <div class="order-column"><?php echo (new DateTime($order['order_date']))->format('d/m/Y H:i:s'); ?></div>
                <div class="order-column"><?php echo htmlspecialchars($order['status']); ?></div>
                <div class="order-column">
                    <div class="tooltip">
                        <a href="order_preview.php?order_id=<?php echo $order['id']; ?>" class="view-order" title="Προβολή Παραγγελίας"><i class="fas fa-eye"></i></a>
                        <span class="tooltiptext">Προβολή Παραγγελίας</span>
                    </div>
                    <div class="tooltip">
                        <a href="delete_order.php?id=<?php echo $order['id']; ?>" class="delete-order" onclick="return confirm('Είσαι σίγουρος;');" title="Διαγραφή"><i class="fas fa-trash-alt"></i></a>
                        <span class="tooltiptext">Διαγραφή Παραγγελίας</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <p class="total-orders">Σύνολο Παραγγελιών: <?php echo $total_orders; ?></p>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <div class="records-per-page">
            <form method="GET" action="">
                <label for="records_per_page">Εγγραφές ανά σελίδα:</label>
                <select name="records_per_page" id="records_per_page" onchange="this.form.submit()">
                    <?php foreach ([5, 10, 20, 50, 100] as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo ($records_per_page == $option) ? 'selected' : ''; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="<?php echo $total_orders; ?>" <?php echo ($records_per_page == $total_orders) ? 'selected' : ''; ?>>Όλες</option>
                </select>
                <input type="hidden" name="sort_by" value="<?php echo $sort_by; ?>">
                <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>">
                <input type="hidden" name="user_id" value="<?php echo isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : ''; ?>">
                <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                <input type="hidden" name="total_amount" value="<?php echo isset($_GET['total_amount']) ? htmlspecialchars($_GET['total_amount']) : ''; ?>">
                <input type="hidden" name="username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                <input type="hidden" name="page" value="1">
            </form>
        </div>
    </div>
    <style>
        .order-pending {
            background-color: #FFD580; /* Ανοιχτό πορτοκαλί για εκκρεμείς παραγγελίες */
        }

        .order-completed {
            background-color: #D4EDDA; /* Απαλό πράσινο για ολοκληρωμένες παραγγελίες */
        }
    </style>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>