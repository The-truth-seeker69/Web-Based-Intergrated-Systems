<?php
require 'header.php';
$_title = 'User Profile';
// auth('Member');
$_SESSION['userId'] = 1;

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    // Fetch user data for both GET and POST requests
    $stm = $_db->prepare('SELECT * FROM user WHERE userID = ?');
    $stm->execute([$userId]);
    $result = $stm->fetch();

    if (!$result) {
        redirect('/');
    }

    extract((array)$result);
    $_SESSION['userPic'] = $result->userPic;
}

if (is_post()) {
    // Retrieve form inputs
    $email = req('userEmail');
    $userName = req('userName');
    $contact = req('userPhoneNo');
    $userPic = $_SESSION['userPic'];
    $f = get_file('userPic');

    $_err = [];

    // Validate: email
    if ($email == '') {
        $_err['userEmail'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['userEmail'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['userEmail'] = 'Invalid email';
    } else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM user WHERE userEmail = ? AND userID != ?');
        $stm->execute([$email, $userId]);

        if ($stm->fetchColumn() > 0) {
            $_err['userEmail'] = 'Duplicated';
        }
    }

    // Validate: userName
    if ($userName == '') {
        $_err['userName'] = 'Required';
    } else if (strlen($userName) > 100) {
        $_err['userName'] = 'Maximum 100 characters';
    }

    // Validate: contact
    if ($contact == '') {
        $_err['userPhoneNo'] = 'Required';
    } else if (!is_contact($contact)) {
        $_err['userPhoneNo'] = 'Contact Number Format must be 01x-xxxxxxx';
    } else {
        $stm = $_db->prepare('SELECT * FROM user WHERE userID = ?');
        $stm->execute([$userId]);
        $result = $stm->fetch();
        if ($contact !== $result->userPhoneNo && is_exists($contact, 'user', 'userPhoneNo')) {
            $_err['userPhoneNo'] = 'This Contact Number is in Use. Try Another.';
        }
    }

    // Validate: photo (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['userPic'] = 'Must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['userPic'] = 'Maximum 1MB';
        }
    }

    // DB operation
    if (!$_err) {
        // Delete old photo and save new photo (if uploaded)
        if ($f) {
            if (file_exists("../image/$userPic")) {
                unlink("../image/$userPic");
            }
            $userPic = save_photo($f, '../image');
        }

        // Update user details
        $stm = $_db->prepare('
            UPDATE user
            SET userEmail = ?, userName = ?, userPhoneNo = ?, userPic = ?
            WHERE userID = ?
        ');
        $stm->execute([$email, $userName, $contact, $userPic, $userId]);

        temp('info', 'User Details updated');
        redirect('/User/page/userProfile.php');
    } else {
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/userProfile.css">
    <script src="../../script/app.js"></script>
    <script src="../JS/userProfile.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="info"><?= temp('info') ?></div>
    <div class="account-container">
        <div class="account-details">
            <h1>Profile</h1>
            <form method="post" class="form" enctype="multipart/form-data">
                <div class="profile-photo-container" onmouseover="showIcons()" onmouseout="hideIcons()">
                    <label class="upload" tabindex="0">
                        <?= html_file('userPic', 'image/*', 'hidden') ?>
                        <img id="profilePhoto" src="/User/image/<?= $userPic ?>" alt="Profile Photo"
                            class="profile-photo">
                        <i class="fas fa-edit edit-photo-icon"></i>
                    </label>
                    <?= err('userPic') ?>
                </div>

                <div class="info-row">
                    <label for="userEmail" class="label">Email</label>
                    <input type="email" id="userEmail" name="userEmail" class="value"
                        value="<?= isset($email) ? $email : $result->userEmail ?>">
                </div>
                <div class='error'>
                    <?php
                    if (isset($_err['userEmail']) && $_err['userEmail'] != null) {
                        echo "<b><p>" . $_err['userEmail'] . "</p></b>";
                    }
                    ?>
                </div>

                <div class="info-row">
                    <label for="userName" class="label">Username</label>
                    <input type="text" id="userName" name="userName" class="value"
                        value="<?= isset($userName) ? $userName : $result->userName ?>">
                </div>
                <div class='error'>
                    <?php
                    if (isset($_err['userName'])) {
                        echo "<b><p>" . $_err['userName'] . "</p></b>";
                    }
                    ?>
                </div>
                <div class="info-row">
                    <label for="userPhoneNo" class="label">Contact</label>
                    <input type="text" id="userPhoneNo" name="userPhoneNo" class="value"
                        value="<?= isset($contact) ? $contact : $result->userPhoneNo ?>">
                </div>
                <div class='error'>
                    <?php
                    if (isset($_err['userPhoneNo'])) {
                        echo "<b><p>" . $_err['userPhoneNo'] . "</p></b>";
                    }
                    ?>
                    <br><br>
                </div>
                <section>
                    <button type="submit">Update</button>
                    <button type="button" class="btn-back"
                        onclick="window.location.href='../../index.php'">Back</button>
                </section>
            </form>
        </div>
    </div>

    <script>
        // Preview photo
        $('label.upload input[type=file]').on('change', e => {
            const f = e.target.files[0];
            const img = $(e.target).siblings('img')[0];
            if (!img) return;

            img.dataset.src ?? = img.src;

            if (!f) {
                // If no file is selected
                return;
            }

            if (f.type.startsWith('image/')) {
                // Set the new image URL as preview
                img.src = URL.createObjectURL(f);
            } else {
                // Reset to the default image if the file is not valid
                img.src = img.dataset.src;
                e.target.value = ''; // Clear the file input value
            }
        });
    </script>
</body>

</html>

<?php
include 'footer.php';
