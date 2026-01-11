function addToCart(productIdentifier, price, useName = false) {
    console.log("DEBUG: Product ID:", productIdentifier, "Price:", price, "Use Name:", useName);

    if (typeof productIdentifier === "undefined" || productIdentifier === null) {
        console.error("ERROR: Το productIdentifier είναι undefined ή null!");
        return;
    }

    const quantityInput = document.getElementById(`quantity_${productIdentifier}`);
    if (!quantityInput) {
        console.error(`ERROR: Δεν βρέθηκε το input ποσότητας για το προϊόν με ID: ${productIdentifier}`);
        return;
    }

    const quantity = quantityInput.value;
    const requestBody = useName
        ? { product_name: productIdentifier, quantity: quantity, price: price }
        : { product_id: productIdentifier, quantity: quantity, price: price };

    console.log('Request body:', requestBody);

    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(requestBody),
    })
    .then((response) => response.json())
    .then((data) => {
        console.log("Server Response:", data);
        if (data.status === 'success') {
            updateCartCount();
            updateCartDropdown(); // Update the cart dropdown
        } else {
            console.error("Server Error:", data.message);
        }
    })
    .catch((error) => console.error('Fetch Error:', error));
}
const data = {
    product_id: productIdentifier,
    quantity: quantity,
    price: price
};


// Προσθήκη debug για αποστολή δεδομένων
console.log("Αποστολή δεδομένων:", data);

fetch('add_to_cart.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(requestBody)
})

.then(response => response.json())
.then(result => {
    // Εμφάνιση του αποτελέσματος στο console
    console.log("Αποτέλεσμα από τον server:", result);

    if (result.status === 'success') {
        alert(result.message);
    } else {
        console.error(result.message);
    }
})
.catch(error => console.error('Σφάλμα:', error));




function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.total_items ?? 0; // Ανανέωση με το σωστό πλήθος
            }
        })
        .catch(error => console.error('Error:', error));
}


function updateCartDropdown() {
    fetch('get_cart_items.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const cartItemsContainer = document.querySelector('.cart-items');
                cartItemsContainer.innerHTML = ''; // Clear existing items

                let totalAmount = 0;

                data.items.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.classList.add('cart-item');
                    const price = parseFloat(item.price); // Ensure price is a number
                    const itemTotal = price * item.quantity;
                    totalAmount += itemTotal;

                    itemElement.innerHTML = `
                        <img src="${item.product_image}" alt="${item.product_name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <h4 class="cart-item-name">${item.product_name}</h4>
                            <p class="cart-item-quantity"><strong>Ποσότητα: ${item.quantity}</strong></p>
                            <p class="cart-item-price"><strong>Τιμή: €${price.toFixed(2)}</strong></p>
                            <p class="cart-item-total"><strong>Σύνολο: €${itemTotal.toFixed(2)}</strong></p>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemElement);
                });

                console.log("Συνολικό ποσό:", totalAmount);

                // Update total amount element
                const totalAmountElement = document.querySelector('.cart-total h4');
                totalAmountElement.innerHTML = `Σύνολο: €${totalAmount.toFixed(2)}`;
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}