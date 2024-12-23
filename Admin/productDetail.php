<?php
$_title = 'Product';  // Set page title
require '../_base.php';
include '../head.php';

$prodID = req('prodID');


//prepare where there need to slot in
//query select all only 
$stm = $_db->prepare('SELECT * FROM product WHERE prodID = ?');
$stm->execute([$prodID]);
$s = $stm->fetch();

$stm1 = $_db->prepare('SELECT * FROM productimage WHERE prodID = ?');
$stm1->execute([$prodID]);
$images = $stm1->fetchAll();

$categoryID = $s->categoryID; // The categoryID from product table

try {
    // Prepare a query to get the category name
    $stmt = $_db->prepare('SELECT categoryName FROM category WHERE categoryID = ?');
    $stmt->execute([$categoryID]);
    
    // Fetch the category name
    $categoryName = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle errors
    echo "Error fetching category: " . $e->getMessage();
    $categoryName = 'Unknown Category';
}
?>


<head>
    <link rel="stylesheet" href="AdminCss/viewProduct.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>


</style>
</head>

<body>
<form method="post" class="productForm">
    <h1>Book Details</h1>
    <label>Book ID: <span><?= $s->prodID ?></span></label>
    <label>Book Name: <span><?= $s->prodName ?></span></label>
    <label>Author: <span><?= $s->prodAuthor ?></span></label>
    <label>Product Description: <span><?= $s->prodDesc ?></span></label>
    <label>Book Price (RM): <span><?= $s->prodPrice ?></span></label>
    <label>Stock: <span><?= $s->prodStock ?></span></label>
    <label>Status: <span><?= $s->prodStatus ?></span></label>
    <label>Category: <span><?= $categoryName ?></span></label>
    <label>Image: </label>
    <div class="productImagesContainer">
<?php
$baseURL = 'AdminImage/';
if ($images) {
    foreach ($images as $image) {
        $imagePath = $baseURL . $image->imageURL;

        // Check inside the folder got this picture exist or not 
        //if got this picture
        if (file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($image->imageAltText) . '" />';
        } else {
            // Display the imageAltText from the database if the file doesn't exist
            echo '<p>' . htmlspecialchars($image->imageAltText) . '</p>';
        }
    }
} else {
    echo '<p>No images found for this product.</p>';
}
?>

    </div>
    
    <div class="actionButtons">
        <button type="button" onclick="window.location.href='product.php'">Back to Product List</button>
    </div>
</form>


</body>
</html>