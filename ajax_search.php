<?php
// Σύνδεση με τη βάση δεδομένων
include 'cartDB_connection.php';

if (isset($_GET['ajax_search']) && !empty(trim($_GET['ajax_search']))) {
    $search_query = trim($_GET['ajax_search']);

    // Εκτέλεση αναζήτησης στη βάση
    $stmt = $conn->prepare("SELECT name, category, image FROM products WHERE name LIKE ?");
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param('s', $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    // Mapping κατηγοριών και λέξεων-κλειδιών
    $category_map = [
        'Είδη Παντοπωλείου' => ['zymarika', 'zaxarh', 'aleyria', 'saltses', 'mpaxarika', 'ladi', 'ryzia', 'ospria', 'ntomatika'],
        'Νερά, Αναψυκτικά, Χυμοί' => ['nera', 'anapsyktika', 'xymoi'],
        'Κάβα' => ['krasi', 'mpira', 'pota'],
        'Γάλατα, Κρέμες Γάλακτος' => ['gala', 'krema'],
        'Αλλαντικά' => ['zampon', 'mpeikon', 'loykanika', 'salamia'],
        'Φρέσκο Ψάρι, Θαλασσινά' => ['psaria', 'malakia', 'ostrakoeidh', 'salamia'],
        'Μπισκότα, Ζαχαρώδη, Σοκολάτες' => ['mpiskota', 'sokolata'],
        'Σνακ, Ξηροί Καρποί' => ['snaks', 'kshroiKarpoi'],
        'Είδη πρωινού, Ροφήματα' => ['dhmhtriaka', 'rofhmata', 'ostrakoeidi'],
        'Βρεφικά Τρόφιμα' => ['babyGala', 'babyFoods'],
        'Χαρτικά, Πάνες, Σερβιέτες' => ['xartika', 'panesEnhlikwn', 'panesBrefikes'],
        'Είδη προσωπικής Υγιεινής, Καλλυντικά' => ['malliwn', 'swma', 'ksyrisma', 'proswpo', 'stoma'],
        'Απορρυπαντικά, Είδη καθαρισμού' => ['royxwn', 'piata', 'katharistika', 'synergaKatharismoy'],
    ];
    
    //Μεταφράσεις κατηγοριών προϊόντων για καλύτερη εμφάνιση στο search results
    $category_translation = [
        'zymarika' => 'Ζυμαρικά',
        'zaxarh' => 'Ζάχαρη',
        'aleyria' => 'Αλεύρια',
        'saltses' => 'Σάλτσες',
        'mpaxarika' => 'Μπαχαρικά',
        'ladi' => 'Λάδι',
        'ryzi' => 'Ρύζι',
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
        'psaria' => 'Ψάρια',
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
    //Ανακατεύθυνση προϊόντος με βάση την σελίδα .php
    $category_to_file = [
        'zymarika' => 'zymarika.php',
        'zaxarh' => 'zaxari.php',
        'aleyria' => 'aleyria.php',
        'saltses' => 'saltses.php',
        'mpaxarika' => 'mpaxarika.php',
        'ladi' => 'ladi.php',
        'ryzi' => 'ryzia.php',
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
        'psaria' => 'psari.php',
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
    

    // Δημιουργία HTML αποτελεσμάτων
    $output = '<ul>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $image_path = $row['image']; // Παίρνουμε το path της εικόνας από τη βάση
            $output .= '<li style="display: flex; align-items: center; margin-bottom: 10px;">
                            <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($row['name']) . '" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                            <div>
                                <a href="' . ($category_to_file[$row['category']] ?? '#') . '" style="color: #FF6600; text-decoration: none;" onmouseover="this.style.color=\'#E64A19\'" onmouseout="this.style.color=\'#FF6600\'">
                                    ' . htmlspecialchars($row['name']) . '
                                </a>
                                <br>
                                <span>Κατηγορία: ' . htmlspecialchars($category_translation[$row['category']] ?? $row['category']) . '</span>
                            </div>
                        </li>';
        }
    } else {
        $output .= '<li style="color: #E64A19; text-align: center;">Δεν βρέθηκαν αποτελέσματα.</li>';
    }
    $output .= '</ul>';

    $stmt->close();

    // Επιστροφή HTML στον browser
    echo $output ?: '<ul><li style="color: #E64A19; text-align: center;">Δεν βρέθηκαν αποτελέσματα.</li></ul>';
    exit;

}
?>
