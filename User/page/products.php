<?php include "../header.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Gallery</title>
    <link rel="stylesheet" href="../css/products.css">
</head>
<body>
    <div class="product-gallery">
        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="product1.png" alt="Hot Wheels Action Loop Cyclone Challenge">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>Hot Wheels Action Loop Cyclone Challenge</h3>
            <p>Price: RM199.5</p>
            <p>Total Sold: 5</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(2)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="Ken Item">
                <button class="arrow right" onclick="nextImage(2)">&#8250;</button>
            </div>
            <h3>Ken Item</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(2)">&#8249;</button>
                <img src="product2.png" alt="Ken Item">
                <button class="arrow right" onclick="nextImage(2)">&#8250;</button>
            </div>
            <h3>Ken Item</h3>
            <p>Price: RM100</p>
            <p>Total Sold: 2</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(2)">&#8249;</button>
                <img src="product2.png" alt="Ken Item">
                <button class="arrow right" onclick="nextImage(2)">&#8250;</button>
            </div>
            <h3>Ken Item</h3>
            <p>Price: RM100</p>
            <p>Total Sold: 2</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(2)">&#8249;</button>
                <img src="product2.png" alt="Ken Item">
                <button class="arrow right" onclick="nextImage(2)">&#8250;</button>
            </div>
            <h3>Ken Item</h3>
            <p>Price: RM100</p>
            <p>Total Sold: 2</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <!-- Add more product blocks as needed -->
    </div>

<script src="./script/app.js"></script>
</body>

<?php
include "../footer.php"
?>