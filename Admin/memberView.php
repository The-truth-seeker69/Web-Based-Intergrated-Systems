<?php

include "header.php";



$fields = [
    'userID'          => 'ID',
    'userName'        => 'Name',
    'userEmail'       => 'Email',

];
$user_status = [
    'active'  => 'Active',
    'inactive' => 'Inactive'

];


$sort = req('sort');
key_exists($sort, $fields) || $sort = 'userID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);
//search parameter
$keyword = req('keyword');
$status = req('status');

//search user

$sql = 'SELECT * FROM user 
                WHERE (userName LIKE ? OR userEmail LIKE ? OR userPhoneNo LIKE ?)
        AND (userStatus = ? OR ? = "")
        ORDER BY ' . $sort . ' ' . $dir;


$params = [
    // % meaning anything come after or before the email is consider as matched
    "%$keyword%",
    "%$keyword%",  // Match email with wildcard
    "%$keyword%",
    $status ?? '',   // Exact match for status; default to empty string if not set
    $status ?? ''
];


require_once '../lib/SimplePager.php';
$p = new SimplePager($sql, $params, 10, $page);
$arr = $p->result;




// update status 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'selectedUsers' is set and not empty
    if (!empty($_POST['selectedUsers'])) {
        $selectedUsers = $_POST['selectedUsers'];

        // Sanitize user input
        $placeholders = implode(',', array_fill(0, count($selectedUsers), '?'));
        $sql = "UPDATE user
                SET userStatus = CASE
                    WHEN userStatus = 'active' THEN 'inactive'
                    ELSE 'active'
                END
                WHERE userID IN ($placeholders)";
        $stmt = $_db->prepare($sql);
        $stmt->execute($selectedUsers);

        header('Location: ' . $_SERVER['REQUEST_URI']);
        temp('info', 'Record updated');

        exit;
    } else {
        // Handle the case where no users were selected
        temp('info', 'Please select at least one user to update.');
    }
}

?>

<main>
    <!-- Flash message -->


    <!-- Search and Filter Section -->
    <form id="search-filter">
        <div id="info"><?= temp('info') ?></div>

        <label>Search :</label>
        <?= html_search('keyword', 'placeholder="Search...."') ?>

        <?= html_select('status', $user_status, 'All') ?>

        <button id="search-button">Search &#128269;</button>
    </form>

    <!-- Member Table -->
    <form method="POST" id="member-table">
        <table>
            <thead>
                <tr>
                    <?= table_headers($fields, $sort, $dir, "page=$page") ?>
                    <th>Phone No </th>
                    <th>Profile Pic </th>
                    <th>Status </th>
                </tr>
            </thead>
            <tbody id="member-list">
                <?php foreach ($arr as $u): ?>
                    <tr>

                        <td><?= $u->userID ?></td>
                        <td><?= $u->userName ?></td>
                        <td><?= $u->userEmail ?></td>
                        <td><?= $u->userPhoneNo ?></td>
                        <td>
                            <img src="/image/user/uploads/<?= $u->userPic ?>" alt="Profile Picture">
                        </td>
                        <td><?= $u->userStatus ?></td>
                        <td>
                            <input type="checkbox" name="selectedUsers[]" value="<?= $u->userID ?>">
                        </td>



                    </tr>
                <?php endforeach ?>


            </tbody>
        </table>

        <div class="update-status-wrapper">
            <div class="pagination">
                <?= $p->html("sort=$sort&dir=$dir&keyword=$keyword&status=$status") ?>
            </div>
            <div>
                <button data-confirm-update name="updateStatus">Update Status</button>

            </div>
        </div>

        <img src="" alt="">

    </form>
    </section>

</main>
<script src="../../script/app.js"></script>