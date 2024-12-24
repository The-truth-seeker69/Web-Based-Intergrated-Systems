<?php
$_title = 'Category';  // Set page title
include 'header.php';

$categoryID = req('categoryID');

//prepare where there need to slot in
//query select all only 
$stm = $_db->prepare('SELECT * FROM category WHERE categoryID = ?');
$stm->execute([$categoryID]);
$s = $stm->fetch();

    $categoryName = $s->categoryName;
    $categoryDesc = $s->categoryDesc;

    if (is_post()) {
        $categoryID         = req('categoryID'); // <-- From URL
        $categoryName       = req('categoryName');
        $categoryDesc       = req('categoryDesc');
    
        
        // Validate name
        if ($categoryName == '') {
            $_err['categoryName'] = 'Required';
        }
        else if (strlen($categoryName) > 100) {
            $_err['categoryName'] = 'Maximum length 100';
        }
        else {
            // Check if the product name already exists in the database, excluding the current product ID
            $checkStmt = $_db->prepare('SELECT COUNT(*) FROM category WHERE categoryName = ? AND categoryID != ?');
            $checkStmt->execute([$categoryName, $categoryID]);
            
            if ($checkStmt->fetchColumn() > 0) {  // If a product with the same name exists
                $_err['categoryName'] = 'Product name already exists';
            }
        }
        
        // Validate name
        if ($categoryDesc == '') {
            $_err['categoryDesc'] = 'Required';
        }
        else if (strlen($categoryDesc) > 200) {
            $_err['categoryDesc'] = 'Maximum length 200';
        }
       
    
        // Output
        if (!$_err) {
            $stm = $_db->prepare('UPDATE category SET categoryName = ?, categoryDesc = ? WHERE categoryID = ?');
            $stm->execute([$categoryName, $categoryDesc, $categoryID]);
    
            temp('info', 'Record updated');
            redirect('categoryDetail.php');
        }
    }
?>

<head>
    <link rel="stylesheet" href="../Style/admin/addProduct.css"> <!-- Link to your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div id="info"><?= temp('info') ?></div>
<form method="post" class="productForm">
<label for="categoryID" style="color: blue;">Category Id : <?= $categoryID ?></label>


    <label for="categoryName">Category Name :</label>
    <?= html_text('categoryName', 'maxlength="100"') ?>
    <?= err('categoryName') ?>

    <label for="categoryDesc">Description : </label>
    <?= html_textarea('categoryDesc', 'maxlength="200"') ?>
    <?= err('categoryDesc') ?>

    <section style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="window.location.href='categoryDetail.php'">Back To Category List</button> 
    </section>
</form>
</body>
</html>