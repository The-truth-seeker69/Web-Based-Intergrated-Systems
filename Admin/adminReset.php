<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/general/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Reset Password</title>
</head>
<body>
    <?php
    require '../_base.php';
    $captcha_key='admin_reset_captcha';
    if(is_post()){
        $captcha=req('captcha');
        $email = req('email');

        if ($email == '') {
            $_err['email'] = 'This field is required!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_err['email'] = 'Invalid email format!';
        }else{
            $_err['email']=false;
        }

        if(!$_err['email']){
            $emailExist=is_exists($email,'admin','adminEmail');
            if(!$emailExist){//Email not found
                $_err['email']='Invalid email!';
            }
        }

        if (empty($captcha)) {
            $_err['captcha'] = 'CAPTCHA is required!';
        } else if ($_SESSION[$captcha_key] !== $captcha) {
            $_err['captcha'] = 'CAPTCHA is incorrect!';
        }

        if(!in_array(true, $_err)){
             
        $stm = $_db->prepare('SELECT * FROM `admin` WHERE adminEmail = ?');
        $stm->execute([$email]);
        $admin = $stm->fetch();

        $id = sha1(uniqid() . rand());

        $stm = $_db->prepare('
            DELETE FROM adminToken WHERE adminId = ?;

            INSERT INTO adminToken (id, expiryDateTime, adminId)
            VALUES (?, ADDTIME(NOW(), "00:05"), ?);
        ');
        $stm->execute([$admin->adminID, $id, $admin->adminID]);

        $url = base("Admin/adminNewPass.php?id=$id");

        $m = get_mail();
        $m->addAddress($admin->adminEmail, $admin->adminName);
        $m->isHTML(true);
        $m->Subject = 'Reset Password';
        $m->Body = "
            <div style='border-bottom:1px solid #eee'>
                <a href='' style='font-size:1.5em;color: #000000;text-decoration:none;font-weight:650'>Unpopular</a>
                </div>
                <p>Dear $admin->adminName,<p>
                <p>
                    Trouble signing in?<br>
                    Resetting your password is easy.<br><br>
                    Just click <a href='$url'>here</a> to reset your password. We’ll have you up and running in no time.
                </p>
                <p>If you did not make this request, please ignore this email.</p>
                <hr style='border:none;border-top:1px solid #eee' />
                <p>Regards,</p>
                <p>Unpopular</p>
        ";
        $m->send();
            
        }
    }
    ?>
    <div id="mainPanel">
    <form method="post" id="loginForm">
            <div><h2>Reset Password</h2></div>
            <p>Enter email to reset your password</p>
            <div class="input-group">
            <i class="fa-solid fa-envelope"></i>
                <?= html_text('email','placeholder="Email"')?>
            </div>
            <?= err('email')?>

            <div id="captchabox">
            <?= html_text('captcha','placeholder="Enter what you see"')?>
            <img src="../../lib/captcha.php?form_type=admin_reset" alt="CAPTCHA" />
            </div>
            <?= err('captcha')?>

            <button type="submit" class="login-btn">Confirm</button>

        </form>
    </div>
    <div id="signupLink">
        
        <div>or</div>
        <div id="back"><a href="adminLogin.php">Back To Login</a></div>
    </div>
</body>
</html>