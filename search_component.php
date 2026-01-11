<link rel="stylesheet" type="text/css" href="css/productsCSS.css">

<div class="search-container">
    <form id="searchForm">
    <h3 class="search-title">Αναζήτηση προϊόντων</h3>

        <input type="text" name="search" id="searchInput" placeholder="Αναζήτηση προϊόντων ή κατηγοριών" class="search-input">
        <div id="dropdownResults" class="dropdown-results"></div>
    </form>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const query = this.value;
        const resultsContainer = document.getElementById('dropdownResults');

        if (query.length >= 2) {
            fetch(`ajax_search.php?ajax_search=${encodeURIComponent(query)}`)
                .then((response) => response.text())
                .then((data) => {
                    resultsContainer.innerHTML = data;
                    resultsContainer.style.display = 'block';
                })
                .catch((error) => {
                    console.error('Error:', error);
                    resultsContainer.style.display = 'none';
                });
        } else {
            resultsContainer.style.display = 'none';
        }
    });
</script>

<?php

?>

