<?php
include "header.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Gallery</title>
    <link rel="stylesheet" href="../css/products.css">
    <script src="../script/cart.js"></script>
</head>

<?php
$search = req("searchInput");
$category = req("category");
if ($search) {
    $stm = $_db->prepare('SELECT * FROM product WHERE prodName LIKE ?');
    $stm->execute(["%$search%"]);
    if ($stm->rowCount() == 0) {
        temp("info", "No Such Item Found.");
    }
}else if($category){
    $stm = $_db->prepare('SELECT * FROM product WHERE categoryID = (SELECT categoryID FROM category WHERE categoryName = ?)');
    $stm->execute(["$category"]);
    if ($stm->rowCount() == 0) {
        temp("info", "No Such Item Found.");
    }
} else {
    $stm = $_db->prepare('SELECT * FROM product');
    $stm->execute();
}

$arr = $stm->fetchAll();
?>

<body>
    <div class="product-controls">
        <form class="search-form" method="post">
            <div class="search-bar">
                <?= html_search('searchInput', 'placeholder="Enter Book Name"') ?>
                <button id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        <div class="filter-bar">
            <select id="filterSelect" onchange="filterCategory()">
                <option value="all">All</option>
                <?php
                    $stm_cat = $_db->prepare('SELECT * FROM category');
                    $stm_cat->execute();
                    $arr_cat = $stm_cat->fetchAll();

                    $decodedCategory = urldecode($category);
                    foreach($arr_cat as $cat){
                        if ($decodedCategory && $decodedCategory == $cat->categoryName) {
                            echo "<option value=\"$cat->categoryName\" selected>$cat->categoryName</option>";
                        } else {
                            echo "<option value=\"$cat->categoryName\">$cat->categoryName</option>";
                        }
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="product-gallery">
        <?php foreach ($arr as $p): ?>
        <div class="product">
            <div class="product-image-container">
                <?php
                    // Fetch all product images for the current product
                    $stm2 = $_db->prepare('SELECT * FROM productimage WHERE prodID = ?');
                    $stm2->execute([$p->prodID]);
                    $arr2 = $stm2->fetchAll();
                    ?>

                <!-- Product Image Slider -->
                <button class="arrow left" onclick="prevImage(this)">&#8249;</button>
                <div class="image-container">
                    <?php foreach ($arr2 as $index => $image): ?>
                    <img src="../../image/user/uploads/<?= htmlspecialchars($image->imageURL) ?>"
                        alt="<?= htmlspecialchars($image->imageAltText) ?>" class="<?= $index === 0 ? 'active' : '' ?>"
                        style="width:300px; height:180px;">
                    <?php endforeach; ?>
                </div>
                <button class="arrow right" onclick="nextImage(this)">&#8250;</button>
            </div>

            <h3><?= $p->prodName ?></h3>
            <p><b>RM<?= $p->prodPrice ?></b></p>
            <p>Stock Available: <?= $p->prodStock ?></p>
            <button class="details-btn"
                onclick="window.location.href='productDetails.php?prodID=<?= $p->prodID ?>'">Detail</button>
            <button class="cart-btn">Add to Cart</button>
        </div>
        <?php endforeach; ?>


    </div>

    <script src="./script/app.js"></script>
</body>

<?php
include "footer.php"
?>

<script>
function prevImage(button) {
    // Find the product box where the button was clicked
    const productBox = button.closest('.product-image-container');
    const images = productBox.querySelectorAll('.image-container img');
    let activeIndex = Array.from(images).findIndex(img => img.classList.contains('active'));

    // Go to the previous image or loop to the last one
    if (activeIndex > 0) {
        images[activeIndex].classList.remove('active');
        images[activeIndex - 1].classList.add('active');
    } else {
        images[activeIndex].classList.remove('active');
        images[images.length - 1].classList.add('active');
    }
}

function nextImage(button) {
    // Find the product box where the button was clicked
    const productBox = button.closest('.product-image-container');
    const images = productBox.querySelectorAll('.image-container img');
    let activeIndex = Array.from(images).findIndex(img => img.classList.contains('active'));

    // Go to the next image or loop to the first one
    if (activeIndex < images.length - 1) {
        images[activeIndex].classList.remove('active');
        images[activeIndex + 1].classList.add('active');
    } else {
        images[activeIndex].classList.remove('active');
        images[0].classList.add('active');
    }
}

function filterCategory() {
    const filterSelect = document.getElementById("filterSelect").value;
    if (filterSelect === "all") {
        window.location.href = "products.php";
    } else {
        window.location.href = "products.php?category=" + encodeURIComponent(filterSelect);
    }
}
</script>