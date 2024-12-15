// Toggle Cart Visibility
function toggleCart() {
    const cart = document.getElementById("cart");
    cart.classList.toggle("hidden");
}

// Increase Quantity
function increaseQty(button) {
    const input = button.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    updateTotal();
}

// Decrease Quantity
function decreaseQty(button) {
    const input = button.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
    updateTotal();
}

// Update Total Price
function updateTotal() {
    const items = document.querySelectorAll(".cart-item");
    let total = 0;

    items.forEach((item) => {
        const price = parseFloat(item.querySelector(".item-price").textContent.substring(1));
        const quantity = parseInt(item.querySelector("input[type='number']").value);
        const isChecked = item.querySelector(".select-item").checked;

        if (isChecked) {
            total += price * quantity;
        }
    });

    document.getElementById("total-price").textContent = `$${total.toFixed(2)}`;
}
