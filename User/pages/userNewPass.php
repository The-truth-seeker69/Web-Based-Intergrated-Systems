<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Style/general/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Reset Password</title>
</head>

<body>

    <?php
    include '../../_base.php';

    $_db->query('DELETE FROM token WHERE expiryDateTime < NOW()');

    $id = req('id');

    if (!is_exists($id, 'token', 'id')) {
        temp('info', 'Invalid token. Try again');
        redirect('/');
    }

    if (is_post()) {
        $password = req('password');
        $confirm  = req('confirm');

        if ($password == '') {
            $_err['password'] = 'This field is required!';
        } elseif (strlen($password) < 6) {
            $_err['password'] = 'Password must be at least 6 characters long.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $password)) {
            $_err['password'] = 'Password must include at least one uppercase, lowercase letter, number, and symbol.';
        } else {
            $_err['password'] = false;
        }

        if ($confirm == '') {
            $_err['confirm'] = 'This field is required!';
        } elseif ($password !== $confirm) {
            $_err['confirm'] = 'Passwords do not match!';
        } else {
            $_err['confirm'] = false;
        }

        if (!in_array(true, $_err)) {

            $stm = $_db->prepare('
            UPDATE user
            SET userPassword = SHA1(?)
            WHERE userId = (SELECT userId FROM token WHERE id = ?);

            DELETE FROM token WHERE id = ?;
        ');
            $stm->execute([$password, $id, $id]);

            temp('info', 'Record updated');
            redirect('userLogin.php');
        }
    }

    ?>
    <div id="mainPanel">

        <form method="post" class="form">
            <div>
                <h2>Reset Password</h2>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <?= html_password('password', 'placeholder="New Password"') ?>
            </div>
            <?= err('password') ?>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <?= html_password('confirm', 'placeholder="Confirm Password"') ?>
            </div>
            <?= err('confirm') ?>

            <button type="submit" class="login-btn">Confirm</button>
        </form>



    </div>
    <div id="signupLink">

        <div>or</div>
        <div id="back"><a href="userLogin.php">Back To Login</a></div>
    </div>
</body>

</html>