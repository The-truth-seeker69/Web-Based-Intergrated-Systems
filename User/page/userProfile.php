<?php
$_title = 'User Profile';
require '../../_base.php';
// auth('Member');
$_SESSION['userId'] = 1;

if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
}

if(is_get()){
    $stm = $_db->prepare('SELECT * FROM user WHERE userID = ?');
    $stm->execute([$userId]);
    $result = $stm->fetch();

    if(!$result){
        redirect('/');
    }

    extract((array)$result);
    $_SESSION['userPic'] = $result->userPic;
}

// if(isset($_GET['userID']))
//     echo $_GET['userID'];


//Update
if (is_post()) {
    $email = req('userEmail');
    $userName  = req('userName');
    $contact = req('contact');
    $userPic = $_SESSION['userPic'];
    $f = get_file('userPic');


    // Validate: email
    if ($email == '') {
        $_err['userEmail'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['userEmail'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['userEmail'] = 'Invalid email';
    }
    else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM user
            WHERE userEmail = ? AND id != ?
        ');
        $stm->execute([$email, $user->id]);

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
        $_err['contact'] = 'Required';
    } else if (!is_contact($contact)) {
        $_err['contact'] = 'Contact Number Format must be 01x-xxxxxxx';
    } else {
        $stm = $_db->prepare('SELECT * FROM user WHERE userId = ?');
        $stm->execute([$userId->userId]);
        $result = $stm->fetch();
        // Check if the contact exists in the database and is used by another user
        if ($contact !== $result->contact && is_exists($contact, 'user', 'contact')) {
            $_err['contact'] = 'This Contact Number is in Use. Try Another.';
        }
    }

   
    // Validate: photo (file) --> optional
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['userPic'] = 'Must be image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['userPic'] = 'Maximum 1MB';
        } else if ($f == null) {
            $_err['userPic'] = 'Required';
        }
    }



    // DB operation
    if (!$_err) {
        // (1) Delete and save photo --> optional
        if ($f) {
            unlink("../Uploaded_profile/$userPic");
            $userPic = save_photo_user($f, '../../Admin/Uploaded_profile');
        }

        // (2) Update user (contact, name, photo)
        $stm = $_db->prepare('
            UPDATE user
            SET email = ? , userName = ?, contact = ?, userPic = ?
            WHERE userId = ?
        ');
        $stm->execute([$email, $userName, $contact, $userPic, $userId->userId]);

        // (3) Update global user object
        $user->email = $email;
        $user->userName  = $userName;
        $user->contact = $contact;
        $user->userPic = $userPic;

        temp('info', 'Account Details updated');
        redirect('/User/page/account.php');
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <div class="account-container">
        <div class="account-details">
            <h1>Profile</h1>
            <form method="post" class="form" enctype="multipart/form-data">
           <div class="profile-photo-container" onmouseover="showIcons()" onmouseout="hideIcons()">
            <label class="upload" tabindex="0">
                <?= html_file('photo', 'image/*', 'hidden') ?>
                <img id="profilePhoto" src="/Admin/Uploaded_profile/<?= $photo ?>" alt="Profile Photo" class="profile-photo">

                <!-- Edit icon -->
                <i class="fas fa-edit edit-photo-icon"></i>
            </label>
            <?= err('userPic') ?>

    <div class="info-row">
        <label for="email" class="label">Email</label>
        <input type="email" id="email" name="email" class="value" value="<?= $result->userEmail ?>">
    </div>
    <div class="info-row">
        <label for="username" class="label">Username</label>
        <input type="text" id="username" name="username" class="value" value="<?php echo $result->userName; ?>">
    </div>
</div>
</div>

<section>
<button>Update</button>
<button type="button" class="btn-back" onclick="window.location.href='../../index.php'">Back</button>
</section>

</body>
    
</html>
