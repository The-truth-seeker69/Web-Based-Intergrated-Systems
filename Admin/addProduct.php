<?php
$_title = 'Product';  // Set page title
include 'header.php';

//remember make sure only can upload the img only
if (is_post()) {
    // Input
    $prodName       = req('prodName');
    $prodAuthor     = req('prodAuthor');
    $prodDesc       = req('prodDesc');
    $prodPrice      = req('prodPrice');
    $prodStock      = req('prodStock');
    $prodStatus     ='';
    $book_category  = req('book_category');

    // Validate name
    if ($prodName == '') {
        $_err['prodName'] = 'Required';
    }
    elseif (strlen($prodName) > 100) {
        $_err['prodName'] = 'Must not exceed 100 characters';
    }
    else {
        // Check if the product name already exists in the database
        $checkStmt = $_db->prepare('SELECT COUNT(*) FROM product WHERE prodName = ?');
        $checkStmt->execute([$prodName]);
        if ($checkStmt->fetchColumn() > 0) {
            $_err['prodName'] = 'Product name already exists';
        }
    }

    // Validate desc not more than 100
    if ($prodDesc == '') {
        $_err['prodDesc'] = 'Required';
    }
    elseif (strlen($prodDesc) > 200) {
        $_err['prodDesc'] = 'Must not exceed 300 characters';
    }

    // Validate stock start from 0
    if ($prodStock == '') {
        $_err['prodStock'] = 'Required';
    }

    if ($prodStock == 0) {
            $prodStatus = 'OutOfStock';
    } elseif ($prodStock > 0) {
        $prodStatus = 'Available';
    }  

    // Validate price start from 0.1 but remember number cannot be -
    if ($prodPrice == '') {
        $_err['prodPrice'] = 'Required';
    }

    //book category not null
    if($book_category ==''){
        $_err['book_category'] = 'Required';
    }
    else if (!array_key_exists($book_category, $_productCategory)) {
        $_err['book_category'] = 'Invalid value';
    }

    //purpose : allow multiple photo upload
    $files = $_FILES['prodPicture'];

    if (empty($files['name'][0])) {
        $_err['prodPicture'] = 'Required';
    } else {
        // Validate each image file
        foreach ($files['name'] as $key => $fileName) {
            $fileTmpName = $files['tmp_name'][$key];
            $fileType = $files['type'][$key];

            if (!str_starts_with($fileType, 'image/')) {
                $_err['prodPicture'] = 'Must be an image';
                break;
            }
        }
    }

    // Output---all is okay no error
    if (!$_err) {
        $stm = $_db->prepare('INSERT INTO product(prodName, prodAuthor, prodDesc, prodPrice, prodStock, prodStatus, categoryID)
                              VALUES(?, ?, ?, ?, ?, ?, ?)');
        $stm->execute([$prodName, $prodAuthor, $prodDesc, $prodPrice, $prodStock, $prodStatus, $book_category]);
       
// Process each image file
$uploadedImages = [];
foreach ($files['name'] as $key => $fileName) {
    $prodId = $_db->lastInsertId();// last value in the database
    $photo = uniqid() . '.jpg'; // Unique file name
    $fileTmpName = $files['tmp_name'][$key];

    require_once '../lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($fileTmpName)
        ->thumbnail(200, 200)
        ->toFile("AdminImage/$photo", 'image/jpeg'); // Save the image

    // Insert image into the productimage table
    $stm1 = $_db->prepare('INSERT INTO productimage (imageURL, imageAltText, prodID) VALUES (?, ?, ?)');
    $stm1->execute([$photo, $prodName, $prodId]); // Insert each image

    if ($stm1->rowCount() > 0) {
        $uploadedImages[] = $photo; // Store the uploaded image's filename
    } else {
        $_err['prodPicture'] = 'Failed to insert image';
        break;
    }
        }
        if (!$_err) {
            temp('info', 'Product and images inserted successfully');
            redirect('product.php');
        }
    }
}
?>

<head>
    <link rel="stylesheet" href="../Style/admin/addProduct.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
        <div id="info"><?= temp('info') ?></div>
<form method="post" class="productForm" enctype="multipart/form-data">
    <!-- <label for="prodId">Product Id : </label>
    <?= html_text('prodId', 'maxlength="10" data-upper') ?>
    <?= err('prodId') ?> -->

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

    <label for="book_category">Book Category : </label>
    <?= html_select('book_category', $_productCategory) ?>
    <?= err('book_category') ?>

    <label for="prodPicture">Book Image: </label>
    <input type="file" id="prodPicture" name="prodPicture[]" accept="image/*" multiple onchange="showPreview(event)">
    <div id="preview"></div>
    <?= err('prodPicture') ?>

    <section style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="window.location.href='product.php'">Back To Product List</button>  <!-- Redirect with JavaScript -->
    </section>
</form>

</body>
</html>
<script>
   function showPreview(event) {
    var files = event.target.files;
    var previewContainer = $('#preview');
    
    previewContainer.empty(); // Clear previous previews

    $.each(files, function(index, file) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var img = $('<img>', {
                src: e.target.result,
                css: {
                    width: '100px',
                    margin: '5px'
                }
            });
            previewContainer.append(img);
        };
        
        reader.readAsDataURL(file);
    });
}
</script>