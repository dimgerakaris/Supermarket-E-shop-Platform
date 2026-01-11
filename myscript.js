var typed = new Typed(".typing", {
    strings: [
        '<span style="color: #9C27B0;">Πότα!!</span>',
        '<span style="color: #9C27B0;">Αναψυκτικά!!</span>',
        '<span style="color: #9C27B0;">Τρόφιμα!!</span>',
        '<span style="color: #9C27B0;">Έπιπλα!!</span>',
        '<span style="color: #9C27B0;">Ηλεκτρικά είδη!!</span>',
        '<span style="color: #9C27B0;">και πολλά άλλα...</span>'
    ],
    typeSpeed: 60,
    backSpeed: 20,
	loop: true
});


document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.querySelector(".fas.fa-shopping-cart");
    const cartDropdown = document.querySelector(".cart-dropdown");

    // Εναλλαγή προβολής του dropdown με κλικ στο εικονίδιο
    cartIcon.addEventListener("click", function (e) {
        e.stopPropagation(); // Αποτροπή εξάπλωσης του click event
        cartDropdown.classList.toggle("active");
    });

    // Κλείσιμο του dropdown όταν κάνεις κλικ έξω από αυτό
    document.addEventListener("click", function () {
        cartDropdown.classList.remove("active");
    });

    // Πρόληψη κλεισίματος όταν κάνεις κλικ μέσα στο dropdown
    cartDropdown.addEventListener("click", function (e) {
        e.stopPropagation();
    });

    // Διαχείριση του κουμπιού "Συνέχεια Αγορών"
    const continueShoppingButton = document.querySelector(".continue-shopping-btn");
    continueShoppingButton.addEventListener("click", function () {
        cartDropdown.classList.remove("active");
    });
});



