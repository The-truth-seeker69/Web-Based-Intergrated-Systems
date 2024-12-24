<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Style/general/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php 
    $_title = 'Create Account';
    require '../../_base.php';
    if(is_post()){

        $name=req('name');
        $email = req('email');
        $newPass = req('newPass');
        $conPass = req('conPass');
        $phone = req('phone');
        $pfp=get_file('photo');
        $autofill=req('autofill');
        $captcha=req('captcha');

        if($name==''){
            $_err['name']='This field is required!';
        } elseif (strlen($name) < 3) {
            $_err['name'] = 'Username must be at least 3 characters long.';
        }elseif(!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $name)){
            $_err['name']='Username must start with an alphabet and contain only numbers or _.';
        }else{
            $_err['name']=false;
        }

        if(!$_err['name']){
            $userExist=is_exists($name,'admin','adminName');
            if($userExist){//Username taken
                $_err['name']='Username is taken.';
            }
        }

        
        if ($email == '') {
            $_err['email'] = 'This field is required!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_err['email'] = 'Invalid email format!';
        }else{
            $_err['email']=false;
        }

        if(!$_err['email']){
            $emailExist=is_exists($email,'admin','adminemail');
            if($emailExist){//Username taken
                $_err['email']='Email is already in use.';
            }
        }

        if ($newPass == '') {
            $_err['newPass'] = 'This field is required!';
        } elseif (strlen($newPass) < 6) {
            $_err['newPass'] = 'Password must be at least 6 characters long.';
        }elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $newPass)) {
            $_err['newPass'] = 'Password must include at least one uppercase, lowercase letter, number, and symbol.';
        }else{
            $_err['newPass']=false;  
        }

        if ($conPass == '') {
            $_err['conPass'] = 'This field is required!';
        } elseif ($newPass !== $conPass) {
            $_err['conPass'] = 'Passwords do not match!';
        }else{
            $_err['conPass']=false;  
        }

        if ($phone == '') {
            $_err['phone'] = 'This field is required!';
        } elseif (!preg_match('/^01\d{8,9}$/', $phone)) {
            $_err['phone'] = 'Phone number must start with 01 and be 10 or 11 digits long.';
        } else {
            // Additional length checks for specific prefixes
            if ((str_starts_with($phone, '010') || str_starts_with($phone, '015')) && strlen($phone) !== 11) {
                $_err['phone'] = 'Phone number starting with 010 or 015 must be exactly 11 digits.';
            } elseif (!in_array(substr($phone, 0, 3), ['010', '015']) && strlen($phone) !== 10) {
                $_err['phone'] = 'Phone number not starting with 010 or 015 must be exactly 10 digits.';
            }else{
                $_err['phone']=false;
            }
        }

        if(!$_err['phone']){
            $phoneExist=is_exists($phone,'admin','adminphoneno');
            if($phoneExist){//Username taken
                $_err['phone']='Phone number is already in use.';
            }
        }

        if (empty($captcha)) {
            $_err['captcha'] = 'CAPTCHA is required!';
        } else if ($_SESSION['captcha'] !== $captcha) {
            $_err['captcha'] = 'CAPTCHA is incorrect!';
        }


        if ($pfp) {

            if (!str_starts_with($pfp->type, 'image/')) {
                $_err['photo'] = 'Must be an image.';
            } elseif ($pfp->size > 1 * 1024 * 1024) {
                $_err['photo'] = 'Maximum size is 1MB.';
            }
        } 

        if(!in_array(true, $_err)){//Passed all form validation and ready for db insert        

            if($pfp){//If there is an image
            $photo=save_photo($pfp,'../image');

            $stm=$_db->prepare('INSERT INTO admin (adminName, adminemail, adminpassword, adminphoneno, adminpic, adminRole) VALUES (?, ?, SHA1(?), ?, ?, ?)');
            $success=$stm->execute([$name,$email,$newPass,$phone,$photo,'Admin']);
            }else{//Register without image
                $stm=$_db->prepare('INSERT INTO admin (adminName, adminEmail, adminPassword, adminPhoneNo, adminRole) VALUES (?, ?, SHA1(?), ?, ?)');
                $success=$stm->execute([$name,$email,$newPass,$phone,'Admin']);
            }
            if($success){
                if($autofill){
                session_start();
                $_SESSION['autofillAdminName'] = $name;
                $_SESSION['autofillAdminPass'] = $newPass;
                }
                redirect('adminLogin.php');
            }
        }

    }
    ?>
    <div id="mainPanel">
        
        <form method="post" id="loginForm">
            <div><h2>Create New Admin</h2></div>

            <div class="picrow">
            <label class="profilepic">
            <?= html_file('photo', 'image/*', 'hidden') ?>
            <img src="../../image/user/uploads/ppplaceholder.png" id="profilepic" alt="Profile Picture">
            </label>
            </div>

            <div class="picrow">
                Profile picture (Optional)
            </div>

            <div class="picrow">
                <?= err('photo')?>
            </div>

            <div class="row">

            <div class="input-group">
            <i class="fa-solid fa-user"></i>
                <?= html_text('name','placeholder="Username"')?>
            </div>
            

            <div class="input-group">
            <i class="fa-solid fa-envelope"></i>
                <?= html_text('email','placeholder="Email"')?>
            </div>
            
            </div>

            <div class="errRow">
            <?= err('name')?>
            <?= err('email')?>
            </div>
            
            <div class="row">

            <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <?= html_password('newPass','placeholder="New Password"')?>
            </div>
            <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <?= html_password('conPass','placeholder="Confirm Password"')?>
            </div>

            </div>

            <div class="errRow">
            <?= err('newPass')?>
            <?= err('conPass')?>
            </div>

            

            <div class="input-group" id="phone">
            <i class="fa-solid fa-phone"></i>
            <?= html_text('phone','placeholder="Phone Number"')?>
            </div>

            

            <div class="row">
            <?= err('phone')?>
            </div>

            <div id="captchabox">
            <?= html_text('captcha','placeholder="Enter what you see"')?>
            <img src="../../lib/captcha.php" alt="CAPTCHA" />
            </div>
            <?= err('captcha')?>

            <div class="actions">
                <label >
                <?= html_checkbox('autofill')?> Autofill login
                </label>
                
            </div>

            <button type="submit" class="login-btn">Confirm</button>

        </form>
    </div>

    <div id="signupLink">
        
        <div>or</div>
        <div id="back"><a href="index.php">Continue As Guest</a></div>
    </div>
    
</body>
</html>