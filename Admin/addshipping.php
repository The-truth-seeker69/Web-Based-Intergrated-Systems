<?php
$_title = 'Category';  // Set page title
require '../_base.php';
include '../head.php';

//remember make sure only can upload the img only
if (is_post()) {
    // Input

    $shippingName               = req('shippingName');
    $shippingDescription        = req('shippingDescription');
    $shippingCost               = req('shippingCost');
    $estimatedDeliveryDay       = req('estimatedDeliveryDay');

    // Validate name
    if ($shippingName == '') {
        $_err['shippingName'] = 'Required';
    }
    elseif (strlen($shippingName) > 100) {
        $_err['shippingName'] = 'Must not exceed 100 characters';
    }else {
        // Check if the product name already exists in the database, excluding the current product ID
        $checkStmt = $_db->prepare('SELECT COUNT(*) FROM shippingmethod WHERE shippingName = ?');
        $checkStmt->execute([$shippingName]);
        
        if ($checkStmt->fetchColumn() > 0) {  // If a product with the same name exists
            $_err['shippingName'] = 'This method already exists';
    }
}

    // Validate desc not more than 100
    if ($shippingDescription == '') {
        $_err['shippingDescription'] = 'Required';
    }
    elseif (strlen($shippingDescription) > 200) {
        $_err['shippingDescription'] = 'Must not exceed 300 characters';
    }

     //check the cost
     if($shippingCost == ''){
        $_err['shippingCost'] = 'Required';
    }
   

    //check the day
    if($estimatedDeliveryDay == ''){
        $_err['estimatedDeliveryDay'] = 'Required';
    }
    elseif (!is_numeric($estimatedDeliveryDay) || $estimatedDeliveryDay < 0) {
        $_err['estimatedDeliveryDay'] = 'Must be a non-negative number';
    }


    if(!$_err){
        $stm = $_db->prepare('INSERT INTO shippingmethod(shippingName,shippingDescription, shippingCost, estimatedDeliveryDay)
                              VALUES(?, ?, ?, ?)');
                        
    if ($stm->execute([$shippingName, $shippingDescription, $shippingCost, $estimatedDeliveryDay])) {
        temp('info', 'New Method added successfully');
        redirect('shipping.php');
    } else {
            temp('info', 'Fail to add new shipping, Try later.');
            redirect('shipping.php');
            }
        }
} 
?>

<head>
    <link rel="stylesheet" href="AdminCss/addShipping.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Add New Shipping Method</h1>      
<div id="info"><?= temp('info') ?></div>
<form method="post" class="shippingForm">

    <label for="shippingName">Method Name : </label>
    <?= html_text('shippingName', 'maxlength="100"') ?>
    <?= err('shippingName') ?>


    <label for="shippingDescription">Description : </label>
    <?= html_textarea('shippingDescription', 'maxlength="200"') ?>
    <?= err('shippingDescription') ?>

    <label for="shippingCost">Cost(RM) : </label>
    <?= html_number('shippingCost', 'min="0" step="0.1"') ?>
    <?= err('shippingCost') ?>

    <label for="estimatedDeliveryDay">Estimate Delivery Day : </label>
    <?= html_number('estimatedDeliveryDay', 'min="0" step="1"') ?>
    <?= err('estimatedDeliveryDay') ?>
    

    <section>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
        <button type="button" onclick="window.location.href='shipping.php'">Back To Category List</button>  <!-- Redirect with JavaScript -->
    </section>
</form>

</body>
</html>
