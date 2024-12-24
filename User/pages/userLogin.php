<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Style/general/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Member Login</title>
</head>

<body>
    <?php
    require '../../_base.php';
    $captcha_key = 'user_login_captcha';
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        // Check if the token matches a user in the database
        $stm = $_db->prepare('SELECT * FROM user WHERE rememberToken = ?');
        $stm->execute([$token]);
        $user = $stm->fetch();

        if ($user) {
            $_SESSION['userId'] = $user->userID;
            $_SESSION['userName'] = $user->userName;
            redirect('../index.php');
        }
    }

    if (isset($_SESSION['autofillName'])) {
        $GLOBALS['name'] = $_SESSION['autofillName'];
        unset($_SESSION['autofillName']);
    }
    if (isset($_SESSION['autofillPass'])) {
        $GLOBALS['pass'] = $_SESSION['autofillPass'];
        unset($_SESSION['autofillPass']);
    }

    if (is_post()) {
        $name = req('name');
        $pass = req('pass');
        $remember = req('remember');
        $captcha = req('captcha');

        if ($name == '') {
            $_err['name'] = 'This field is required!';
        }

        if ($pass == '') {
            $_err['pass'] = 'This field is required!';
        }

        if (empty($captcha)) {
            $_err['captcha'] = 'CAPTCHA is required!';
        } else if ($_SESSION[$captcha_key] !== $captcha) {
            $_err['captcha'] = 'CAPTCHA is incorrect!';
        }

        if (!$_err) {

            $stm = $_db->prepare('SELECT * FROM user WHERE username=? AND userPassword = SHA1(?)');
            $stm->execute([$name, $pass]);
            $user = $stm->fetch();

            if ($_SESSION[$captcha_key] !== $captcha) {
                $_err['captcha'] = 'CAPTCHA is incorrect!';
            }

            if ($user) { //Username and password match

                $_SESSION['userId'] = $user->userID;
                $_SESSION['userName'] = $user->userName;

                if ($remember) {
                    // Generate a remember-me token
                    $remember_token = bin2hex(random_bytes(32)); // 32 bytes = 64 characters in hex
                    $stm = $_db->prepare('UPDATE user SET rememberToken = ? WHERE userID = ?');
                    $stm->execute([$remember_token, $user->userID]);
                    setcookie('remember_token', $remember_token, time() + (15 * 60), '/', '', true, true); //15minutes http only cookie
                }

                redirect('../../index.php');
            } else {
                $_err['unmatch'] = 'Username or password is invalid!';
            }
        }
    }
    ?>
    <div id="mainPanel">

        <form method="post" class="loginForm">
            <div>
                <h2>Member Login</h2>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <?= html_text('name', 'placeholder="Username"') ?>
            </div>
            <?= err('name') ?>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <?= html_password('pass', 'placeholder="Password"') ?>
            </div>

            <?= err('pass') ?>
            <?= err('unmatch') ?>

            <div id="captchabox">
                <?= html_text('captcha', 'placeholder="Enter what you see"') ?>
                <img src="../../lib/captcha.php?form_type=user_login" alt="CAPTCHA" />
            </div>
            <?= err('captcha') ?>
            <div class="actions">
                <label>
                    <?= html_checkbox('remember') ?> Remember Me
                </label>
                <a href="userReset.php" class="forgot-password">Forgot Password?</a>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
    <div id="signupLink">
        <a href="userRegister.php">Don't have an account? Sign up now!</a>
        <div>or</div>
        <div><a href="../../index.php">Continue as Guest</a></div>
    </div>

</body>

</html>