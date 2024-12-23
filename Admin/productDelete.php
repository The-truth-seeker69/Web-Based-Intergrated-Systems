<?php
$_title = 'Product';  // Set page title
require '../_base.php';
include '../head.php';

$prodID = req('prodID');

// Step 1: Get the current product status
$stm = $_db->prepare('SELECT prodStatus FROM product WHERE prodID = ?');
$stm->execute([$prodID]);
$product = $stm->fetch();

// Step 2: Check if product exists and retrieve the current status
if ($product) {
    $currentStatus = $product->prodStatus;

    //if available make it Unavailable else current status is Unavailable make it available 
    $newStatus = ($currentStatus == 'Available') ? 'Unavailable' : 'Available';

    if ($currentStatus == 'OutOfStock') {
        redirect('viewStock.php');
    }

    $stmUpdate = $_db->prepare('UPDATE product SET prodStatus = ? WHERE prodID = ?');
    $stmUpdate->execute([$newStatus, $prodID]);

    // Step 5: Check if the status was 'OutOfStock' before the update and redirect
    

    // Step 6: Redirect to the product list after updating the status
    redirect('product.php');
} else {
    echo "Product not found.";
}
?>
