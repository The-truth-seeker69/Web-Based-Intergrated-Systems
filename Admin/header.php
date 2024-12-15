<?php

require __DIR__ . '/../base.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page </title>
    <link rel="stylesheet" href="/Style/admin/header.css">
    <link rel="stylesheet" href="/Style/admin/home.css">
    <link rel="stylesheet" href="/Style/general/genaral.css">
    <link rel="stylesheet" href="/Style/admin/memberView.css">
    <link rel="stylesheet" href="/Style/admin/viewAdminProfile.css">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/script/app.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>

<body>

    <header>

        <div id="header-brand">
            <h1><a href="/Admin/home.php">Unpopular </a></h1>
        </div>

        <ul class="nav-links">
            <li><a href="/Admin/home.php">Home</a></li>
            <li><a href="/Admin/pages/memberView.php">Member</a></li>
            <li><a href="/Admin/pages/adminView.php">Admin</a></li>

            <li><a href="">Product</a></li>
            <li><a href="#">Order</a></li>


        </ul>
        <?php if ($_user): ?>
            <div id="profile-pic">
                <img src="/image/admin/uploads/<?= $_user->adminPic ?>" alt=" Profile Picture">
                <ul class="dropdown-menu">
                    <li><a href="/Admin/pages/viewAdminProfile.php">View Profile</a></li>
                    <li><a href="/Admin/pages/editAdminProfile.php">Edit Profile</a></li>
                    <li><a href="/Admin/pages/adminLogout.php">Logout</a></li>
                </ul>
            </div>
        <?php endif ?>

    </header>