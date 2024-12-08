<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item');

// TODO: Modify the query to search by name, author, or prodID
$stm = $_db->prepare(
    'SELECT * FROM product WHERE prodID LIKE ? OR prodAuthor LIKE ? OR prodName LIKE ?'
);

// Use the input for all columns with wildcards for partial matching
$stm->execute(["%$item%", "%$item%", "%$item%"]);

// Fetch results
$arr = $stm->fetchAll();


$stmt2 = $_db->prepare('SELECT * FROM product WHERE prodStock = 0');
$stmt2->execute();
$productsOutOfStock = $stmt2->fetchAll();

if ($productsOutOfStock) {
    // Set a temporary flash message to inform user
    temp('info', 'Some items are out of stock.');
}
?>

<head>
    <link rel="stylesheet" href="AdminCss/adminTable.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>

form {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 20px auto;
  padding: 10px;
  max-width: 500px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #ffffff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Input field styling */
form input[type="search"] {
    width: 80%;
    height: 80%;
}

/* Search button styling */
form button {
  padding: 8px 15px;
  border: none;
  background-color: #2b587a;
  color: white;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
}

/* Hover effect for search button */
form button:hover {
  background-color: #7bb6e3;
}

/* Red out-of-stock status styling */
.out-of-stock {
    color: red; /* Change text color to red */
    font-weight: bold;
}

    </style>

</head>

<main>
        <h1 style="text-align:center;">Book List</h1>
<form>
    <!-- for searching -->
    <?= html_search('item', 'placeholder="Enter ID OR TITLE OR AUTHOR"') ?>
    <button>Search</button>  
</form>

<p><?= count($arr) ?> record(s)</p>
<div id="info"><?= temp('info') ?></div>
 <!-- Here is the button add category, add product-->
    <div class="topButton">
    <a href="addProduct.php" id="addNewProduct" class="button">Add New Product</a>
    <a href="categoryDetail.php" id="categoryDetail" class="button">View Category</a>
    <a href="viewStock.php" id="viewStock" class="button">Out Of Stock</a>
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
        <td class="<?= $s->prodStatus === 'OutOfStock' ? 'out-of-stock' : '' ?>">
        <?= $s->prodStatus ?></td>
        <td class="buttonDesign">
            <!-- Here is the button for view, update, and also the delete -->
            <button data-get="productDetail.php?prodID=<?= $s->prodID ?>">Detail</button>
            <button data-get="productUpdate.php?prodID=<?= $s->prodID ?>">Update</button>
           <button data-post="productDelete.php?prodID=<?= $s->prodID ?>" data-confirm>Delete</button>
        </td>
    </tr> 
    <?php endforeach ?>
    </table>
</main>
</body>
</html>
