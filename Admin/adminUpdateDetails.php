<?php
// Start the session

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// Include necessary files
include "header.php";


// Check what session variables are set

$adminID = $_GET['id'];


if (is_get()) {
    // Fetch admin data
    $stm = $_db->prepare('SELECT * FROM Admin WHERE adminid = ?');
    $stm->execute([$adminID]);
    $admin = $stm->fetch();
    if (!$admin) {
        redirect('/');
    }
    $_SESSION['photo'] = $admin->adminPic;

    extract((array)$admin);
    $name = $admin->adminName;
    $email = $admin->adminEmail;
    $phoneNo = $admin->adminPhoneNo;
    $photo = $_SESSION['photo'];
}
if (is_post()) {


    $email = req('email');
    $name  = req('name');
    $phoneNo  = req('phoneNo');
    $photo = $_SESSION['photo'];
    $f = get_file('photo');


    //validate name
    if ($email == '') {
        $_err['email'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    } else {

        $stm = $_db->prepare('
                SELECT COUNT(*) FROM admin
                WHERE adminEmail = ? AND adminID != ?
            ');
        $stm->execute([$email, $adminID]);

        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Duplicated';
        }
    }

    if ($name == '') {
        $_err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }
    // db operation
    if (!$_err) {
        if ($f) {
            unlink("../image/admin/uploads/$photo");
            $photo = save_photo($f, '../image/admin/uploads');
        }

        $stm = $_db->prepare('
        UPDATE admin
        SET adminemail = ?, adminname = ?, adminpic = ? , adminphoneno = ?
        WHERE adminid = ?
    ');
        $stm->execute([$email, $name, $photo, $phoneNo, $adminID]);

        $_SESSION['photo'] = $photo;
        $_user->adminEmail = $email;
        $_user->adminName = $name;
        $_user->adminPhoneNo = $phoneNo;
        $_user->adminPic = $photo;

        temp('info', 'Record updated');
        redirect("viewAdminProfile.php");
    }
}
?>

<body>
    <div id="update-admin-container">
        <h1>Update Admin Details</h1>

        <form id="admin-profile-form" method="post" enctype="multipart/form-data">
            <div class="form-group" id="profile-pic-container">

                <label for="photo">Photo</label>
                <label class="upload" tabindex="0">
                    <?= html_file('photo', 'image/*', 'hidden') ?>
                    <img src='/image/admin/uploads/<?= $photo ?>' alt="Admin Photo">
                </label>
            </div>

            <label for="adminName">Name:</label>
            <?= html_text('name', "required", 'maxlength="100"') ?>
            <?php

            ?>
            <?= err('email') ?>

            <label for="adminEmail">Email:</label>
            <?= html_text('email', "required", 'maxlength="100"') ?>
            <?= err('name') ?>
            <label for="adminPhoneNo">Phone Number:</label>
            <?= html_text('phoneNo', "required", 'maxlength="100"') ?>
            <?= err('photo') ?>


            <button type="submit">Update</button>
            <a href="viewAdminProfile.php" class="cancel-btn">Cancel</a>

        </form>
    </div>

</body>

</html>