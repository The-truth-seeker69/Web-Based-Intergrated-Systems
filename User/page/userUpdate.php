<?php
require '../../base.php';

$_SESSION['user_url'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = 'lim@gmail.com';
    $_SESSION['username'] = 'Lim';
    $_SESSION['gender'] = 'Male';
    $_SESSION['contact'] = '012-3456789';
}

// Retrieve session data
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$gender = $_SESSION['gender'];
$contact = $_SESSION['contact'];

if(is_get()){
    $email = req('email');
    $userName = req('userName');
    $contact = req('contact');


    if($email == ''){
        $_err['email'] = 'Required';
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_err['email'] = 'Invalid format';
    }
    // else{
    //     $stm = $_db->prepare('SELECT * FROM user WHERE userId = ?')
    // }
   
    if($userName == ''){
        $_err['userName'] = 'Required';
    }

    if($contact == ''){
        $_err['email'] = 'Required';
    }
   else if(!preg_match('/^\d{10,11}$/', $contact)){

   }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/userProfile.css">
</head>
<body>
    <div class="account-container">
        <div class="account-details">
            <h1>Profile</h1>
            <form action="">
            <div class="info-section">
    <div class="info-row">
        <label for="email" class="label">Email</label>
        <input type="email" id="email" name="email" class="value" value="<?php echo htmlspecialchars($email); ?>" >
    </div>
    <div class="info-row">
        <label for="username" class="label">Username</label>
        <input type="text" id="username" name="username" class="value" value="<?php echo htmlspecialchars($username); ?>">
    </div>
    <div class="info-row">
        <label for="contact" class="label">Contact</label>
        <input type="tel" id="contact" name="contact" class="value" value="<?php echo htmlspecialchars($contact); ?>">
    </div>
</div>
<button type="button" class="btn-update" onclick="window.location.href='userUpdate.php'">Update</button>
<button type="button" class="btn-back" onclick="window.location.href='../../index.php'">Back</button>

</body>
</html>
