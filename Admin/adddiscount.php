<?php
$_title = 'Discount';  // Set page title
require '../_base.php';
include '../head.php';

//remember make sure only can upload the img only
if (is_post()) {
    // Input
    $discountCode           = req('discountCode');
    $discountPercentage     = req('discountPercentage');
    $discountDesc           = req('discountDesc');
    $startDate              = req('startDate');
    $endDate                = req('endDate');

    // Validate name
     // Validate code
     if ($discountCode == '') {
        $_err['discountCode'] = 'Required';
    }
    else if (strlen($discountCode) > 100) {
        $_err['discountCode'] = 'Maximum length 100';
    }
    else {
        // Check if the code already exists in the database, excluding the current code ID
        $checkStmt = $_db->prepare('SELECT COUNT(*) FROM discountCode WHERE discountCode = ?');
        $checkStmt->execute([$discountCode]);
        
        if ($checkStmt->fetchColumn() > 0) {  // If a product with the same name exists
            $_err['discountCode'] = 'This Code already exists';
        }
    }
    
    // Validate desc
    if ($discountDesc == '') {
        $_err['discountDesc'] = 'Required';
    }
    else if (strlen($discountDesc) > 200) {
        $_err['discountDesc'] = 'Maximum length 200';
    }

    //validate the price
    if ($discountPercentage == '') {
        $_err['discountPercentage'] = 'Required';
    }

    //validate start date 
    if ($startDate == '') {
        $_err['startDate'] = 'Required';
    }

    //validate end date
    if ($endDate == '') {
        $_err['endDate'] = 'Required';
    }

    //convert to timestamp //more late more bigger the value
    if (strtotime($endDate) < strtotime($startDate)) {
        $_err['endDate'] = 'End date cannot be before start date';
    }
   
    if (!$_err) {
        $stm = $_db->prepare('INSERT INTO discountcode(discountCode, discountPercentage, discountDesc, startDate, endDate)
                              VALUES(?, ?, ?, ?, ?)');

    if ($stm->execute([$discountCode, $discountPercentage, $discountDesc, $startDate, $endDate])) {
        temp('info', 'New Discount added successfully');
        redirect('discount.php');
    } else {
            temp('info', 'Fail to add new discount, Try later.');
            redirect('discount.php');
            }
        }
} 
?>

<head>
    <link rel="stylesheet" href="AdminCss/addCategory.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Add New Category</h1>      
<div id="info"><?= temp('info') ?></div>
<form method="post" class="categoryForm">

    
<label for="discountCode">Discount Code :</label>

    <?= html_text('discountCode', 'maxlength="100"') ?>
    <?= err('discountCode') ?>

    <label for="discountPercentage">Percentage (%) : </label>
    <?= html_number('discountPercentage', 'min="0" step="1"') ?>
    <?= err('discountPercentage') ?>

    <label for="discountDesc">Desc : </label>
    <?= html_textarea('discountDesc', 'maxlength="200"') ?>
    <?= err('discountDesc') ?>

    <label for="startDate">Start Date  : </label>
    <?= html_datetime('startDate') ?>
    <?= err('startDate') ?>

    <label for="endDate">End Date : </label>
    <?= html_datetime('endDate') ?>
    <?= err('endDate') ?>

    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="window.location.href='discount.php'">Back To Discount List</button>  <!-- Redirect with JavaScript -->
    </section>
</form>

</body>
</html>
