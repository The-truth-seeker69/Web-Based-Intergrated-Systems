<?php
$_title = 'Shipping';  // Set page title
require '../_base.php';
include '../head.php';

$shippingMethodID = req('shippingMethodID');
// Fetch product data
$stm = $_db->prepare('SELECT * FROM shippingmethod WHERE shippingMethodID = ?');
$stm->execute([$shippingMethodID]);
$s = $stm->fetch();

$shippingName = $s->shippingName;
$shippingDescription = $s->shippingDescription;
$shippingCost = $s->shippingCost;
$estimatedDeliveryDay =  $s->estimatedDeliveryDay;

if(is_post()){
    $shippingName = req('shippingName');
    $shippingDescription = req('shippingDescription');
    $shippingCost = req('shippingCost');
    $estimatedDeliveryDay = req('estimatedDeliveryDay');

    //checking
    // Validate method
    if ($shippingName == '') {
        $_err['shippingName'] = 'Required';
    }
    elseif (strlen($shippingName) > 100) {
        $_err['shippingName'] = 'Must not exceed 100 characters';
    }
    else {
        // Check if the product name already exists in the database, excluding the current product ID
        $checkStmt = $_db->prepare('SELECT COUNT(*) FROM shippingmethod WHERE shippingName = ? AND shippingMethodID != ?');
        $checkStmt->execute([$shippingName, $shippingMethodID]);
        
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

    if (!$_err) {
        $insertStmt = $_db->prepare('UPDATE shippingmethod SET shippingName = ?, shippingDescription = ?, shippingCost = ?, estimatedDeliveryDay = ? WHERE shippingMethodID = ?');
        $insertStmt->execute([$shippingName, $shippingDescription, $shippingCost, $estimatedDeliveryDay, $shippingMethodID]);
    
        // Check if the update affected any rows
        if ($insertStmt->rowCount() > 0) {
            echo "<script>
                    alert('Shipping Method updated successfully!');
                    window.location.href = 'shippingUpdate.php?shippingMethodID=" . $shippingMethodID . "';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('There is something wrong!!');
                  </script>";
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
    <link rel="stylesheet" href="AdminCss/updateShipping.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<form method="post" class="shippingForm">

<label for="id" style="color: blue;">Shipping Method ID: <?= $shippingMethodID ?></label>

<label for="shippingName">Shipping Metohd : </label>
    <?= html_text('shippingName', 'maxlength="100"') ?>
    <?= err('shippingName') ?>

    <label for="shippingDescription">Shipping Description : </label>
    <?= html_text('shippingDescription', 'maxlength="100"') ?>
    <?= err('shippingDescription') ?>

    <label for="shippingCost">Cost(RM) : </label>
    <?= html_number('shippingCost', 'min="0" step="0.1"') ?>
    <?= err('shippingCost') ?>

    <label for="estimatedDeliveryDay">Estimate Delivery Day : </label>
    <?= html_number('estimatedDeliveryDay', 'min="0" step="1"') ?>
    <?= err('estimatedDeliveryDay') ?>
    

<section style="display: flex; justify-content: center; align-items: center; gap: 10px;">
    <button type="submit">Submit</button>
    <button type="reset">Reset</button>
    <button type="button" onclick="window.location.href='shipping.php'">Back To Shipping List</button>
</section>

</form>
</body>
</html>
