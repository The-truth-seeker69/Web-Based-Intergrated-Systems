<?php
$_title = 'Stock';  // Set page title
include 'header.php';

    $stm = $_db->prepare('SELECT * FROM product WHERE prodStatus = "OutOfStock"');
    $stm->execute();

// Fetch results
    $arr = $stm->fetchAll();

?>
<head>
    <link rel="stylesheet" href="../Style/admin/adminTable.css">
    <link rel="stylesheet" href="../Style/admin/stock.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<main>
     <!-- Here is search-->
<form>
    <?= html_search('item', 'placeholder="Enter Book ID"') ?>
    <button>Search</button>  <!-- default is submit -->
</form>

<p><?= count($arr) ?> record(s)</p>
<div id="info"><?= temp('info') ?></div>
 <!-- Here is the button add category, add product-->
    
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
        <td class="<?= $s->prodStock === 0 ? 'out-of-stock' : '' ?>">
        <?= $s->prodStock ?>
        <td><?= $s->prodStatus ?></td>
        <td class="buttonDesign">
            <!-- Here is the button for view, update, and also the delete -->
            <button class="add-stock-btn" data-post="productRestock.php?prodID=<?= $s->prodID ?>">Add stock</button>

        </td>
    </tr> 
    <?php endforeach ?>
    </table>
</main>
</body>
</html>

<script>
$(document).ready(function () {
  $('.add-stock-btn').on('click', function (e) {
    e.preventDefault(); // Prevent default behavior

    const url = $(this).data('post');
    const quantity = prompt("Please enter the quantity to add:");

    if (quantity && !isNaN(quantity) && parseInt(quantity) > 0) {
      // Dynamically create and submit the POST request with the entered quantity
      const form = $('<form>')
        .appendTo(document.body)
        .attr({
          method: 'POST',
          action: url,
        });

      $('<input>')
        .attr({
          type: 'hidden',
          name: 'quantity',
          value: quantity,
        })
        .appendTo(form);

      form.submit();
    } else {
      alert("Invalid input. Please enter a valid number.");
    }
  });
});

</script>
