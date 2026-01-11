<?php
include 'load_header.php';
include 'info_bar.php';
include 'menu.php';
include 'cartDB_connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$sql = "SELECT id, name, image, price, description, category, stock FROM products";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = intval($_POST['delete_id']);
        
        // Διαγραφή προϊόντος από τη βάση δεδομένων
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Το προϊόν διαγράφηκε με επιτυχία!'); window.location.href='manage_stock.php';</script>";
        } else {
            echo "<script>alert('Σφάλμα κατά τη διαγραφή του προϊόντος: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
}

$conn->close();

$categoryTranslations = [
    'zymarika' => 'Ζυμαρικά',
    'zaxarh' => 'Ζάχαρη',
    'aleyria' => 'Αλεύρια',
    'saltses' => 'Σάλτσες',
    'mpaxarika' => 'Μπαχαρικά',
    'ladi' => 'Λάδι',
    'ryzi' => 'Ρύζι',
    'ospria' => 'Όσπρια',
    'ntomatika' => 'Ντοματικά',
    'nera' => 'Νερά',
    'anapsyktika' => 'Αναψυκτικά',
    'xymoi' => 'Χυμοί',
    'krasi' => 'Κρασί',
    'mpira' => 'Μπύρα',
    'pota' => 'Ποτά',
    'gala' => 'Γάλα',
    'krema' => 'Κρέμα',
    'giaoyrtia' => 'Γιαούρτια',
    'gBaby' => 'Βρεφικά Τρόφιμα',
    'leykoTyri' => 'Λευκό Τυρί',
    'kitrinoTyri' => 'Κίτρινο Τυρί',
    'ayga' => 'Αυγά',
    'boytyro' => 'Βούτυρο',
    'nwpesZymes' => 'Νωπές Ζύμες',
    'kotopoylo' => 'Κοτόπουλο',
    'mosxari' => 'Μοσχάρι',
    'arni' => 'Αρνί',
    'xoirino' => 'Χοιρινό',
    'galKot' => 'Γαλοπούλα',
    'zampon' => 'Ζαμπόν',
    'mpeikon' => 'Μπέικον',
    'salamia' => 'Σαλάμια',
    'loykanika' => 'Λουκάνικα',
    'psaria' => 'Ψάρια',
    'malakia' => 'Μαλάκια',
    'ostrakoeidh' => 'Οστρακοειδή',
    'mpiskota' => 'Μπισκότα',
    'sokolata' => 'Σοκολάτα',
    'snaks' => 'Σνακς',
    'kshroiKarpoi' => 'Ξηροί Καρποί',
    'dhmhtriaka' => 'Δημητριακά',
    'rofhmata' => 'Ροφήματα',
    'babyGala' => 'Βρεφικό Γάλα',
    'babyFoods' => 'Βρεφικές Τροφές',
    'xartika' => 'Χαρτικά',
    'panesEnhlikwn' => 'Πάνες Ενηλίκων',
    'panesBrefikes' => 'Πάνες Βρεφικές',
    'malliwn' => 'Μαλλιών',
    'swma' => 'Σώμα',
    'ksyrisma' => 'Ξύρισμα',
    'proswpo' => 'Πρόσωπο',
    'stoma' => 'Στόμα',
    'royxwn' => 'Ρούχων',
    'piata' => 'Πιάτα',
    'katharistika' => 'Καθαριστικά',
    'synergaKatharismoy' => 'Σύνεργα Καθαρισμού'
];
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Διαχείριση Αποθέματος</title>
    <link rel="stylesheet" href="css/manage_stock.css">

