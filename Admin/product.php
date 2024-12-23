<?php
$_title = 'Product';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item'); // req for item id
$viewAll = req('view_all') === 'true'; // check if view_all is set and compare

// Default behavior, show only 'Available' products initially
$item = req('item'); // Item ID or search keyword
$viewAll = req('view_all') === 'true'; // Check if view_all is set and compare

$searchQuery = "%$item%"; // Search keyword

// Adjust query based on view_all status
if ($viewAll) {
    // Display all products regardless of status
    $stm = $_db->prepare('SELECT * FROM product WHERE prodID LIKE ? OR prodAuthor LIKE ? OR prodName LIKE ?');
} else {
    // Only display 'Available' products
    $stm = $_db->prepare('SELECT * FROM product WHERE prodStatus = "Available" AND (prodID LIKE ? OR prodAuthor LIKE ? OR prodName LIKE ?)');
}
$stm->execute([$searchQuery, $searchQuery, $searchQuery]);

$arr = $stm->fetchAll();


// Check for out-of-stock products
$stmt2 = $_db->prepare('SELECT * FROM product WHERE prodStock = 0');
$stmt2->execute();
$productsOutOfStock = $stmt2->fetchAll();

// Display alert if out-of-stock products exist
if ($productsOutOfStock) {
    temp('info', 'Some items are out of stock.');
}
?>

<head>
    <link rel="stylesheet" href="AdminCss/adminTable.css">
    <link rel="stylesheet" href="AdminCss/product.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<main>
    <h1 style="text-align:center;">Book List</h1>
    <form action="product.php" method="GET">
        <?= html_search('item', 'placeholder="Enter ID OR TITLE OR AUTHOR"') ?>
        <input type="hidden" name="view_all" value="<?= $viewAll ? 'true' : 'false' ?>">
        <!-- Hidden input to maintain view state -->
        <button>Search</button>
    </form>
    </a>


    <p><?= count($arr) ?> record(s)</p>
    <div id="info"><?= temp('info') ?></div>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Left-aligned buttons -->
        <div style="display: flex; gap: 10px;">
            <a href="addProduct.php" id="addNewProduct" class="button">Add New Product</a>
            <a href="categoryDetail.php" id="categoryDetail" class="button">View Category</a>
            <a href="viewStock.php" id="viewStock" class="button">Out Of Stock</a>
        </div>
        <a href="?view_all=<?= $viewAll ? 'false' : 'true' ?>" class="button">
            <?= $viewAll ? 'View Available' : 'View All' ?>
        </a>
    </div>

    <!-- Here is the table -->
    <table class="table">
        <tr>
            <th>Book Id</th>
            <th>Book Title</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($arr as $s): ?>
            <tr>
                <td><?= $s->prodID ?></td>
                <td><?= $s->prodName ?></td>
                <td><?= $s->prodPrice ?></td>
                <td><?= $s->prodStock ?></td>
                <!-- check whether is out-of-stock || or Unavailable -->
                <td
                    class="<?= $s->prodStatus === 'OutOfStock' ? 'out-of-stock' : ($s->prodStatus === 'Unavailable' ? 'unavailable' : '') ?>">
                    <?= $s->prodStatus ?>
                </td>
                <td class="buttonDesign">
    <button data-get="productDetail.php?prodID=<?= $s->prodID ?>">Detail</button>
    <button data-get="productUpdate.php?prodID=<?= $s->prodID ?>">Update</button>
    <!-- Delete Button -->
    <button class="deleteButton <?= ($s->prodStatus === 'Unavailable') ? 'active' : '' ?>"
        data-post="productDelete.php?prodID=<?= $s->prodID ?>"
        data-confirm="Are you sure you want to delete?">
        <?= ($s->prodStatus === 'OutOfStock') ? 'Restore' : '' ?>
        <?= ($s->prodStatus === 'Available') ? 'Delete' : '' ?>
        <?= ($s->prodStatus === 'Unavailable') ? 'Active' : '' ?>
    </button>
</td>


                </td>
            </tr>
        <?php endforeach ?>
    </table>
</main>
</body>

</html>