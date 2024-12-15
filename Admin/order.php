<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item'); // Get the search input from the user

// Prepare the query to search only by orderID
$stm = $_db->prepare(
    'SELECT * FROM `order` WHERE orderID LIKE ?'
);

// Execute the query with wildcards for partial matching
$stm->execute(["%$item%"]);

// Fetch all matching rows
$arr = $stm->fetchAll();

if (is_post()) {
    $orderStatus    = req('orderStatus');
    $orderID        = req('orderID');

    if ($orderStatus && $orderID) {
        try {
            $stm = $_db->prepare("UPDATE `order` SET `orderStatus` = ? WHERE `orderID` = ?");
            $stm->execute([$orderStatus, $orderID]);

            temp('info', 'Order Status Change Successfully');
            redirect('order.php');
        } catch (PDOException $e) {
            echo "<p>Error updating status: " . $e->getMessage() . "</p>";
        }
    }
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
.cancelled {
    color: red; /* Change text color to red */
    font-weight: bold;
}

    </style>

</head>

<main>
        <h1 style="text-align:center;">Order List</h1>
<form>
    <!-- for searching -->
    <?= html_search('item', 'placeholder="Enter ID"') ?>
    <button>Search</button>  
</form>

<p><?= count($arr) ?> record(s)</p>
<div id="info"><?= temp('info') ?></div>
 <!-- Here is the button add category, add product-->
    
 <!-- Here is the table -->
    <table class="table">
    <tr>
        <th>Order Id</th>
        <th>Total</th>
        <th>Date</th>
        <th>Current Status</th>
        <th>Modify Status</th>
        <th>Action</th>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><?= $s->orderID ?></td>
        <td><?= $s->grandTotal ?></td>
        <td><?= $s->orderDate ?></td>
        <td class="<?= $s->orderStatus == 'Cancelled' ? 'cancelled' : '' ?>">
        <?= $s->orderStatus ?>
        <td>
        <form method="post" action="order.php">
                        <?= html_select('orderStatus', $_orderStatus, $s->orderStatus) ?>
                        <input type="hidden" name="orderID" id="orderID" value="<?= $s->orderID ?>">
                        <button type="submit">Submit</button>
        </form>
        </td>
         <!-- Form with dropdown for status change -->
        
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
