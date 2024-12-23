<?php
$_title = 'Order';  // Set page title
require '../_base.php';
include '../head.php';

$orderID = req('orderID');


//prepare where there need to slot in
//query select all only 
$stm = $_db->prepare('SELECT * FROM `order` WHERE orderID = ? ');
$stm->execute([$orderID]);
$s = $stm->fetch();
?>


<head>
    <link rel="stylesheet" href="AdminCss/viewOrder.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>


</style>
</head>

<body>
<form method="post" class="orderForm">
    <h1>Order Details</h1>
    <label>Order ID: <span style="color:blue; font-weight: bold;"><?= $s->orderID ?></span></label>
    <label>User ID: <span><?= $s->userID ?></span></label>
    <label>Order Date: <span><?= $s->orderDate ?></span></label>
    <label>Discount Total: <span><?= $s->discountTotal ?></span></label>
    <label>Grand Total: <span><?= $s->grandTotal ?></span></label>
    <label>Order Status: <span><?= $s->orderStatus ?></span></label>
    <label>Payment Method : <span><?= $s->paymentMethod ?></span></label>
    <label>Shipping Address<span><?= $s->shippingAddress ?></span></label>
    <label>Discount Code ID : <span><?= $s->discountCodeID ?></span></label>
    <label>Shipping Method ID : <span><?= $s->shippingMethodID ?></span></label>
    
    </div>
    
    <div class="actionButtons">
        <button type="button" onclick="window.location.href='order.php'">Back to Order List</button>
    </div>
</form>


</body>
</html>