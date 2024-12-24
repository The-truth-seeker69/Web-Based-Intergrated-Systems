<?php
$_title = 'Product';  // Set page title
include 'header.php';

$prodID = req('prodID');

// Fetch product data
$stm = $_db->prepare('SELECT * FROM product WHERE prodID = ?');
$stm->execute([$prodID]);
$s = $stm->fetch();

$stm1 = $_db->prepare('SELECT * FROM productimage WHERE prodID = ?');
$stm1->execute([$prodID]);
$images = $stm1->fetchAll();


//retrieve value
$prodName = $s->prodName;
$prodAuthor = $s->prodAuthor;
$prodDesc = $s->prodDesc;
$prodPrice = $s->prodPrice;
$prodStock = $s->prodStock;
$categoryID = $s->categoryID;

//use to retrieve
$currentCategory = $_db->prepare('SELECT categoryName FROM category WHERE categoryID = ?');
$currentCategory->execute([$categoryID]);
$currentCategoryName = $currentCategory->fetchColumn();

// Handle form submission
if (is_post()) {
    // Update product details
    $prodName = req('prodName');
    $prodAuthor = req('prodAuthor');
    $prodDesc = req('prodDesc');
    $prodPrice = req('prodPrice');
    $prodStock = req('prodStock');
    $categoryID = req('categoryID');


    // Validate name
    if ($prodName == '') {
        $_err['prodName'] = 'Required';
    } elseif (strlen($prodName) > 100) {
        $_err['prodName'] = 'Must not exceed 100 characters';
    } else {
        // Check if the product name already exists in the database, excluding the current product ID
        $checkStmt = $_db->prepare('SELECT COUNT(*) FROM product WHERE prodName = ? AND prodID != ?');
        $checkStmt->execute([$prodName, $prodID]);

        if ($checkStmt->fetchColumn() > 0) {  // If a product with the same name exists
            $_err['prodName'] = 'Product name already exists';
        }
    }

    // Validate desc not more than 100
    if ($prodDesc == '') {
        $_err['prodDesc'] = 'Required';
    } elseif (strlen($prodDesc) > 200) {
        $_err['prodDesc'] = 'Must not exceed 300 characters';
    }

    // Validate stock
    if ($prodStock === '') {
        $_err['prodStock'] = 'Required';
    } elseif (!is_numeric($prodStock)) {
        $_err['prodStock'] = 'Stock must be a number';
    } elseif ($prodStock < 0) {
        $_err['prodStock'] = 'Stock cannot be negative';
    } else {
        // Set product status based on stock quantity
        if ($prodStock == 0) {
            $prodStatus = 'OutOfStock';
        } elseif ($prodStock > 0) {
            $prodStatus = 'Available';
        }
    }


    // Validate price start from 0.1 but remember number cannot be -
    if ($prodPrice == '') {
        $_err['prodPrice'] = 'Required';
    }

    //book category not null
    if ($categoryID == '') {
        $_err['categoryID'] = 'Required';
    } else if (!array_key_exists($categoryID, $_productCategory)) {
        $_err['categoryID'] = 'Invalid value';
    } 

    if (!$_err) {
        $stm = $_db->prepare('UPDATE product SET prodName = ?, prodAuthor = ?, prodDesc = ?, prodPrice = ?, prodStock = ?, prodStatus = ?, categoryID = ? WHERE prodID = ?');
        $stm->execute([$prodName, $prodAuthor, $prodDesc, $prodPrice, $prodStock, $prodStatus, $categoryID, $prodID]);


        // Delete selected images
        if (!empty(req('deleteImages'))) {
            foreach (req('deleteImages') as $imageID) {
                // Fetch image URL
                $stm = $_db->prepare('SELECT imageURL FROM productimage WHERE imageID = ?');
                $stm->execute([$imageID]);
                $image = $stm->fetch();

                if ($image) {
                    $filePath = 'AdminImage/' . $image->imageURL;
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete file from server
                    }

                    // Delete from database
                    $stm = $_db->prepare('DELETE FROM productimage WHERE imageID = ?');
                    $stm->execute([$imageID]);
                }
            }
        }

        // Handle new image uploads
        if (!empty($_FILES['prodPicture']['name'][0])) {
            foreach ($_FILES['prodPicture']['tmp_name'] as $index => $tmpName) {
                $newFileName = uniqid() . '-' . $_FILES['prodPicture']['name'][$index];
                $destination = 'AdminImage/' . $newFileName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $stm = $_db->prepare('INSERT INTO productimage (prodID, imageURL) VALUES (?, ?)');
                    $stm->execute([$prodID, $newFileName]);
                }
            }
        }
        if (!$_err) {
            //using java script since i want print the information, doesn't using redirect because it will direct ignore js
            echo "<script>alert('Product updated successfully!'); window.location.href='productDetail.php?prodID=" . $prodID . "';</script>";
            exit;
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?></title>
    <link rel="stylesheet" href="../Style/admin/updateProduct.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <form method="post" class="productForm" enctype="multipart/form-data">

        <label for="id" style="color: blue;">Book Id: <?= $prodID ?></label>

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

        <label for="categoryID">Book Category : <strong><?= htmlspecialchars($currentCategoryName) ?></strong></p></label>

<select name="categoryID" id="categoryID">
    <?php foreach ($_productCategory as $key => $value): ?>
        <option value="<?= $key ?>" <?= $key == $categoryID ? 'selected' : '' ?>>
            <?= htmlspecialchars($value) ?>
        </option>
    <?php endforeach; ?>
</select>

        <h3>Existing Images:</h3>
        <?php
        $baseURL = 'AdminImage/';
        if ($images) {
            foreach ($images as $image) {
                echo '<div>';
                echo '<img src="' . $baseURL . $image->imageURL . '" width="200" height="150">';
                echo '<label><input type="checkbox" name="deleteImages[]" value="' . $image->imageID . '"> Delete</label>';
                echo '</div>';
            }
        } else {
            echo "No images found for this product.";
        }
        ?>

        <label for="prodPicture">Upload New Images:</label>
        <input type="file" id="prodPicture" name="prodPicture[]" accept="image/*" multiple
            onchange="showPreview(event)">
        <div id="preview"></div>

        <section style="display: flex; justify-content: center; align-items: center; gap: 10px;">
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
            <button type="button" onclick="window.location.href='product.php'">Back To Product List</button>
        </section>

    </form>

    <script>
        function showPreview(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = ''; // Clear existing images
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = 200;
                    img.style.marginRight = '10px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(files[i]);
            }
        }
    </script>

</body>

</html>