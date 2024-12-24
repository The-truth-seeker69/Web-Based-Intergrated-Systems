<?php
require 'header.php';
$_title = 'Order | Details';
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

$orderID = req('id');
$orderID = (int) $orderID;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/orderDetails.css">
    <script src="../../script/app.js"></script>
    <script src="../JS/userProfile.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <div class='order-div'>
        <h1>Order Details</h1>
        <h2>Shipping Information</h2>
        <?php 
        // Get shipping method
        $stm = $_db->prepare('SELECT * FROM `shippingmethod` WHERE shippingMethodID = (SELECT CAST(shippingMethodID AS UNSIGNED) FROM `order` WHERE orderID = ?)');
        $stm->execute([$orderID]);
        $shippingMethod = $stm->fetch();

        $stm2 = $_db->prepare('SELECT orderStatus FROM `order` WHERE orderID = ?');
        $stm2->execute([$orderID]);
        $status = $stm2->fetch();


        if ($shippingMethod) {
            if($shippingMethod->shippingMethodID == 1){
        ?>

        <table>
            <tr>
                <td>Shipping Method: </td>
                <td><?=$shippingMethod->shippingName?></td>
            </tr>
            <tr>
                <td>Status: </td>
                <td><?=$status->orderStatus?></td>
            </tr>
        </table>
        <?php   
            }else{
        ?>
        <table>
            <tr>
                <td>Shipping Method: </td>
                <td><?=$shippingMethod->shippingName?> <i class="fa-solid fa-plane"></i></td>
            </tr>
            <tr>
                <td>Status: </td>
                <td><?=$status->orderStatus?></td>
            </tr>
        </table>
        <?php 
           }
            
        }  
        
        
    ?>

        <h2>Delivery Information</h2>
        <?php 
        $stm3 = $_db->prepare('SELECT * FROM `user` WHERE userID = (SELECT userID FROM `order` WHERE orderID = ?)');
        $stm3->execute([$orderID]);
        $user = $stm3->fetch();

        $stm4 = $_db->prepare('SELECT shippingAddress FROM `order` WHERE orderID = ?');
        $stm4->execute([$orderID]);
        $address = $stm4->fetch(); 


        if ($user) {
            ?>
        <table>
            <tr>
                <td>Recipient: </td>
                <td><?=$user->userName?> <i class="fa-solid fa-plane"></i></td>
            </tr>
            <tr>
                <td> <i class="fa-solid fa-phone"></i></td>
                <td><?=$user->userPhoneNo?></td>
            </tr>
            <tr>
                <td><i class="fa-solid fa-house"></i></td>
                <td><?=$address->shippingAddress?></td>
            </tr>
        </table>
        <?php   
            }  
        ?>

        <h2>Order Information</h2>

        <?php 
        foreach ($arr as $o) {
            // Get order items
            $stm2 = $_db->prepare('SELECT * FROM `order_item` WHERE orderID = ?');
            $stm2->execute([$o->orderID]);
            $arr_items = $stm2->fetchAll();

            // Get shipping method
            $stm4 = $_db->prepare('SELECT * FROM `shippingmethod` WHERE shippingMethodID = ?');
            $stm4->execute([$o->shippingMethodID]);
            $shippingMethod = $stm4->fetch();
    ?>

        <div class="order-container">
            <div class="order-header">
                <table>
                    <tr>
                        <td>Order ID: </td>
                        <td><?=$o->orderID?></td>
                    </tr>
                    <tr>
                        <td>Date-Time: </td>
                        <td><?=$o->orderDate?></td>
                    </tr>
                </table>
            </div>


            <div class="order-items">
                <table class='order-items-table'>

                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price (RM)</th>
                        <th>Total Price (RM)</th>
                    </tr>

                    <?php
                foreach ($arr_items as $items) {
                    $stm3 = $_db->prepare('SELECT * FROM `product` WHERE prodID = ?');
                    $stm3->execute([$items->prodID]);
                    $product = $stm3->fetch();
                    echo "<tr><td>" . $product->prodName . "</td>" . 
                    "<td>" . $items->orderItemsQty . "</td>" . 
                    "<td>" . $product->prodPrice . "</td>" . 
                    "<td>" . $product->prodPrice * $items->orderItemsQty . "</td></tr>";
                }
            ?>

                </table>

            </div>
        </div>
        <?php   
        }
    ?>

        <h2>Summary</h2>
        <?php
            $stm3 = $_db->prepare('SELECT * FROM `order` WHERE orderID = ?');
            $stm3->execute([$orderID]);
            $order = $stm3->fetch();
        ?>
        <table class='summary'>
            <tr>
                <td>Grand Total (RM):</td>
                <td><?=$order->grandTotal?></td>
            </tr>
            <tr>
                <td>Discounted Total (RM): </td>
                <td><?=$order->discountTotal?></td>
            </tr>
            <tr>
                <td>Shipping Cost (RM): </td>
                <td><?=$shippingMethod->shippingCost?></td>
            </tr>
            <tr>
                <td>Subtotal (RM): </td>
                <td><?=$order->grandTotal - $order->discountTotal + $shippingMethod->shippingCost?></td>
            </tr>
        </table>
    </div>
    <?php
include 'footer.php';