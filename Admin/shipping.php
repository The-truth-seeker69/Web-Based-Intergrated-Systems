<?php
$_title = 'Shippig';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item');
$shippingID = req('shippingMethodID');

// Search query for shipping methods
$stm = $_db->prepare(
    'SELECT * FROM shippingmethod WHERE shippingName LIKE ?'
);

// Execute search with wildcards for partial matching
$stm->execute(["%$item%"]);

// Fetch results
$arr = $stm->fetchAll();

// Check if the form was submitted (POST request)
if (is_post()) {
    // Get the shippingMethodID from the POST data
    $shippingID = req('shippingMethodID');

    // Prepare and execute the update statement to mark as unavailable
    $stmUpdate = $_db->prepare('UPDATE shippingmethod SET shippingDescription = "Unavailable" WHERE shippingMethodID = ?');
    $stmUpdate->execute([$shippingID]);
    redirect('shipping.php');
}
?>

<head>
    <link rel="stylesheet" href="AdminCss/adminTable.css">
    <link rel="stylesheet" href="AdminCss/shipping.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<main>
    <h1 style="text-align:center;">Shipping List</h1>
    <!-- Search form -->
    <form method="get">
        <?= html_search('item', 'placeholder="Enter Shipping Method"') ?>
        <button>Search</button> <!-- default is submit -->
    </form>

    <p><?= count($arr) ?> record(s)</p>
    <div id="info"><?= temp('info') ?></div>

    <!-- Button to add new shipping method -->
    <div class="topButton">
        <a href="addshipping.php" id="addNewShipping" class="button">Add New Shipping Method</a>
    </div>

    <!-- Table for displaying shipping methods -->
    <table class="table">
        <tr>
            <th>Shipping Method ID</th>
            <th>Shipping Name</th>
            <th>Shipping Desc</th>
            <th>Shipping Cost(RM)</th>
            <th>Estimated Delivery Day</th>
            <th>Action</th>
        </tr>

        <?php foreach ($arr as $s): ?>
            <tr>
                <td><?= $s->shippingMethodID ?></td>
                <td><?= $s->shippingName ?></td>
                <td style="<?= ($s->shippingDescription === 'Unavailable') ? 'color: red; font-weight: bold;' : '' ?>">
                    <?= $s->shippingDescription ?>
                </td>
                <td><?= $s->shippingCost ?></td>
                <td><?= $s->estimatedDeliveryDay ?></td>

                <!-- Delete button inside a form -->
                <td class="buttonDesign">
                    <form method="post" action="">
                        <input type="hidden" name="shippingMethodID" value="<?= $s->shippingMethodID ?>">
                        <button data-get="shippingUpdate.php?shippingMethodID=<?= $s->shippingMethodID ?>" class="update">Update</button>
                        <button type="submit" data-confirm="Are you sure you want to delete this shipping method?">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</main>
</body>

</html>