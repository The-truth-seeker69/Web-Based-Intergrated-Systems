<?php
require __DIR__ . '/../_base.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="stylesheet" href="/Style/admin/header.css">
    <link rel="stylesheet" href="/Style/admin/home.css">
    <link rel="shortcut icon" href="/image/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script/app.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div id="header-brand">
            <h1><a href="/Admin/home.php">INFINITE</a></h1>
        </div>

        <?php if ($_user && $_user->adminRole == 'Manager'): ?>
            <ul class="nav-links">
                <li><a href="/Admin/home.php">Home</a></li>
                <li><a href="/Admin/pages/memberView.php">Member</a></li>
                <li><a href="/Admin/pages/adminView.php">Admin</a></li>
                <li><a href="/Admin/product.php">Product</a></li>
                <li><a href="/Admin/order.php">Order</a></li>
            </ul>
        <?php elseif ($_user && $_user->adminRole == 'Admin'): ?>
            <ul class="nav-links">
                <li><a href="/Admin/home.php">Home</a></li>
                <li><a href="/Admin/pages/memberView.php">Member</a></li>
                <li><a href="/Admin/product.php">Product</a></li>
                <li><a href="/Admin/order.php">Order</a></li>
            </ul>
        <?php endif ?>


        <?php if (!$_user): ?>
            <div id="profile-pic">
                <img src="/image/admin/uploads/ppplaceholder.png" alt="Profile Picture">
                <ul class="dropdown-menu">
                    <li><a href="/Admin/pages/adminLogin.php">Login</a></li>

                </ul>
            </div>
        <?php else: ?>
            <div id="profile-pic">
                <img src="/image/admin/uploads/<?= $_user->adminPic ?>" alt=" Profile Picture">
                <ul class="dropdown-menu">
                    <li><a href="/Admin/pages/viewAdminProfile.php">View Profile</a></li>
                    <li><a href="/Admin/pages/adminLogout.php">Logout</a></li>
                </ul>

            </div>
        <?php endif ?>

    </header>