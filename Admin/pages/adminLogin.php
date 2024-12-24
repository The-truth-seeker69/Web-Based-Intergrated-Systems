<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Style/general/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php 
    $_title = 'Admin Login';
    require '../../_base.php';
        $name=req('name');
        $pass=req('pass');

        if (isset($_COOKIE['remember_token_admin'])) {
            $token = $_COOKIE['remember_token_admin'];
        
            // Check if the token matches a user in the database
            $stm = $_db->prepare('SELECT * FROM admin WHERE rememberToken = ?');
            $stm->execute([$token]);
            $admin = $stm->fetch();
        
            if ($admin) {
                $_SESSION['adminId'] = $admin->adminID;
                $_SESSION['adminName'] = $admin->adminName;
                redirect('../../index.php');
            }
        }
    
        if (isset($_SESSION['autofillAdminName'])) {
            $GLOBALS['adminName'] = $_SESSION['autofillAdminName']; 
            unset($_SESSION['autofillAdminName']); 
        }
        if (isset($_SESSION['autofillAdminPass'])) {
            $GLOBALS['adminPass'] = $_SESSION['autofillAdminPass']; 
            unset($_SESSION['autofillAdminPass']); 
        }

    if(is_post()){
        
        $name=req('adminName');
        $pass=req('adminPass');
        $remember=req('remember');
        $captcha=req('captcha');

        if($name==''){
            $_err['name']='This field is required!';
        }

        if($pass==''){
            $_err['pass']='This field is required!';
        }

        if (empty($captcha)) {
            $_err['captcha'] = 'CAPTCHA is required!';
        } else if ($_SESSION['captcha'] !== $captcha) {
            $_err['captcha'] = 'CAPTCHA is incorrect!';
        }

        if(!$_err){
            
                $stm=$_db->prepare('SELECT * FROM admin WHERE adminname=? AND adminPassword=SHA1(?)');
                $stm->execute([$name,$pass]);
                $admin=$stm->fetch();
                
                if ($_SESSION['captcha'] !== $captcha) {
                    $_err['captcha'] = 'CAPTCHA is incorrect!';
                }

                if($admin){
                        temp('info', 'Login successfully');
                        
                        $_SESSION['adminId']=$admin->adminID;
                        $_SESSION['adminName']=$admin->adminName;

                        if ($remember) {
                            // Generate a remember-me token
                            $remember_token = bin2hex(random_bytes(32)); // 32 bytes = 64 characters in hex
                            $stm = $_db->prepare('UPDATE admin SET rememberToken = ? WHERE adminID = ?');
                            $stm->execute([$remember_token, $admin->adminID]);
                            setcookie('remember_token_admin', $remember_token, time() + (15 * 60), '/', '', true, true);//15minutes http only cookie
                        } 
                        login($admin,'../home.php');
                        redirect('../../index.php');
                    
                }else{
                    $_err['unmatch']='Username or password is invalid!';
                }

            

        }
    }
    ?>
    <div id="mainPanel">
        
        <form method="post" id="loginForm">
            <div><h2>Admin Login</h2></div>
            <div class="input-group">
            <i class="fa-solid fa-user"></i>
                <?= html_text('adminName','placeholder="Username"')?>
            </div>
            <?= err('name')?>

            <div class="input-group">
            <i class="fa-solid fa-lock"></i>
                <?= html_password('adminPass','placeholder="Password"')?>
            </div>
            <?= err('pass')?>
            <?= err('unmatch')?>

            <div id="captchabox">
            <?= html_text('captcha','placeholder="Enter what you see"')?>
            <img src="../../lib/captcha.php" alt="CAPTCHA" />
            </div>
            <?= err('captcha')?>

            <div class="actions">
                <label>
                <?= html_checkbox('remember')?> Remember Me
                </label>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>

        </form>
    </div>

    <div id="signupLink">
        
        <div>or</div>
        <div id="back"><a href="../../index.php">Back To Home</a></div>
    </div>
    
</body>
</html>