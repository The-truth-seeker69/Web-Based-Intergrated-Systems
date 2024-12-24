<?php
require '../_base.php';
session_start();
session_destroy(); // Destroy session

// Clear admin cookies and unset remember me token
if (isset($_COOKIE['remember_token_admin'])) {
    $token = $_COOKIE['remember_token_admin'];
    $stm = $_db->prepare('UPDATE admin SET rememberToken = NULL WHERE rememberToken = ?');
    $stm->execute([$token]);
    setcookie('remember_token_admin', '', time() - 3600, '/', '', true, true); // Expire the cookie
}

redirect('adminLogin.php');
