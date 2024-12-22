<?php
include "header.php";
?>

<?php
$prodID = req('prodID');

$stm = $_db->prepare('SELECT * FROM product WHERE prodID = ?');
$stm->execute([$prodID]);

if ($stm->rowCount() == 0) {
    //PRODUCT NOT FOUND
    header('products.php');
    exit;
}

$product = $stm->fetch();

// Fetch product images
$stm_images = $_db->prepare('SELECT * FROM productimage WHERE prodID = ?');
$stm_images->execute([$prodID]);
$images = $stm_images->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Gallery</title>
    <title><?= htmlspecialchars($product->prodName) ?></title>
    <link rel="stylesheet" href="../css/productDetails.css">
    <link rel="stylesheet" href="../css/products.css">
</head>

<body>
    <div class="product-details-container">
        <div class="product-image-container">
            <!-- Left arrow -->
            <button class="arrow left" onclick="prevImage()">&#8249;</button>
            <!-- Image container -->
            <div class="image-container">
                <?php foreach ($images as $index => $image): ?>
                <img src="../../image/user/uploads/<?= htmlspecialchars($image->imageURL)?> "
                    style='width:500px; height:500px' alt="<?= htmlspecialchars($image->imageAltText) ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>">
                <?php endforeach; ?>
            </div>
            <!-- Right arrow -->
            <button class="arrow right" onclick="nextImage()">&#8250;</button>
        </div>

        <div class="product-info">
            <h1><?= htmlspecialchars($product->prodName) ?></h1>
            <i>by <b><?= htmlspecialchars($product->prodAuthor) ?></b></i>
            <br><br>
            <p><b>Price:</b> RM<?= htmlspecialchars($product->prodPrice) ?></p>
            <p><b>Stock Available:</b> <?= htmlspecialchars($product->prodStock) ?></p>
            <p><b>Description:</b> <?= htmlspecialchars($product->prodDesc) ?></p>
            <br><br>
            <button class="add-to-cart-btn"
                onclick="window.location.href='products.php'">Back</button>&nbsp;&nbsp;&nbsp;
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
    </div>
</body>

<script>
let currentIndex = 0;

function showImage(index) {
    const images = document.querySelectorAll('.product-image-container img');
    images.forEach((img, i) => {
        img.classList.remove('active');
        if (i === index) img.classList.add('active');
    });
}

function prevImage() {
    const images = document.querySelectorAll('.product-image-container img');
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    showImage(currentIndex);
}

function nextImage() {
    const images = document.querySelectorAll('.product-image-container img');
    currentIndex = (currentIndex + 1) % images.length;
    showImage(currentIndex);
}

// Ensure the first image is visible on page load
document.addEventListener('DOMContentLoaded', () => {
    showImage(currentIndex);
});
</script>


<?php
include "footer.php"
?>