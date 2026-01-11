function checkStock(productId, stock) {
    const quantityInput = document.getElementById('quantity_' + productId);
    const addToCartButton = document.getElementById('add-to-cart-' + productId);
    const stockMessage = document.getElementById('stock-message-' + productId);

    if (parseInt(quantityInput.value) > stock) {
        quantityInput.value = stock;
        addToCartButton.disabled = true;
        stockMessage.style.display = 'block';
    } else {
        addToCartButton.disabled = false;
        stockMessage.style.display = 'none';
    }
}

function validateStock(productId, stock) {
    const quantityInput = document.getElementById('quantity_' + productId);
    if (parseInt(quantityInput.value) > stock) {
        alert('Δεν υπάρχει τόσο απόθεμα');
        return false;
    }
    return true;
}