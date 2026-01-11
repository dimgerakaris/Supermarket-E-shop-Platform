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


$conn->set_charset("utf8mb4");

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $category = trim($_POST['category'] ?? '');
    
    // Έλεγχος και αποθήκευση εικόνας
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "PHOTO/products/" . $category . "/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_file = $target_dir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_file)) {
            echo "<script>alert('Σφάλμα κατά την αποθήκευση της εικόνας.');</script>";
            error_log("Σφάλμα μεταφοράς αρχείου: " . $_FILES['image']['error']);
            die("Error uploading image!");
        }
        $image = $image_file;

    } else {
        echo "<script>alert('Παρακαλώ ανεβάστε μια εικόνα.');</script>";
        $image = "";
    }

    if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || empty($category) || empty($image)) {
        echo "<script>alert('Όλα τα πεδία είναι υποχρεωτικά και πρέπει να έχουν έγκυρες τιμές.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category, $image);

        if ($stmt->execute()) {
            echo "<script>alert('Το προϊόν προστέθηκε με επιτυχία!'); window.location.href='manage_stock.php';</script>";
        } else {
            echo "<script>alert('Σφάλμα κατά την προσθήκη του προϊόντος: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image = $_POST['image'];

    // Διαχείριση του ανεβάσματος της εικόνας
    $targetDir = "PHOTO/products/zymarika/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Έλεγχος αν το αρχείο είναι εικόνα
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('Το αρχείο δεν είναι εικόνα.');</script>";
        exit();
    }

    // Έλεγχος αν το αρχείο υπάρχει ήδη
    if (file_exists($targetFile)) {
        echo "<script>alert('Η εικόνα υπάρχει ήδη.');</script>";
        exit();
    }

    // Έλεγχος μεγέθους αρχείου
    if ($_FILES["image"]["size"] > 500000) {
        echo "<script>alert('Η εικόνα είναι πολύ μεγάλη.');</script>";
        exit();
    }

    // Επιτρέπονται μόνο συγκεκριμένες μορφές αρχείων
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Επιτρέπονται μόνο αρχεία JPG, JPEG, PNG & GIF.');</script>";
        exit();
    }

    // Ανέβασμα αρχείου
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        echo "<script>alert('Σφάλμα κατά το ανέβασμα της εικόνας.');</script>";
        exit();
    }

    $image = $targetFile;

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $description, $price, $stock, $category, $image);

    if ($stmt->execute()) {
        echo "<script>alert('Το προϊόν προστέθηκε με επιτυχία!'); window.location.href='manage_stock.php';</script>";
    } else {
        echo "<script>alert('Σφάλμα κατά την προσθήκη του προϊόντος!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Προσθήκη Νέου Προϊόντος</title>
    <link rel="stylesheet" href="css/add_product.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .form-container h2 {
            text-align: center;
            color: #ff6600;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container select,
        .form-container input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #ff6600;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-container button:hover {
            background-color: #e65c00;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Προσθήκη Νέου Προϊόντος</h2>
        <form method="POST" action="add_product.php" enctype="multipart/form-data">
            <label for="name">Όνομα Προϊόντος:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Περιγραφή Προϊόντος:</label>
            <input type="text" id="description" name="description" required>

            <label for="price">Τιμή Προϊόντος:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="stock">Απόθεμα Προϊόντος:</label>
            <input type="number" id="stock" name="stock" required>

            <label for="category">Κατηγορία Προϊόντος:</label>
            <select id="category" name="category" required>
                <?php foreach ($categoryTranslations as $key => $value): ?>
                    <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($value); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="image">Ανέβασμα Εικόνας Προϊόντος:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Προσθήκη Προϊόντος</button>
        </form>
    </div>
</body>
</html>
