<?php
$category_map = [
    'Είδη Παντοπωλείου' => ['zymarika', 'zaxari', 'aleyria', 'saltses', 'mpaxarika', 'ladi', 'ryzia', 'ospria', 'ntomatika'],
    'Νερά, Αναψυκτικά, Χυμοί' => ['nera', 'anapsyktika', 'xymoi'],
    'Κάβα' => ['krasi', 'mpira', 'pota'],
    'Γάλατα, Κρέμες Γάλακτος' => ['gala', 'krema'],
    'Αλλαντικά' => ['zampon', 'mpeikon', 'loykanika', 'salamia'],
    'Φρέσκο Ψάρι, Θαλασσινά' => ['psari', 'malakia', 'ostrakoeidh'],
    'Μπισκότα, Ζαχαρώδη, Σοκολάτες' => ['mpiskota', 'sokolata'],
    'Σνακ, Ξηροί Καρποί' => ['snaks', 'kshroiKarpoi'],
    'Είδη πρωινού, Ροφήματα' => ['dhmhtriaka', 'rofhmata'],
    'Βρεφικά Τρόφιμα' => ['babyGala', 'babyFoods'],
    'Χαρτικά, Πάνες, Σερβιέτες' => ['xartika', 'panesEnhlikwn', 'panesBrefikes'],
    'Είδη προσωπικής Υγιεινής, Καλλυντικά' => ['malliwn', 'swma', 'ksyrisma', 'proswpo', 'stoma'],
    'Απορρυπαντικά, Είδη καθαρισμού' => ['royxwn', 'piata', 'katharistika', 'synergaKatharismoy'],
];
 //Ανακατεύθυνση προϊόντος με βάση την σελίδα .php
 $category_to_file = [
    'zymarika' => 'zymarika.php',
    'zaxari' => 'zaxari.php',
    'aleyria' => 'aleyria.php',
    'saltses' => 'saltses.php',
    'mpaxarika' => 'mpaxarika.php',
    'ladi' => 'ladi.php',
    'ryzia' => 'ryzia.php',
    'ospria' => 'ospria.php',
    'ntomatika' => 'ntomatika.php',
    'nera' => 'nera.php',
    'anapsyktika' => 'anapsyktika.php',
    'xymoi' => 'xymoi.php',
    'krasi' => 'krasi.php',
    'mpira' => 'mpira.php',
    'pota' => 'pota.php',
    'gala' => 'gala.php',
    'krema' => 'krema.php',
    'giaoyrtia' => 'giaoyrtia.php',
    'gBaby' => 'gBaby.php',
    'leykoTyri' => 'leykoTyri.php',
    'kitrinoTyri' => 'kitrinoTyri.php',
    'ayga' => 'ayga.php',
    'boytyro' => 'boytyro.php',
    'nwpesZymes' => 'nwpesZymes.php',
    'kotopoylo' => 'kotopoylo.php',
    'mosxari' => 'mosxari.php',
    'arni' => 'arni.php',
    'xoirino' => 'xoirino.php',
    'galKot' => 'galopKOT.php',
    'zampon' => 'zampon.php',
    'mpeikon' => 'mpeikon.php',
    'salamia' => 'salamia.php',
    'loykanika' => 'loykanika.php',
    'psari' => 'psari.php',
    'malakia' => 'malakia.php',
    'ostrakoeidh' => 'ostrakoeidh.php',
    'mpiskota' => 'mpiskota.php',
    'sokolata' => 'sokolata.php',
    'snaks' => 'snaks.php',
    'kshroiKarpoi' => 'kshroiKarpoi.php',
    'dhmhtriaka' => 'dhmhtriaka.php',
    'rofhmata' => 'rofhmata.php',
    'babyGala' => 'babyGala.php',
    'babyFoods' => 'babyFoods.php',
    'xartika' => 'xartikaa.php',
    'panesEnhlikwn' => 'panesEnhlikwn.php',
    'panesBrefikes' => 'panesBrefikes.php',
    'malliwn' => 'malliwn.php',
    'swma' => 'swma.php',
    'ksyrisma' => 'ksyrisma.php',
    'proswpo' => 'proswpo.php',
    'stoma' => 'stoma.php',
    'royxwn' => 'royxwn.php',
    'piata' => 'piata.php',
    'katharistika' => 'katharistika.php',
    'synergaKatharismoy' => 'synergaKatharismoy.php',
];
$photo_map = [
    'Είδη Παντοπωλείου' => 'pantopoleio.png',
    'Νερά, Αναψυκτικά, Χυμοί' => 'drinks.jpg',
    'Κάβα' => 'kaba.jpg',
    'Γάλατα, Κρέμες Γάλακτος' => 'galata.jpg',
    'Αλλαντικά' => 'allantika.jpg',
    'Φρέσκο Ψάρι, Θαλασσινά' => 'thalassina.jpg',
    'Μπισκότα, Ζαχαρώδη, Σοκολάτες' => 'sokolates.jpg',
    'Σνακ, Ξηροί Καρποί' => 'snak.jpg',
    'Είδη πρωινού, Ροφήματα' => 'prwino.jpg',
    'Βρεφικά Τρόφιμα' => 'babyfood.jpg',
    'Χαρτικά, Πάνες, Σερβιέτες' => 'xartika.jpg',
    'Είδη προσωπικής Υγιεινής, Καλλυντικά' => 'proswpika.jpg',
    'Απορρυπαντικά, Είδη καθαρισμού' => 'katharistika.jpg',
];

