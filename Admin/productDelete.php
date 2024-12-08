<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

$prodID = req('prodID');

// TODO: Modify the query to search by name, author, or prodID
$stm = $_db->prepare('UPDATE product SET prodStatus = "OutOfStock" WHERE prodID = ?');
$stm->execute([$prodID]);

if($stm){
    redirect('a_home.php');
}
?>
<div id="info"><?= temp('info') ?></div>

