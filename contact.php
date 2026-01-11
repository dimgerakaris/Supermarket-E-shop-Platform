<?php
// contact.php
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επικοινωνία</title>
    <link rel="stylesheet" href="css/contactCSS.css">
    <link rel="stylesheet" href="css/menu.css">
    
<link rel="stylesheet" href="css/footer.css"></head>
<body>
    <div class="contact-container">
        <h1>Επικοινωνια</h1>
        <p>Για οποιαδήποτε πληροφορία σχετικά με την πτυχιακή εργασία, επικοινωνήστε μαζί μου.</p>

        <div class="contact-info">
            <p><strong>Ονοματεπώνυμο:</strong> ΓΕΡΑΚΑΡΗΣ ΔΗΜΗΤΡΙΟΣ</p>
            <p><strong>Email:</strong> dim_gerakaris@yahoo.gr</p>
            <p><strong>Τμήμα:</strong> Τμήμα Ηλεκτρολόγων Μηχανικών και Μηχανικών Υπολογιστών, Ελληνικό Μεσογειακό Πανεπιστήμιο</p>
            <p><strong>Τηλέφωνο:</strong> 6955543902</p>
        </div>

        <h2>Φόρμα Επικοινωνίας</h2>
        <form method="POST" action="contactProcess.php">
            <div>
                <label for="name">Όνομα:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="message">Μήνυμα:</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button type="submit">Αποστολή</button>
        </form>
    </div>
</body>
</html>

<?php
include 'footer.php';
?>
