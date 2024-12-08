<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/image/img1.jpg">
    <link rel="stylesheet" href="/Admin/AdminCss/adminBar.css">
  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script/app.js"></script>
</head>

<body>
      <!-- Flash message -->
      <div id="info"><?= temp('info') ?></div>
    <header>
        <div class="container">
            <div id="logo">
                <img src="../image/img1.jpg" alt="Logo Image">
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="../Admin/product.php">Product</a></li>
                    <li><a href="../Admin/order.php">Order</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
<main>
