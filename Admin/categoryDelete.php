<?php
$_title = 'Category';  // Set page title
require '../_base.php';
include '../head.php';

$categoryID = req('categoryID');

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

