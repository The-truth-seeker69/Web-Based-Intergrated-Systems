<?php
$_title = 'Product';  // Set page title
require '../_base.php';
include '../head.php';

//remember make sure only can upload the img only
if (is_post()) {
    // Input
    $categoryName       = req('categoryName');
    $categoryDesc       = req('categoryDesc');

    // Validate name
    if ($categoryName == '') {
        $_err['categoryName'] = 'Required';
    }
    elseif (strlen($categoryName) > 100) {
        $_err['categoryName'] = 'Must not exceed 100 characters';
    }

    // Validate desc not more than 100
    if ($categoryDesc == '') {
        $_err['categoryDesc'] = 'Required';
    }
    elseif (strlen($categoryDesc) > 200) {
        $_err['categoryDesc'] = 'Must not exceed 300 characters';
    }

    // Check if the category already exists
    $stmt = $_db->prepare('SELECT COUNT(*) FROM category WHERE categoryName = ?');
    $stmt->execute([$categoryName]);

    if ($stmt->fetchColumn() > 0) {
        temp('info', 'Category Already exist !!!');
            redirect('categoryDetail.php');
        // Insert the category into the database
        $stm = $_db->prepare('INSERT INTO category(categoryName,categoryDesc)
                              VALUES(?, ?)');
     
        if ($stmtInsert->execute([$categoryName, $categoryDesc])) {
            temp('info', 'Category added successfully');
            redirect('categoryDetail.php');
        } else {
            temp('info', 'Fail to add new category, Try later.');
            redirect('categoryDetail.php');
        }
    }
}
?>

<head>
    <link rel="stylesheet" href="AdminCss/addCategory.css"> <!-- Link to your CSS file -->
</head>

<body>
    <h1>Add New Category</h1>      
<div id="info"><?= temp('info') ?></div>
<form method="post" class="categoryForm">

    <label for="categoryName">Category Name : </label>
    <?= html_text('categoryName', 'maxlength="100"') ?>
    <?= err('categoryName') ?>


    <label for="categoryDesc">Description : </label>
    <?= html_textarea('categoryDesc', 'maxlength="200"') ?>
    <?= err('categoryDesc') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="window.location.href='categoryDetail.php'">Back To Category List</button>  <!-- Redirect with JavaScript -->
    </section>
</form>

</body>
</html>
