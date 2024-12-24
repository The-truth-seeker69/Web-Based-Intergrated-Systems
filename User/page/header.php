<!DOCTYPE html>
<html lang="en">
<?php @include "../../_base.php" ?>
<?php @include "_base.php" ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page </title>
    <link rel="stylesheet" href="/Style/user/home.css">
    <link rel="stylesheet" href="/Style/user/header.css">
    <link rel="stylesheet" href="/Style/user/footer.css">
    <script src="/script/cart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>

<body>
    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>
    <header>


        <div id="header-brand">
            <h1><a href="../../index.php">Unpopular </a></h1>
        </div>

        <ul class="nav-links">
            <li><a href="../../index.php">Home</a></li>
            <li><a href="../../User/page/products.php">Products</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
        <div id="header-utility">
            <?php if (isset($_SESSION['userId'])): ?>
            <div id="profile-pic">
                <?php
                    
                ?>
                <img src="../../image/user/uploads/ppplaceholder.png" alt=" Profile Picture">
                <ul class="dropdown-menu">
                    <li><a href="#">View Profile</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </div>

            <?php else: ?>
            <a href="#login" class="nav-item">
                <i class="fas fa-user"></i> Login / Register
            </a>

            <?php endif ?>

            <!-- Shopping Cart UI -->
            <form method="post" action="../../User/page/checkout.php">
                <div id="cart" class="cart-ui hidden">
                    <h2 style="color: black;"><u>Your Cart</u></h2>
                    <br>
                    <div class="cart-items">
                        <?php
                        if(isset($_SESSION['userId'])){
                            $userID = $_SESSION['userId'];

                            $stm = $_db->prepare('SELECT * FROM cart WHERE userID = ?');
                            $stm->execute([$userID]);
                            $cart = $stm->fetch();
                            
                            if($cart){
                                $stm2 = $_db->prepare('SELECT * FROM cart_item WHERE cartID = ?');
                                $stm2->execute([$cart->cartID]);
                                $cart_items = $stm2->fetchAll();

                                if($cart_items){
                                    foreach($cart_items as $items){
                                        $stm3 = $_db->prepare('SELECT * FROM product WHERE prodID = ?');
                                        $stm3->execute([$items->prodID]);
                                        $product = $stm3->fetch();

                                        $stm4 = $_db->prepare('SELECT * FROM productimage WHERE prodID = ?  LIMIT 1');
                                        $stm4->execute([$product->prodID]);
                                        $productImage = $stm4->fetch();
                    ?>

                        <div class="cart-item">
                            <input type="hidden" name="cartID" value="<?= $cart->cartID ?>" />
                            <input type="checkbox" class="select-item" name="selectedItems[]"
                                value="<?=$product->prodID?>" />
                            <img src="../../image/user/uploads/<?= $productImage->imageURL ?>" alt="Product Image" />
                            <div class="item-details">
                                <p class="item-name"><?= $product->prodName ?></p>
                                <div class="item-quantity">
                                    <button class="qty-btn" type='button'
                                        onclick="updateCartQty(this, 'decrease', <?= $items->prodID ?>)">-</button>
                                    <input type="number" value="<?= $items->cartItemsQty ?>" min="1" readonly
                                        data-prod-id="<?= $items->prodID ?>" />
                                    <button class="qty-btn" type='button'
                                        onclick="updateCartQty(this, 'increase', <?= $items->prodID ?>)">+</button>
                                </div>
                                <p class="item-price"> RM <?= $product->prodPrice ?></p>
                            </div>
                        </div>



                        <?php
                                    }
                    ?>
                        <div class="cart-summary">
                            <!-- <p class="cart-details">Total: <span id="total-price">$100</span></p> -->
                            <button class="checkout-btn">Proceed to Checkout</button>
                            <button type='button' class="close-cart" onclick="toggleCart()">Close</button>
                        </div>

                        <?php
                                }else {
                                    echo "<h2 style='text-align:center; color:red;'>No Items In Your Cart.</h2>";
                                    echo '<div class="cart-summary">
                                            <button class="close-cart" onclick="toggleCart()">Close</button>
                                        </div>';
                                }
                            }
                        }
                    ?>
                    </div>
                </div>

                <!-- Shopping Bag Icon -->
                <a href="javascript:void(0)" class="nav-item">
                    <i class="fas fa-shopping-bag" id="cart-icon"></i>
                </a>
        </div>
        </form>
    </header>

    </nav>
    <script>
    $("#cart-icon").click(function() {
        $.ajax({
            url: "check_session.php",
            method: "POST",
            success: function(response) {
                if (response === "not_logged_in") {
                    window.location.href = "../../User/page/userLogin.php";
                } else {
                    const cart = document.getElementById("cart");
                    cart.classList.toggle("hidden");
                }
            }
        });
    });
    </script>