</head>
<body>
    <div class="title-container">
        <h2 class="title">Διαχείριση Αποθέματος</h2>
        <p><a href="add_product.php" class="modal-button">Θέλετε να προσθέσετε νέο προϊόν; Πατήστε εδώ</a></p> <!-- Προσθήκη του συνδέσμου -->
    </div>
    <div class="content">
        <div class="categories">
            <ul>
                <?php
                $categories = [];
                foreach ($products as $product) {
                    $categories[$product['category']][] = $product;
                }
                ?>
                <?php foreach (array_keys($categories) as $category): ?>
                    <li onclick="showCategory('<?php echo htmlspecialchars($category); ?>')"><?php echo htmlspecialchars($categoryTranslations[$category]); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="products">
            <?php foreach ($categories as $category => $products): ?>
                <div class="category-products" id="<?php echo htmlspecialchars($category); ?>" style="display: none;">
                    <h3 class="categories-title"><?php echo htmlspecialchars($categoryTranslations[$category]); ?></h3>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                                <div class="product-info">
                                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                                    <p><strong>Τιμή: </strong><?php echo htmlspecialchars($product['price']); ?> €</p>
                                    <p id="stock-<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['stock']); ?> σε απόθεμα</p>
                                    <p>
                                        <?php if ($product['stock'] > 0): ?>
                                            <i class="fas fa-check" style="color: green;"></i> Διαθέσιμο
                                        <?php else: ?>
                                            <i class="fas fa-times" style="color: red;"></i> Μη Διαθέσιμο
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="product-actions">
                                    <button class="edit-product" onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo htmlspecialchars($product['stock']); ?>)"><i class="fa fa-pencil-alt"></i> Επεξεργασία Αποθέματος</button>
                                    <button class="modal-button" onclick="openDeleteModal(<?php echo $product['id']; ?>)"><i class="fas fa-trash"></i> Διαγραφή Προϊόντος</button>
                                    <?php if ($_SESSION['role'] !== 'admin'): ?>
                                    <form action="cart.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 50px;" id="quantity-<?php echo $product['id']; ?>" oninput="checkStock(<?php echo $product['id']; ?>, <?php echo $product['stock']; ?>)">
                                        <button type="submit" class="modal-button" id="add-to-cart-<?php echo $product['id']; ?>">Προσθήκη στο καλάθι</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="modalBackground" onclick="closeModal()"></div>
    <div id="editModal">
        <h2>Επεξεργασία Αποθέματος</h2>
        <form id="editProductForm">
            <input type="hidden" id="productId">
            <div style="margin-bottom: 20px;">
                <label class="name" for="productName">Όνομα Προϊόντος:</label>
                <input type="text" class="product-name" id="productName" disabled style="font-weight: bold; width: 100%; text-align: center;">
            </div>
            <div style="margin-bottom: 20px;">
                <label class="stock" for="productStock">Απόθεμα Προϊόντος:</label>
                <input type="number" class= "product-stock" id="productStock" min="0" style="width: 100%; text-align: center;">
            </div>
            <button type="button" class="modal-button" onclick="saveProduct()">Αποθήκευση</button>
            <button type="button" class="modal-button" onclick="closeModal()">Ακύρωση</button>
        </form>
    </div>

    <div id="deleteModal">
        <h2>Επιβεβαίωση Διαγραφής</h2>
        <p>Είστε σίγουροι ότι θέλετε να διαγράψετε αυτό το προϊόν;</p>
        <form method="POST" action="manage_stock.php">
            <input type="hidden" name="delete_id" id="deleteProductId">
            <button type="submit" class="modal-button">Ναι</button>
            <button type="button" class="modal-button" onclick="closeModal()">Όχι</button>
        </form>
    </div>

    <script>
    function showCategory(category) {
        document.querySelectorAll('.category-products').forEach(div => {
            div.style.display = 'none';
        });
        document.getElementById(category).style.display = 'block';
    }

    function editProduct(id, name, stock) {
        document.getElementById('productId').value = id;
        document.getElementById('productName').value = name;
        document.getElementById('productStock').value = stock;
        document.getElementById('editModal').style.display = 'block';
        document.getElementById('modalBackground').style.display = 'block';
    }
    
    function openDeleteModal(id) {
        document.getElementById('deleteProductId').value = id;
        document.getElementById('deleteModal').style.display = 'block';
        document.getElementById('modalBackground').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('deleteModal').style.display = 'none';
        document.getElementById('modalBackground').style.display = 'none';
    }
    
    function saveProduct() {
        const id = document.getElementById('productId').value;
        const stock = document.getElementById('productStock').value;
        
        fetch('update_product.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id, stock })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('stock-' + id).innerText = stock + ' σε απόθεμα';
                closeModal();
            } else {
                alert('Σφάλμα κατά την ενημέρωση!');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function checkStock(productId, stock) {
        const quantityInput = document.getElementById('quantity-' + productId);
        const addToCartButton = document.getElementById('add-to-cart-' + productId);

        if (parseInt(quantityInput.value) >= stock) {
            addToCartButton.disabled = true;
        } else {
            addToCartButton.disabled = false;
        }
    }
    </script>
</body>
</html>
