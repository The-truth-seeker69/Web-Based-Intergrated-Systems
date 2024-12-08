<?php
$_title = 'ABC - Top 1 in Malaysia';  // Set page title
require '../_base.php';
include '../head.php';

$categoryID = req('categoryID');

// TODO: Modify the query to search by name, author, or prodID
$stm = $_db->prepare('UPDATE category SET categoryDesc = "Inactive" WHERE categoryID = ?');
$stm->execute([$categoryID]);

if($stm){
   redirect('categoryDetail.php');
}
else{
    temp('info', 'Invalid');
}
?>
<div id="info"><?= temp('info') ?></div>

