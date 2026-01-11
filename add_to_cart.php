<?php
session_start();
include 'cartDB_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Πρέπει να συνδεθείτε για να προσθέσετε προϊόντα στο καλάθι.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug_add_to_cart.log', json_encode($_POST) . PHP_EOL, FILE_APPEND);

    // Έλεγχος δεδομένων
    if (empty($_POST['product_id']) || empty($_POST['quantity']) || empty($_POST['price'])) {
        echo json_encode(['status' => 'error', 'message' => 'Μη έγκυρα δεδομένα.']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);

    // Ανάκτηση προϊόντος από τον πίνακα products
    $sql = "SELECT name, image FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Το προϊόν δεν βρέθηκε.']);
        exit;
    }

    $product_name = $product['name'];
    $product_image = $product['image'];

    // Εισαγωγή ή ενημέρωση στο καλάθι
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    } else {
        $sql = "INSERT INTO cart (user_id, product_id, product_name, product_image, quantity, price) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissid", $user_id, $product_id, $product_name, $product_image, $quantity, $price);
    }

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Το προϊόν προστέθηκε στο καλάθι.',
            'debug' => [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_image' => $product_image,
                'price' => $price,
                'quantity' => $quantity
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Σφάλμα κατά την προσθήκη στο καλάθι.']);
    }

    $conn->close();
}
?>
