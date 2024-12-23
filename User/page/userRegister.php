<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Style/general/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../script/register.js"></script>
    <title>Create Account</title>
</head>
<body>
    <?php 
    require '../../_base.php';
    if (!isset($_SESSION['formStage'])) {
        $_SESSION['formStage'] = 'register';
    }
    if(is_post()){
        
        if (isset($_POST['register'])) {
            
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
            $userExist=is_exists($name,'user','userName');
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
            $emailExist=is_exists($email,'user','useremail');
            if($emailExist){//Email taken
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
            $phoneExist=is_exists($phone,'user','userphoneno');
            if($phoneExist){//Phone num taken
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

            $otp = random_int(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 300; // 5 minutes
            $_SESSION['registration_data'] = [
                'name' => $name,
                'email' => $email,
                'password' => $newPass,
                'phone' => $phone,
                'photo' => $pfp ? file_get_contents($pfp->tmp_name) : null,
                'autofill' => $autofill
            ];

            $m = get_mail();
            $m->addAddress($email, $name);
            $m->isHTML(true);
            $m->Subject = 'Account Activation';
            $m->Body = "
                <div style='border-bottom:1px solid #eee'>
                <a href='' style='font-size:1.5em;color: #000000;text-decoration:none;font-weight:650'>Unpopular</a>
                </div>
                <p>Dear $name,<p>
                <p>
                    Thank you for choosing Unpopular. Use the following OTP to activate your account. OTP is valid for 5 minutes.
                </p>
                <h2 style='background: #000000;margin: 0 auto;width: max-content;padding: 0 10px;color: #ffffff;border-radius: 4px;'>$otp</h2>
                <hr style='border:none;border-top:1px solid #eee' />
                <p>Regards,</p>
                <p>Unpopular</p>
            ";
            $m->send();
            $_SESSION['formStage'] = 'verify';
 
        }

    }elseif (isset($_POST['verify'])) {

        if (time() > $_SESSION['otp_expiry']) {
            unset($_SESSION['otp'], $_SESSION['otp_expiry']); // Clear OTP-related session data
            $_SESSION['formStage'] = 'register'; 
        }

        $inputOtp=req('otpInput');
        if($inputOtp==''){
            $_err['otpInput']='This field is required!';
        }else{

        if ($inputOtp == $_SESSION['otp'] && time() <= $_SESSION['otp_expiry']) {//otp correct
            $formData = $_SESSION['registration_data'];
            if($formData['photo']!=null){//insert with image
                $photo=save_photo_from_data($formData['photo'],'../../image/user/uploads');
                $stm=$_db->prepare('INSERT INTO user (userName, useremail, userpassword, userphoneno, userpic) VALUES (?, ?, SHA1(?), ?, ?)');
                $success=$stm->execute([$formData['name'],$formData['email'],$formData['password'],$formData['phone'],$photo]);
            }else{
                $stm=$_db->prepare('INSERT INTO user (userName, useremail, userpassword, userphoneno) VALUES (?, ?, SHA1(?), ?)');
                $success=$stm->execute([$formData['name'],$formData['email'],$formData['password'],$formData['phone']]);
            }

            if(!empty($success)){
                if($formData['autofill']){
                session_start();
                $_SESSION['autofillName'] = $formData['name'];
                $_SESSION['autofillPass'] = $formData['password'];
                }
                unset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['registration_data']);
                $_SESSION['formStage']='register'; // Reset form stage
                redirect('userLogin.php');
                
            }

        }else{
            $_err['otpInput']='OTP is incorrect!';
            $_SESSION['formStage'] = 'verify';
        }

        

        }
    }

    }
    ?>
    <div id="mainPanel">

    <?php if ($_SESSION['formStage'] === 'register'): ?>

        <form method="post" enctype="multipart/form-data" id="loginForm">
            <div><h2>Create Your Account</h2></div>

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

            <button type="submit" class="btn" name="register" >Confirm</button>

        </form>

        <?php elseif ($_SESSION['formStage'] === 'verify'): ?>

        <form method="post" action="">
            <p>An OTP has been sent to your email. </p><p>Please enter it below to activate your account:</p>
            <div class="input-group" id="otp" >
            <?= html_text('otpInput','placeholder="Enter OTP"')?>
            </div>
            <?= err('otpInput')?>

            <button type="submit" class="btn" name="verify">Verify OTP</button>
        </form>

        <?php endif; ?>

    </div>

    <div id="signupLink">
        
        <div>or</div>
        <div id="back"><a href="../../index.php">Continue As Guest</a></div>
    </div>
    
</body>
</html>