<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

$prodID = req('prodID');

//if want active need to make sure the quantity is more than 0
$checkStockStmt = $_db->prepare('SELECT prodStock FROM product WHERE prodID = ?');
$checkStockStmt->execute([$prodID]);
$stock = $checkStockStmt->fetchColumn(); // Get the stock value

if ($stock > 0) {
    $stm = $_db->prepare('UPDATE product SET prodStatus = "Available" WHERE prodID = ?');
    $stm->execute([$prodID]);

   redirect('a_home.php');
} else {
    // Handle the case where stock is 0
    temp('info', 'Cannot set product status to Available because stock is 0');
    redirect('viewStock.php');
}
?>
<div id="info"><?= temp('info') ?></div>
