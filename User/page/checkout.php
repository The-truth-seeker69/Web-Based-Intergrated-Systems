<?php @include "../../_base.php" ?>
<?php @include "_base.php" ?>

<?php

$selectedItems = $_POST['selectedItems'];

if ($selectedItems) {
    //remove this
    $_SESSION['userId'] = 1;
    $userID = $_SESSION['userId'];
    $cartID = req('cartID');

    $placeholders = implode(',', $selectedItems);

    $query = 'SELECT * FROM cart_item WHERE cartID = ? AND prodID IN (' . $placeholders . ')';
    $stm = $_db->prepare($query);
    $stm->execute([$cartID]);

    $cart_items = $stm->fetchAll();
} else {
    echo "
    <script>
        alert('You cannot check out with nothing selected');
        window.location.href='../../User/page/products.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="../css/checkout.css">
</head>

<body>
    <div class="checkout-container">
        <h1>Checkout</h1><br>

        <!-- Product List -->
        <div class="product-list">
            <?php
            foreach ($cart_items as $items) {
                $stm2 = $_db->prepare('SELECT * FROM product WHERE prodID = ?');
                $stm2->execute([$items->prodID]);
                $product = $stm2->fetch();

                $stm3 = $_db->prepare('SELECT * FROM productimage WHERE prodID = ?  LIMIT 1');
                $stm3->execute([$product->prodID]);
                $productImage = $stm3->fetch();
            ?>

                <div class="product-item">
                    <img src="../../image/user/uploads/<?= $productImage->imageURL ?>" alt="Product Image"
                        class="product-image">
                    <div class="product-details">
                        <p class="product-name"><?= $product->prodName ?></p>
                        <p class="product-price">Price: RM <?= $product->prodPrice ?></p>
                    </div>
                    <div class="product-quantity">
                        <label for="quantity1">Qty:</label>
                        <input class="qty" type="number" value="<?= $items->cartItemsQty ?>" min="1" disabled>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>

        <!-- Shipping Method -->
        <div class="dropdown">
            <label for="shipping-method">Shipping Method:</label>
            <select name="shipping-method" id="shipping-method">
                <option value="0" selected='selected'>
                    -- Select One --
                </option>
                <?php
                $stm4 = $_db->prepare('SELECT * FROM shippingmethod');
                $stm4->execute();

                $shippingMethods = $stm4->fetchAll();

                foreach ($shippingMethods as $shippingMethod) {
                ?>
                    <option value="<?= htmlspecialchars($shippingMethod->shippingCost) ?>">
                        <?= htmlspecialchars($shippingMethod->shippingName) ?> - RM
                        <?= htmlspecialchars($shippingMethod->shippingCost) ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </div>

        <!-- Shipping Address -->
        <div class="dropdown">
            <label for="shipping-address">Shipping Address:</label>
            <select id="shipping-address" name="shipping-address">
                <option value="0" selected='selected'>
                    -- Select One --
                </option>
                <?php
                $stm5 = $_db->prepare("SELECT * FROM address WHERE userID = ?");
                $stm5->execute([(int) $userID]);

                $addresses = $stm5->fetchAll();

                foreach ($addresses as $address) {
                ?>
                    <option value="<?= $address->addressID ?>">
                        <?= htmlspecialchars($address->addressLine) ?>,
                        <?= htmlspecialchars($address->state) ?>,
                        <?= htmlspecialchars($address->postalCode) ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </div>

        <!-- Voucher Code -->
        <div class="voucher">
            <label for="voucher-code">Voucher Code:</label>
            <input type="text" id="voucher-code" placeholder="Enter code">
        </div>

        <!-- Price Summary -->
        <div class="price-summary">
            <p>Grand Total: <span id="grand-total">RM 300</span></p>
            <p>Discounted Total: <span id="discounted-total">RM 270</span></p>
            <p>Shipping Total: <span id="shipping-total">RM 10</span></p>
            <p>Total Payment: <span id="total-payment">RM 280</span></p>
        </div>
    </div>
</body>

</html>