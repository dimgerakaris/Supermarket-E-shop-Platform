<?php
session_start();
include 'header-admin.php'; // Admin header
include 'cartDB_connection.php'; // Database connection file
// include 'info_bar.php';
// include 'search_component.php';
// include 'menu.php';

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος και έχει τον ρόλο admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Καθορισμός τρέχουσας σελίδας
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Καθορισμός αριθμού εγγραφών ανά σελίδα
$records_per_page = isset($_GET['records_per_page']) ? max(1, intval($_GET['records_per_page'])) : 5;
$offset = ($page - 1) * $records_per_page;

// filepath: /c:/xampp/htdocs/ΠΤΥΧΙΑΚΗ/customers.php
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'id_asc';
switch ($sort_order) {
    case 'id_asc':
        $order_by = 'id ASC';
        break;
    case 'id_desc':
        $order_by = 'id DESC';
        break;
    case 'fname_asc':
        $order_by = 'fName ASC';
        break;
    case 'fname_desc':
        $order_by = 'fName DESC';
        break;
    case 'lname_asc':
        $order_by = 'lName ASC';
        break;
    case 'lname_desc':
        $order_by = 'lName DESC';
        break;
    case 'email_asc':
        $order_by = 'email ASC';
        break;
    case 'email_desc':
        $order_by = 'email DESC';
        break;
    case 'odos_asc':
        $order_by = 'odos ASC';
        break;
    case 'odos_desc':
        $order_by = 'odos DESC';
        break;
    case 'number_asc':
        $order_by = 'number ASC';
        break;
    case 'number_desc':
        $order_by = 'number DESC';
        break;
    case 'tk_asc':
        $order_by = 'tk ASC';
        break;
    case 'tk_desc':
        $order_by = 'tk DESC';
        break;
    default:
        $order_by = 'id ASC';
        break;
}

// Ανάκτηση των πελατών από τη βάση δεδομένων με pagination
$sql = "SELECT id, fName, lName, email, odos, number, tk FROM registration WHERE role != 'admin' ORDER BY $order_by LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Ανάκτηση συνολικού αριθμού πελατών για το pagination
$sql_total = "SELECT COUNT(*) AS total FROM registration WHERE role != 'admin'";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_customers = $row_total['total'] ?? 0;
$total_pages = max(1, ceil($total_customers / $records_per_page));

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Διαχείριση Πελατών</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/customers.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="customers-container">
        <h2 class="customers-title">Διαχείριση Πελατών</h2>
        <div class="customers-list">
        <div class="customer-box header">
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'id_asc') ? 'id_desc' : 'id_asc'])); ?>">
                <strong>ID</strong>
                <?php if ($sort_order == 'id_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'id_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'fname_asc') ? 'fname_desc' : 'fname_asc'])); ?>">
                <strong>Όνομα</strong>
                <?php if ($sort_order == 'fname_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'fname_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'lname_asc') ? 'lname_desc' : 'lname_asc'])); ?>">
                <strong>Επώνυμο</strong>
                <?php if ($sort_order == 'lname_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'lname_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'email_asc') ? 'email_desc' : 'email_asc'])); ?>">
                <strong>Email</strong>
                <?php if ($sort_order == 'email_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'email_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'odos_asc') ? 'odos_desc' : 'odos_asc'])); ?>">
                <strong>Οδός</strong>
                <?php if ($sort_order == 'odos_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'odos_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'number_asc') ? 'number_desc' : 'number_asc'])); ?>">
                <strong>Αριθμός</strong>
                <?php if ($sort_order == 'number_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'number_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column">
            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => ($sort_order == 'tk_asc') ? 'tk_desc' : 'tk_asc'])); ?>">
                <strong>Τ.Κ.</strong>
                <?php if ($sort_order == 'tk_asc'): ?>
                    <i class="fas fa-arrow-up" style="color: #ff6600;"></i>
                <?php elseif ($sort_order == 'tk_desc'): ?>
                    <i class="fas fa-arrow-down" style="color: #ff6600;"></i>
                <?php endif; ?>
            </a>
        </div>
        <div class="customer-column"><strong>Ενέργειες</strong></div>
    </div>
            </div>
            <div id="customers-content">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="customer-box">
                        <div class="customer-column"><?php echo $row['id']; ?></div>
                        <div class="customer-column"><?php echo htmlspecialchars($row['fName']); ?></div>
                        <div class="customer-column"><?php echo htmlspecialchars($row['lName']); ?></div>
                        <div class="customer-column"><?php echo htmlspecialchars($row['email']); ?></div>
                        <div class="customer-column"><?php echo htmlspecialchars($row['odos']); ?></div>
                        <div class="customer-column"><?php echo htmlspecialchars($row['number']); ?></div>
                        <div class="customer-column"><?php echo htmlspecialchars($row['tk']); ?></div>
                        <div class="customer-column">
                            <div class="tooltip">
                                <a href="orders.php?user_id=<?php echo $row['id']; ?>" class="view-orders" title="Προβολή Παραγγελιών">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                                <span class="tooltiptext">Προβολή Παραγγελιών</span>
                            </div>
                            <div class="tooltip">
    <a href="javascript:void(0);" class="delete-user" data-id="<?php echo $row['id']; ?>" title="Διαγραφή">
        <i class="fas fa-trash-alt"></i>
    </a>
    <span class="tooltiptext">Διαγραφή Χρήστη</span>
</div>

                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <p class="total-customers">Σύνολο Πελατών: <?php echo $total_customers; ?></p>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&records_per_page=<?php echo $records_per_page; ?>&sort=<?php echo $sort_order; ?>"
                    class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <div class="records-per-page">
        <div style="clear: both;"></div>
            <form class="records"  method="GET" action="">
                <label for="records_per_page">Εγγραφές ανά σελίδα:</label>
                <select style="margin-top:50px;" name="records_per_page" id="records_per_page" onchange="this.form.submit()">
                    <?php foreach ([5, 10, 20, 50, 100] as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo ($records_per_page == $option) ? 'selected' : ''; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="<?php echo $total_customers; ?>" <?php echo ($records_per_page == $total_customers) ? 'selected' : ''; ?>>Όλες</option>
                </select>
                <input type="hidden" name="sort" value="<?php echo $sort_order; ?>">
                <input type="hidden" name="page" value="1">
            </form>
        </div> 
                    
    </div>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".delete-user").forEach(button => {
        button.addEventListener("click", function() {
            let userId = this.getAttribute("data-id");

            if (!confirm("Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτόν τον πελάτη;")) {
                return;
            }

            fetch("delete_user.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id=" + userId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Ο πελάτης διαγράφηκε επιτυχώς!");
                    this.closest(".customer-box").remove(); // Αφαίρεση από το HTML
                } else {
                    alert("Αποτυχία διαγραφής: " + data.message);
                }
            })
            .catch(error => console.error("Σφάλμα:", error));
        });
    });
});
</script>

<?php
$stmt->close();
$conn->close();
?>
