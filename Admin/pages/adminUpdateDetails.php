<?php
// Start the session
session_start();

// Include necessary files
include "../header.php";


// Check what session variables are set
auth();


if (is_get()) {
    $adminID = $_GET['id'];

    // Fetch admin data
    $stm = $_db->prepare('SELECT * FROM Admin WHERE adminid = ?');
    $stm->execute([$adminID]);
    $admin = $stm->fetch();
    $_SESSION['photo'] = $admin->adminPic;

    extract((array)$admin);
    print_r($admin);
    $name = $admin->adminName;
    $email = $admin->adminEmail;
    $phoneNo = $admin->adminPhoneNo;
}
if (is_post()) {
    $email = req('email');
    $name  = req('name');
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
        // TODO
        $stm = $_db->prepare('
                SELECT COUNT(*) FROM user
                WHERE email = ? AND id != ?
            ');
        $stm->execute([$email, $_user->id]);

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

    if (!$_err) {
        if ($f) {
            unlink("../../image/admin/uploads");
            $photo = save_photo($f, '../../image/admin/uploads');
        }

        $stm = $_db->prepare('
        UPDATE admin
        SET adminemail = ?, adminname = ?, adminpic = ?
        WHERE id = ?
    ');
        $stm->execute([$email, $name, $photo, $_user->id]);

        $_user->email = $email;
        $_user->name = $name;
        $_user->photo = $photo;

        temp('info', 'Record updated');
        redirect('/');
    }
}
?>

<body>

    <form id="admin-profile-form" method="post">
        <input type="hidden" name="adminID" value="<?= htmlspecialchars($admin->adminID) ?>">

        <label for="photo">Photo</label>
        <label class="upload" tabindex="0">
            <?= html_file('photo', 'image/*', 'hidden') ?>
            <img src="/photos/<?= $photo ?>">
        </label>

        <label for="adminName">Name:</label>
        <?= html_text('name', "required", 'maxlength="100"') ?>
        <?= err('email') ?>

        <label for="adminEmail">Email:</label>
        <?= html_text('email', "required", 'maxlength="100"') ?>
        <?= err('name') ?>
        <label for="adminPhoneNo">Phone Number:</label>
        <?= html_text('phoneNo', "required", 'maxlength="100"') ?>
        <?= err('photo') ?>


        <button type="submit">Update</button>
        <a href="viewAdminProfile.php" class="cancel-btn">Cancel</a>


</body>

</html>