<?php
include "../header.php";


$fields = [
    'adminID'          => 'ID',
    'adminName'        => 'Name',
    'adminEmail'       => 'Email',
];

$admin_role = [
    'manager'  => 'Manager',
    'admin' => 'Admin'

];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'adminID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';


$page = req('page', 1);
$keyword = req('keyword');
$role = req('role');
$sql = 'SELECT * FROM admin 
                WHERE (adminName LIKE ? OR adminEmail LIKE ? OR adminPhoneNo LIKE ?)
              AND (adminRole = ? OR ? = "")

        ORDER BY ' . $sort . ' ' . $dir;

$params = [
    // % meaning anything come after or before the email is consider as matched
    "%$keyword%",
    "%$keyword%",  // Match email with wildcard
    $keyword,
    $role ?? '',
    $role ?? ''

];
require_once '../../lib/SimplePager.php';
$p = new SimplePager($sql, $params, 10, $page);
$arr = $p->result;


?>




<main>
    <form id="search-filter">
        <?php

        ?>
        <label>Search :</label>
        <?= html_search('keyword', 'placeholder="Search...."', 'id=""') ?>

        <?= html_select('role', $admin_role, 'All') ?>


        <button id="search-button">Search &#128269;</button>
        <?= html_button("adminRegister.php", "Add New Admin  &#10010;", "id='add-admin-btn'") ?>
    </form>
    <form method="POST" id="member-table">
        <table>
            <thead>
                <tr>
                    <?= table_headers($fields, $sort, $dir, "page=$page") ?>
                    <th>Phone No </th>
                    <th>Profile Pic </th>
                    <th>Role </th>
                </tr>
            </thead>
            <tbody id="member-list">
                <?php foreach ($arr as $a): ?>
                    <tr>

                        <td><?= $a->adminID ?></td>
                        <td><?= $a->adminName ?></td>
                        <td><?= $a->adminEmail ?></td>
                        <td><?= $a->adminPhoneNo ?></td>
                        <td>
                            <img src="/image/admin/uploads/<?= $a->adminPic ?>" alt="Profile Picture">

                        </td>
                        <td><?= $a->adminRole ?></td>



                    </tr>
                <?php endforeach ?>


            </tbody>
        </table>
        <div class="update-status-wrapper">
            <div class="pagination">
                <?= $p->html("sort=$sort&dir=$dir&keyword=$keyword&role=$role") ?>
            </div>
        </div>
</main>