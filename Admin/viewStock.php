<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

// TODO: Modify the query to search by name, author, or prodID
    $stm = $_db->prepare('SELECT * FROM product WHERE prodStatus = "OutOfStock"');
    $stm->execute();

// Fetch results
$arr = $stm->fetchAll();

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

.out-of-stock {
    color: red; /* Change text color to red */
    font-weight: bold;
}
    </style>

<script>
$(document).ready(function() {
    $('#add-stock-btn').click(function() {
        const quantity = prompt("Please enter the quantity to add:");

        if (quantity && !isNaN(quantity) && parseInt(quantity) > 0) {
            $('#restock-quantity').val(quantity); // Set the hidden input value
            $('#restock-form').submit(); // Submit the form
        } else {
            alert('Invalid input. Please try again.');
        }
    });
});
</script>


</head>

<main>
     <!-- Here is search-->
<form>
    <?= html_search('item') ?>
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
            <button data-get="productActive.php?prodID=<?= $s->prodID ?>" data-confirm>Active</button>
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