//Μεταφράσεις κατηγοριών προϊόντων για καλύτερη εμφάνιση στο menu results
$subcategory_map = [
    'zymarika' => 'Ζυμαρικά',
    'zaxari' => 'Ζάχαρη',
    'aleyria' => 'Αλεύρια',
    'saltses' => 'Σάλτσες',
    'mpaxarika' => 'Μπαχαρικά',
    'ladi' => 'Λάδι',
    'ryzia' => 'Ρύζι',
    'ospria' => 'Όσπρια',
    'ntomatika' => 'Ντομάτα',
    'nera' => 'Νερά',
    'anapsyktika' => 'Αναψυκτικά',
    'xymoi' => 'Χυμοί',
    'krasi' => 'Κρασί',
    'mpira' => 'Μπύρα',
    'pota' => 'Ποτά',
    'gala' => 'Γάλα',
    'krema' => 'Κρέμα Γάλακτος',
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
    'psari' => 'Ψάρια',
    'malakia' => 'Μαλάκια',
    'ostrakoeidh' => 'Οστρακοειδή',
    'mpiskota' => 'Μπισκότα',
    'sokolata' => 'Σοκολάτα',
    'snaks' => 'Σνακ',
    'kshroiKarpoi' => 'Ξηροί Καρποί',
    'dhmhtriaka' => 'Δημητριακά',
    'rofhmata' => 'Ροφήματα',
    'babyGala' => 'Βρεφικό Γάλα',
    'babyFoods' => 'Βρεφικές Τροφές',
    'xartika' => 'Χαρτικά',
    'panesEnhlikwn' => 'Πάνες Ενηλίκων',
    'panesBrefikes' => 'Πάνες Βρεφικές',
    'malliwn' => 'Φροντίδα Μαλλιών',
    'swma' => 'Φροντίδα Σώματος',
    'ksyrisma' => 'Ξύρισμα',
    'proswpo' => 'Φροντίδα Προσώπου',
    'stoma' => 'Στοματική Υγιεινή',
    'royxwn' => 'Απορρυπαντικά Ρούχων',
    'piata' => 'Απορρυπαντικά Πιάτων',
    'katharistika' => 'Καθαριστικά Γενικής Χρήσης',
    'synergaKatharismoy' => 'Σύνεργα Καθαρισμού',
];
echo '<div class="menu-container" id="menuContainer">';
echo '<div class="menu-icon" id="menuButton"><i class="fas fa-bars"></i><span class="menu-text">Μενού</span></div>';
echo '<ul class="menu" id="dropdownMenu">';
foreach ($category_map as $category => $subcategories) {
    $image_path = "PHOTO/category/{$photo_map[$category]}";

    echo "<li class='menu-item'>";
    echo "<div class='menu-header'>";
    echo "<img src='{$image_path}' alt='{$category}' class='menu-image'>";
    echo "<h3 style='font-size:16px; font-weight:bold;'>{$category}</h3>";
    echo "</div>";

    echo "<ul class='submenu'>";
    foreach ($subcategories as $subcategory) {
        $subcategory_name = isset($subcategory_map[$subcategory]) ? $subcategory_map[$subcategory] : $subcategory;
        $subcategory_file = isset($category_to_file[$subcategory]) ? $category_to_file[$subcategory] : '#';

        echo "<li><a href='{$subcategory_file}'>{$subcategory_name}</a></li>";
    }
    echo "</ul>";
    echo "</li>";
}
echo '</ul>';
echo '</div>';
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuButton = document.getElementById("menuButton");
    const menu = document.getElementById("dropdownMenu");
    const menuItems = document.querySelectorAll(".menu-item .menu-header");

    // Άνοιγμα/κλείσιμο του κύριου μενού με κλικ
    menuButton.addEventListener("click", function (event) {
        event.stopPropagation(); // Αποφυγή άμεσου κλεισίματος
        menu.classList.toggle("active");
    });

    // Άνοιγμα/κλείσιμο υπομενού με κλικ στο menu-header
    menuItems.forEach(header => {
        header.addEventListener("click", function (event) {
            event.stopPropagation(); // Μην κλείσει αμέσως το dropdown
            
            let parentItem = this.parentElement; // Παίρνουμε το menu-item

            // Αν είναι ήδη ανοιχτό, το κλείνουμε, αλλιώς κλείνουμε όλα τα άλλα και το ανοίγουμε
            if (parentItem.classList.contains("active")) {
                parentItem.classList.remove("active");
            } else {
                document.querySelectorAll(".menu-item").forEach(item => item.classList.remove("active")); // Κλείνουμε όλα τα άλλα
                parentItem.classList.add("active");
            }
        });
    });

    // Κλείσιμο μενού αν γίνει κλικ εκτός
    document.addEventListener("click", function (event) {
        if (!menu.contains(event.target) && !menuButton.contains(event.target)) {
            menu.classList.remove("active");
            document.querySelectorAll(".menu-item").forEach(item => item.classList.remove("active")); // Κλείνουμε και τα υπομενού
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menuHeaders = document.querySelectorAll(".menu-header");

    menuHeaders.forEach(header => {
        header.addEventListener("click", function () {
            const parentItem = this.parentElement;
            const submenu = parentItem.querySelector(".submenu");

            // Αν το submenu είναι ανοιχτό, το κλείνουμε
            if (submenu.style.display === "block") {
                submenu.style.display = "none";
                parentItem.classList.remove("active");
            } else {
                // Κλείνουμε όλα τα υπόλοιπα submenus
                document.querySelectorAll(".submenu").forEach(sub => sub.style.display = "none");
                document.querySelectorAll(".menu-item").forEach(item => item.classList.remove("active"));

                // Ανοίγουμε το submenu του επιλεγμένου item
                submenu.style.display = "block";
                parentItem.classList.add("active");
            }
        });
    });
});
</script>
