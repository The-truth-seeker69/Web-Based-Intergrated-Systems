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
    <link rel="stylesheet" href="AdminCss/addProduct.css"> <!-- Link to your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Body Global Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #eef2f3;
  color: #333;
}

/* Main Product Details Container */
.productDetailsContainer {
  max-width: 900px;
  margin: 30px auto;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

/* Header Section */
.productDetailsContainer h1 {
  text-align: center;
  font-size: 24px;
  color: #555;
  margin-bottom: 20px;
}

/* Product Info Section */
.productInfo {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* Individual Info Item */
.productInfo .infoItem {
  flex: 1 1 calc(50% - 10px);
  font-size: 16px;
  margin-bottom: 10px;
  line-height: 1.6;
  color: #555;
}

/* Product Description */
.productDescription {
  padding: 10px 0;
  font-size: 16px;
  color: #555;
  line-height: 1.8;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  margin: 10px 0;
}

/* Product Images Section */
.productImagesContainer {
  display: flex;
  gap: 10px;
  justify-content: flex-start;
  margin: 10px 0;
}

.productImagesContainer img {
  max-width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Button Section */
.actionButtons {
  display: flex;
  justify-content: flex-start;
  gap: 10px;
  margin: 20px 0;
}

.actionButtons button {
  background-color: #007bff;
  border: none;
  color: #fff;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.actionButtons button:hover {
  background-color: #0056b3;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .productInfo .infoItem {
    flex: 1 1 100%;
  }

  .productImagesContainer img {
    max-width: 120px;
    height: 120px;
  }
}
</style>
</head>

<body>
<form method class="productForm">
<label for="prodId">Book Id: <?= $s->prodID ?></label>
<label for="prodName">Book Name: <?= $s->prodName ?></label>
<label for="prodAuthor">Author: <?= $s->prodAuthor ?></label>
<label for="prodDesc">Product Description: <?= $s->prodDesc ?></label>
<label for="prodPrice">Book Price(RM): <?= $s->prodPrice ?></label>
<label for="prodStock">Stock: <?= $s->prodStock ?></label>
<label for="prodStatus">Status: <?= $s->prodStatus ?></label>
<label for="catId">Category Id: <?= $categoryName ?></label>

    
<?php
$baseURL = '../image/'; 
 if ($images) {
    // Loop through all fetched images
    foreach ($images as $image) {
        // Display each image with a fixed width and height
        echo '<img src="' . $baseURL . $image->imageURL . '" width="200" height="150" style="margin-right: 20px;"/>';
    }
} else {
    echo "No images found for this product.";
}

?>
    <!-- <label for="prodPicture">Book Image : </label>
    <?= html_file('prodPictures', 'accept="image/*" multiple onchange="showPreview(event)"') ?>
    <div id="preview"></div>
    <?= err('prodPicture') ?>  -->

    <section>
        <button type="button" onclick="window.location.href='a_home.php'">Back To Product List</button>  <!-- Redirect with JavaScript -->
    </section>
</form>

</body>
</html>