<?php
require 'header.php';
$_title = 'Order | History';
// ----------------------------------------------------------------------------


//remove this
$_SESSION['userId'] = 1;
$userId = $_SESSION['userId'];

$stm = $_db->prepare('
    SELECT * FROM `order`
    WHERE userID = ?
');
$stm->execute([$userId]);
$arr = $stm->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../css/orderHistory.css">
    <script src="../../script/app.js"></script>
    <script src="../JS/userProfile.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<p><?= count($arr) ?> record(s)</p>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Datetime</th>
            <th>Grand Total (RM)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($arr as $o) {
            // Fetch order items
            // $stm2 = $_db->prepare('SELECT * FROM `order_item` WHERE orderID = ?');
            // $stm2->execute([$o->orderID]);
            // $arr_items = $stm2->fetchAll();

            // Fetch product details for each item
            // $items_html = '';
            // foreach ($arr_items as $items) {
            //     $stm3 = $_db->prepare('SELECT * FROM `product` WHERE prodID = ?');
            //     $stm3->execute([$items->prodID]);
            //     $product = $stm3->fetch();

            //     $items_html .= "<div class='item'>
            //                         <div class='item-name'>{$product->prodName}</div>
            //                         <div class='item-qty'>{$items->orderItemsQty} pcs</div>
            //                     </div>";
            // }
        ?>
            <tr>
                <td><?= $o->orderID ?></td>
                <td><?= $o->orderDate ?></td>
                <td class="grand-total"><?= $o->grandTotal ?></td>
                <!-- <td>
                <div class="order-details">
                    <?= $items_html ?>
                </div>
            </td> -->
                <td class="action-btn">

                    <button onclick="window.location.href='./orderDetails.php?id=<?= $o->orderID ?>'">Detail</button>
                </td>
            </tr>
            <!-- <?php } ?> -->
    </tbody>
</table>

<?php
include 'footer.php';
