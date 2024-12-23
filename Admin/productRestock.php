<?php
$_title = 'Product';  // Set page title
require '../_base.php';
include '../head.php';

$prodID = req('prodID');
$quantity = $_POST['quantity'] ?? 0;

if ($prodID && $quantity > 0) {
    $stmt = $_db->prepare('UPDATE product SET prodStock = prodStock + ?, prodStatus = "Available" WHERE prodID = ?');

  $stmt->execute([$quantity, $prodID]);

  if ($stmt) {
    temp('info', 'Stock added successfully.');
    redirect('viewStock.php');
  } else {
    temp('info', 'Something went wrong.');
    redirect('viewStock.php');
  }
} 
?>
