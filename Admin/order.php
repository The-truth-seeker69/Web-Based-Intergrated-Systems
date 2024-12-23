<?php
$_title = 'Order';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item'); // Get the search input from the user
$orderStatus = req('orderStatus');  // Get the order status filter from the user (if set)

// Prepare the base query to search by orderID
$sql = 'SELECT * FROM `order` WHERE orderID LIKE ?';

// If orderStatus is provided, add it to the query
if ($orderStatus) {
    $sql .= ' AND orderStatus = ?';
}

// Prepare the query
$stm = $_db->prepare($sql);

// Execute the query with wildcards for partial matching
if ($orderStatus) {
    $stm->execute(["%$item%", $orderStatus]);
} else {
    $stm->execute(["%$item%"]);
}

// Fetch all matching rows
$arr = $stm->fetchAll();

if (is_post()) {
    $orderStatus = req('orderStatus');
    $orderID = req('orderID');

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
    <link rel="stylesheet" href="AdminCss/order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<main>
    <h1 style="text-align:center;">Order List</h1>

    <form method="post" action="order.php">
    <!-- for searching -->
    <?= html_search('item', 'placeholder="Enter ID"') ?>

    <!-- Dropdown to filter by order status -->
    <select name="orderStatus" id="orderStatus" style="margin-left:10px; margin-right:10px;">
        <option value="">Select Status</option>
        <?php foreach ($_orderStatus as $statusKey => $statusValue): ?>
            <option value="<?= $statusKey ?>" <?= is_post() && req('orderStatus') == $statusKey ? 'selected' : '' ?>>
                <?= $statusValue ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Reset button to clear the form fields -->
    <button type="button" id="resetButton" style="margin-right:10px;">Reset</button>

    <!-- Submit button to apply filters -->
    <button type="submit">Filter</button>
</form>



    <p><?= count($arr) ?> record(s)</p>
    <div id="info"><?= temp('info') ?></div>
    <!-- Here is the button add category, add product-->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Left-aligned buttons -->
        <div style="display: flex; gap: 10px;">
            <a href="discount.php" id="discount" class="button">Discount</a>
        </div>
    </div>

    
    <table class="table">
        <tr>
            <th>Order Id</th>
            <th>Total(RM)</th>
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
                    <button class="btn detail" data-get="orderDetail.php?orderID=<?= $s->orderID ?>">Detail</button>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</main>
</body>
</html>

<script>
    $('#resetButton').on('click', function() {
        window.location.href = 'order.php'; 
    });
</script>