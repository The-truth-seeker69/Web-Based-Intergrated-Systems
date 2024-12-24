<?php
$_title = 'Discount';  // Set page title
include 'header.php';

$item = req('item'); // searching purpose
$discountCodeID = req('discountCodeID');

// Search query for shipping methods
$stm = $_db->prepare(
    'SELECT * FROM discountcode WHERE discountcode LIKE ? OR discountCodeID LIKE ?'
);

// Execute search with wildcards for partial matching
$stm->execute(["%$item%", "%$item%"]);

// Fetch results
$arr = $stm->fetchAll();

// Check if the form was submitted (POST request)
// When delete the discount come here to do some action
if (is_post()) {
    // Get the shippingMethodID from the POST data
    $discountCodeID = req('discountCodeID');

    // Prepare and execute the update statement to mark as unavailable
    $stmUpdate = $_db->prepare('UPDATE discountcode SET discountDesc = "Unavailable" WHERE discountCodeID = ?');
    $stmUpdate->execute([$discountCodeID]);
    redirect('discount.php');
}

// Get the current date-time
$currentDate = date('Y-m-d H:i:s');

//check whether there have expired but haven't to mark as unavailable
$stmExpiredDiscounts = $_db->prepare(
    'SELECT discountCodeID, endDate FROM discountcode WHERE endDate < ? AND discountDesc != "Unavailable"'
);
//using current(right now) date-time
$stmExpiredDiscounts->execute([$currentDate]);

//get all result and store in expiredDiscounts
$expiredDiscounts = $stmExpiredDiscounts->fetchAll();

// Loop through each expired discount and update its description
//as expired -> as key -> value (get the value of discountCodeID) and store to disID
foreach ($expiredDiscounts as $expired) {
    $disID = $expired->discountCodeID;

    // Update the expired discounts to have "Unavailable" description
    //using looping to update
    //after done go to current page.
    $stmUpdate = $_db->prepare('UPDATE discountcode SET discountDesc = "Unavailable" WHERE discountCodeID = ?');
    $stmUpdate->execute([$disID]);
    redirect('discount.php');
}

?>


<head>
    <link rel="stylesheet" href="../Style/admin/adminTable.css">
    <link rel="stylesheet" href="../Style/admin/shipping.css"> <!-- same design some direct use the shipping.css design -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<main>
    <h1 style="text-align:center;">Discount List</h1>
    <!-- Search form -->
    <form method="get">
        <?= html_search('item', 'placeholder="Enter Discount Code / ID"') ?>
        <button>Search</button> <!-- default is submit -->
    </form>

    <p><?= count($arr) ?> record(s)</p>
    <div id="info"><?= temp('info') ?></div>

    <!-- Button to add new shipping method -->
    <div class="topButton">
        <a href="adddiscount.php" id="addNewShipping" class="button">Add New Discount</a>
    </div>

    <!-- Table for displaying shipping methods -->
    <table class="table">
        <tr>
            <th>Discount Code ID</th>
            <th>Discount Code</th>
            <th>Discount Percentage (%)</th>
            <th>Discount Desc</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>

        <?php foreach ($arr as $s): ?>
            <tr>
                <td><?= $s->discountCodeID ?></td>
                <td><?= $s->discountCode ?></td>
                <td><?= $s->discountPercentage ?></td>
                <td style="<?= ($s->discountDesc === 'Unavailable') ? 'color: red; font-weight: bold;' : '' ?>">
                    <?= $s->discountDesc ?>
                </td>
                <td><?= $s->startDate ?></td>
                <td><?= $s->endDate ?></td>

                <!-- Delete button inside a form -->
                <td class="buttonDesign">
                    <form method="post" action="">
                        <input type="hidden" name="discountCodeID" value="<?= $s->discountCodeID ?>">
                        <button data-get="discountUpdate.php?discountCodeID=<?= $s->discountCodeID ?>"
                            class="update">Update</button>
                        <button type="submit"
                        data-confirmation="Are you sure you want to delete this discount method?">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</main>
</body>

</html>