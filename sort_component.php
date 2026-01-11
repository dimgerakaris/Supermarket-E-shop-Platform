<?php
// Διαχείριση παραμέτρων ταξινόμησης
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'asc'; // Default: Αύξουσα
$order_by = $sort_order === 'desc' ? 'DESC' : 'ASC';

// Προσθήκη συνθήκης ταξινόμησης στο query (αν δεν υπάρχει ήδη)
if (!isset($sql)) {
    die("Το query δεν είναι ορισμένο πριν την ταξινόμηση.");
}
$sql .= " ORDER BY price $order_by";
?>

<link rel="stylesheet" type="text/css" href="css/myCSS.css">

<!-- HTML Select για ταξινόμηση -->
<div class="sort-container">
    <form method="GET" action="">
        <h3 class="sort-title">Ταξινόμηση προϊόντων</h3> <!-- Προσθήκη τίτλου -->
        <select class="sort-dropdown" name="sort" id="sortSelect" onchange="this.form.submit()">
            <option value="asc" <?php if ($sort_order === 'asc') echo 'selected'; ?>>Τιμή: Αύξουσα</option>
            <option value="desc" <?php if ($sort_order === 'desc') echo 'selected'; ?>>Τιμή: Φθίνουσα</option>
        </select>
    </form>
</div>
