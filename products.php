<?php
include 'load_header.php';
include 'info_bar.php';
include 'search_component.php';
include 'menu.php';
include 'cartDB_connection.php';

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος αν ο χρήστης είναι admin για να φορτώσουμε το κατάλληλο header
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'header-admin.php';
} else {
    include 'header.php';
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Τα Προϊόντα μας</title>
    <link rel="stylesheet" type="text/css" href="css/productsCSS.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.0/typed.js"></script>
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <h1>Επιλέξτε κατηγορία προϊόντων:</h1>
    <ul>
        <li class="category">
            <img src="PHOTO/category/pantopoleio.png" alt="Είδη Παντοπωλείου" class="category-img">
            <a href="#pantopwleio">Είδη Παντοπωλείου</a>
            <ul class="subcategories">
                <li><a href="zymarika.php">Ζυμαρικά</a></li>
                <li><a href="aleyria.php">Αλεύρι</a></li>
                <li><a href="zaxari.php">Ζάχαρη</a></li>
                <li><a href="saltses.php">Έτοιμες Σάλτσες</a></li>
                <li><a href="mpaxarika.php">Αλάτι και Μπαχαρικά</a></li>
                <li><a href="ladi.php">Λάδια και Ξύδι</a></li>
                <li><a href="ryzia.php">Ρύζια</a></li>
                <li><a href="ospria.php">Όσπρια</a></li>
                <li><a href="ntomatika.php">Ντοματικά</a></li>
            </ul>
        </li>
        <li class="category">
            <img src="PHOTO/category/drinks.jpg" alt="Νερά, Αναψυκτικά, Χυμοί" class="category-img">
            <a href="#drinks">Νερά, Αναψυκτικά, Χυμοί</a>
            <ul class="subcategories">
                <li><a href="nera.php">Νερά</a></li>
                <li><a href="anapsyktika.php">Αναψυκτικά</a></li>
                <li><a href="xymoi.php">Χυμοί</a></li>
            </ul>
        </li>
        <li class="category">
            <img src="PHOTO/category/kaba.jpg" alt="Κάβα" class="category-img">
            <a href="#kaba">Κάβα</a>
            <ul class="subcategories">
                <li><a href="krasi.php">Κρασιά</a></li>
                <li><a href="mpira.php">Μπύρες</a></li>
                <li><a href="pota.php">Ποτά</a></li>
            </ul>
        </li>
        <li class="category">
            <img src="PHOTO/category/galata.jpg" alt="Γάλατα, Κρέμες Γάλακτος" class="category-img">
            <a href="#galata">Γάλατα, Κρέμες Γάλακτος</a>
            <ul class="subcategories">
                <li><a href="gala.php">Γάλα</a></li>
                <li><a href="krema.php">Κρέμα Γάλακτος</a></li>
            </ul>
        </li>
        <li class="category">
            <img src="PHOTO/category/allantika.jpg" alt="Αλλαντικά" class="category-img">
            <a href="#allantika">Αλλαντικά</a>
            <ul class="subcategories">
                <li><a href="galopKOT.php">Αλλαντικά Γαλοπούλας</a></li>
                <li><a href="zampon.php">Ζαμπόν</a></li>
                <li><a href="mpeikon.php">Μπέικον</a></li>
                <li><a href="salamia.php">Σαλάμια</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/thalassina.jpg" alt="Φρέσκο Ψάρι, Θαλασσινά" class="category-img">
            <a href="#thalassina">Φρέσκο Ψάρι, Θαλασσινά</a>
            <ul class="subcategories">
                <li><a href="psari.php">Ψάρια</a></li>
                <li><a href="malakia.php">Μαλάκια Θαλασσινά</a></li>
				<li><a href="ostrakoeidh.php">Οστρακοειδή Θαλασσινά</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/sokolates.jpg" alt="Μπισκότα, Ζαχαρώδη, Σοκολάτες" class="category-img">
            <a href="#sokolates">Μπισκότα, Ζαχαρώδη, Σοκολάτες</a>
            <ul class="subcategories">
                <li><a href="mpiskota.php">Μπισκότα</a></li>
                <li><a href="sokolata.php">Σοκολάτες</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/snak.jpg" alt="Σνακ" class="category-img">
            <a href="#snak">Σνακ, Ξηροί Καρποί</a>
			<ul class="subcategories">
                <li><a href="snaks.php">Σνάκς</a></li>
                <li><a href="kshroiKarpoi.php">Ξηροί Καρποί</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/prwino.jpg" alt="Είδη πρωινού, Ροφήματα" class="category-img">
            <a href="#prwino">Είδη πρωινού, Ροφήματα</a>
			<ul class="subcategories">
                <li><a href="dhmhtriaka.php">Δημητριακά και Μπάρες</a></li>
                <li><a href="rofhmata.php">Καφέδες και Ροφήματα</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/babyfood.jpg" alt="Βρεφικά Τρόφιμα" class="category-img">
            <a href="#babyfood">Βρεφικά Τρόφιμα</a>
			<ul class="subcategories">
                <li><a href="babyGala.php">Βρεφικά και Παιδικά Γάλατα</a></li>
                <li><a href="babyFoods.php">Βρεφικές και Παιδικές Τροφές</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/xartika.jpg" alt="Χαρτικά, Πάνες" class="category-img">
            <a href="#xartika">Χαρτικά, Πάνες, Σερβιέτες</a>
			<ul class="subcategories">
                <li><a href="xartikaa.php">Χαρτικά</a></li>
                <li><a href="panesEnhlikwn.php">Σερβιέτες και Πάνες Ενηλίκων</a></li>
				<li><a href="panesBrefikes.php">Βρεφικές πάνες και Μωρομάντηλα</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/proswpika.jpg" alt="Είδη προσωπικής Υγιεινής" class="category-img">
            <a href="#proswpika">Είδη προσωπικής Υγιεινής, Καλλυντικά</a>
			<ul class="subcategories">
                <li><a href="malliwn.php">Φροντίδα Μαλλιών</a></li>
                <li><a href="swma.php">Φροντίδα Σώματος</a></li>
				<li><a href="ksyrisma.php">Είδη Ξυρίσματος</a></li>
				<li><a href="proswpo.php">Φροντίδα Προσώπου</a></li>
                <li><a href="stoma.php">Στοματική Υγιεινή</a></li>
            </ul>
        </li>

        <li class="category">
            <img src="PHOTO/category/katharistika.jpg" alt="Απορρυπαντικά" class="category-img">
            <a href="#katharistika">Απορρυπαντικά, Είδη καθαρισμού</a>
			<ul class="subcategories">
                <li><a href="royxwn.php">Απορρυπαντικά Ρούχων</a></li>
                <li><a href="piata.php">Απορρυπαντικά Πιάτων</a></li>
				<li><a href="katharistika.php">Καθαριστικά Γενικής Χρήσης</a></li>
				<li><a href="synergaKatharismoy.php">Σύνεργα καθαρισμού</a></li>
            </ul>
        </li>
    </ul>

    <script>
    function addToCart(productId, stock) {
    console.log("Product ID:", productId, "Stock:", stock);

    if (typeof productId === "undefined" || productId === null) {
        console.error("Πρόβλημα: Το productId δεν ορίστηκε σωστά!");
        return;
    }

    if (stock <= 0) {
        alert("Το προϊόν δεν είναι διαθέσιμο!");
        return;
    }

}

    </script>
</body>
</html>

<?php
include 'footer.php';
?>
