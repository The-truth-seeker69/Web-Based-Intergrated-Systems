// Toggle Cart Visibility
function toggleCart() {
  const cart = document.getElementById("cart");
  cart.classList.toggle("hidden");
}

function updateCartQty(button, action, prodID) {
  const currentQtyInput = button.parentElement.querySelector(
    'input[type="number"]'
  );
  let currentQty = parseInt(currentQtyInput.value);

  // Adjust the quantity
  if (action === "increase") {
    currentQty += 1;
  } else if (action === "decrease" && currentQty > 1) {
    currentQty -= 1;
  }

  // Update the UI immediately
  currentQtyInput.value = currentQty;

  // Send the updated quantity to the server
  $.ajax({
    url: "../../../User/page/update_cart.php",
    method: "POST",
    data: { prodID: prodID, newQty: currentQty },
    success: function (response) {
      if (response === "success") {
        console.log("Cart updated successfully!");
      } else {
        alert("Failed to update cart. Please try again.");
      }
    },
  });
}
