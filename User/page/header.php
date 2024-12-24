<!DOCTYPE html>
<html lang="en">
<?php @include "../../base.php" ?>
<?php @include "base.php" ?>

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
            <h1><a href="/">Unpopular </a></h1>
        </div>

        <ul class="nav-links">
            <li><a href="#">Products</a></li>
            <li><a href="#">Non-fiction</a></li>
            <li><a href="#">Kids's book</a></li>
            <li><a href="#">放爽</a></li>
        </ul>
        <div id="header-utility">
            <a href="#login" class="nav-item">
                <i class="fas fa-user"></i> Login / Register
            </a>

            <!-- Shopping Cart UI -->
            <div id="cart" class="cart-ui hidden">
                <h2 style="color: black;"><u>Your Cart</u></h2>
                <br>
                <div class="cart-items">
                    <!-- Example Item -->
                    <div class="cart-item">
                        <input type="checkbox" class="select-item" />
                        <img src="/image/img1.jpg" alt="Product Image" />
                        <div class="item-details">
                            <p class="item-name">Sample Product</p>
                            <div class="item-quantity">
                                <button class="qty-btn" onclick="decreaseQty(this)">-</button>
                                <input type="number" value="1" min="1" readonly />
                                <button class="qty-btn" onclick="increaseQty(this)">+</button>
                            </div>
                            <p class="item-price">RM 100</p>
                        </div>
                    </div>
                    <div class="cart-item">
                        <input type="checkbox" class="select-item" />
                        <img src="/image/img1.jpg" alt="Product Image" />
                        <div class="item-details">
                            <p class="item-name" style="color:black;">Sample Product 1</p>
                            <div class="item-quantity">
                                <button class="qty-btn" onclick="decreaseQty(this)">-</button>
                                <input type="number" value="1" min="1" readonly />
                                <button class="qty-btn" onclick="increaseQty(this)">+</button>
                            </div>
                            <p class="item-price">RM 100</p>
                        </div>
                    </div>
                    <div class="cart-item">
                        <input type="checkbox" class="select-item" />
                        <img src="/image/img1.jpg" alt="Product Image" />
                        <div class="item-details">
                            <p class="item-name" style="color:black;">Sample Product 1</p>
                            <div class="item-quantity">
                                <button class="qty-btn" onclick="decreaseQty(this)">-</button>
                                <input type="number" value="1" min="1" readonly />
                                <button class="qty-btn" onclick="increaseQty(this)">+</button>
                            </div>
                            <p class="item-price">RM 100</p>
                        </div>
                    </div>
                </div>
                <div class="cart-summary">
                    <p>Total: <span id="total-price">$100</span></p>
                    <button class="checkout-btn">Proceed to Checkout</button>
                    <button class="close-cart" onclick="toggleCart()">Close</button>
                </div>
            </div>

            <!-- Shopping Bag Icon -->
            <a href="javascript:void(0)" class="nav-item" onclick="toggleCart()">
                <i class="fas fa-shopping-bag"></i>
            </a>
        </div>
    </header>

    </nav>