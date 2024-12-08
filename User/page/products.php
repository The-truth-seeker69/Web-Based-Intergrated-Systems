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
    <div class="product-controls">
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search products...">
            <button id="searchButton">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="filter-bar">
            <select id="filterSelect">
                <option value="all">All</option>
                <option value="category1">Category 1</option>
                <option value="category2">Category 2</option>
                <option value="priceLowHigh">Price: Low to High</option>
                <option value="priceHighLow">Price: High to Low</option>
            </select>
        </div>
    </div>

    <div class="product-gallery">
        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>

        <div class="product">
            <div class="product-image-container">
                <button class="arrow left" onclick="prevImage(1)">&#8249;</button>
                <img src="../../image/img1.jpg" alt="I AM PRODUCT ALT TEXT">
                <button class="arrow right" onclick="nextImage(1)">&#8250;</button>
            </div>
            <h3>I AM PRODUCT</h3>
            <p><b>RM100</b></p>
            <p>Stock Available: 177</p>
            <button class="details-btn">Details</button>
            <button class="cart-btn">Add to Cart</button>
        </div>
    </div>

    <script src="./script/app.js"></script>
</body>

<?php
include "../footer.php"
?>