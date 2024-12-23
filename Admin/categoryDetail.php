<?php
$_title = 'Category';  // Set page title
require '../_base.php';
include '../head.php';

$item = req('item');

// Modify the query to search by name, author, or prodID
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
    <link rel="stylesheet" href="AdminCss/category.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <Style>



    </Style>
</head>

<main>
    <h1 style="text-align:center;">Category List</h1>
    <!-- Here is search-->
    <form>
        <?= html_search('item', 'placeholder="Enter ID OR NAME"') ?>
        <button>Search</button> <!-- default is submit -->
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
                    <?= $s->categoryDesc ?>
                </td>
                <td class="buttonDesign">
                    <!-- Here is the button for view, update, and also delete -->
                    <button class="btn updateButton"
                        data-get="categoryUpdate.php?categoryID=<?= $s->categoryID ?>">Update</button>
                    
                    <button class="btn deleteButton" 
                    data-post="categoryDelete.php?categoryID=<?= $s->categoryID ?>"
                    data-confirm="Are you sure you want to delete?">
                        Delete
                    </button>

                </td>

            </tr>
        <?php endforeach ?>
    </table>
</main>
</body>

</html>