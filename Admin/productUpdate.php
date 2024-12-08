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

    $prodName = $s->prodName;
    $prodAuthor = $s->prodAuthor;
    $prodDesc = $s->prodDesc;
    $prodPrice = $s->prodPrice;
    $prodStock = $s->prodStock;
    $categoryID = $s->categoryID;


?>

<head>
    <link rel="stylesheet" href="AdminCss/addProduct.css"> <!-- Link to your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
<form method="post" class="productForm" enctype="multipart/form-data">
<label for="id" style="color: blue;">Book Id : <?= $prodID ?></label>


    <label for="prodName">Book Title : </label>
    <?= html_text('prodName', 'maxlength="100"') ?>
    <?= err('prodName') ?>

    <label for="prodAuthor">Author : </label>
    <?= html_text('prodAuthor', 'maxlength="100"') ?>
    <?= err('prodAuthor') ?>

    <label for="prodDesc">Description : </label>
    <?= html_textarea('prodDesc', 'maxlength="200"') ?>
    <?= err('prodDesc') ?>

    <label for="prodPrice">Price(RM) : </label>
    <?= html_number('prodPrice', 'min="0" step="0.1"') ?>
    <?= err('prodPrice') ?>

    <label for="prodStock">Stock : </label>
    <?= html_number('prodStock', 'min="0" step="1"') ?>
    <?= err('prodStock') ?>

    <label for="categoryID">Book Category : </label>
    <?= html_select('categoryID', $_productCategory) ?>
    <?= err('categoryID') ?>

        
    <?php
    $baseURL = '../image/'; 
    if ($images) {
        // Loop through all fetched images
        foreach ($images as $image) {
            // Display each image with a fixed width and height
            echo '<img src="' . $baseURL . $image->imageUrl . '" width="200" height="150" style="margin-right: 20px;"/>';
        }
    } else {
        echo "No images found for this product.";
    }

    ?>
    
    <!-- <label for="prodPicture">Book Image : </label>
    <?= html_file('prodPictures', 'accept="image/*" multiple onchange="showPreview(event)"') ?>
    <div id="preview"></div>
    <?= err('prodPicture') ?>  -->

    <label for="prodPicture">Book Image: </label>
    <input type="file" id="prodPicture" name="prodPicture[]" accept="image/*" multiple onchange="showPreview(event)">
    <div id="preview"></div>
    <?= err('prodPicture') ?>
    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="window.location.href='a_home.php'">Back To Product List</button>  <!-- Redirect with JavaScript -->
    </section>
</form>
    
<?php
$baseURL = '../image/'; 
 if ($images) {
    // Loop through all fetched images
    foreach ($images as $image) {
        // Display each image with a fixed width and height
        echo '<img src="' . $baseURL . $image->imageUrl . '" width="200" height="150" style="margin-right: 20px;"/>';
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