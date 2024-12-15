<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item');

// TODO: Modify the query to search by name, author, or prodID
$stm = $_db->prepare(
    'SELECT * FROM category WHERE categoryID LIKE ? OR  categoryName LIKE ?'
);

// Use the input for all columns with wildcards for partial matching
$stm->execute(["%$item%", "%$item%"]);

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

/* Red out-of-stock status styling */
.inactive {
    color: red; /* Change text color to red */
    font-weight: bold;
}

    </style>

</head>

<main>
    <h1 style="text-align:center;">Category List</h1>
     <!-- Here is search-->
<form>
    <?= html_search('item', 'placeholder="Enter ID OR NAME"') ?>
    <button>Search</button>  <!-- default is submit -->
</form>

<p><?= count($arr) ?> record(s)</p>
<div id="info"><?= temp('info') ?></div>
 <!-- Here is the button add category, add product-->
    <div class="topButton">
    <a href="addcategory.php" id="addNewCategory" class="button">Add New Category</a>
    </div>
    
 <!-- Here is the table -->
    <table class="table">
    <tr>
        <th>Category Id</th>
        <th>Category Name</th>
        <th>Desc</th>
        <th>Action</th>
    </tr>

    <?php foreach ($arr as $s): ?>
    <tr>
        <td><?= $s->categoryID ?></td>
        <td><?= $s->categoryName ?></td>
        <td class="<?= $s->categoryDesc === 'Inactive' ? 'inactive' : '' ?>">
        <?= $s->categoryDesc ?></td>
        <td class="buttonDesign">
            <!-- Here is the button for view, update, and also the delete -->
            <button data-get="categoryUpdate.php?categoryID=<?= $s->categoryID ?>">Update</button>
           <button data-post="categoryDelete.php?categoryID=<?= $s->categoryID ?>" data-confirm>Delete</button>
        </td>
    </tr> 
    <?php endforeach ?>
    </table>
</main>
</body>
</html>
