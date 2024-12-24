<?php
include '../../_base.php';
$_title = 'User | Password';
include '../header.php';

// ----------------------------------------------------------------------------
$_SESSION['userId'] = 1; // Simulating logged-in user
// Authenticated users
// auth();

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}

if (is_post()) {
    $old_password = req('old_password');
    $new_password = req('new_password');
    $confirm = req('confirm');

    // Validate old password
    if ($old_password == '') {
        $_err['old_password'] = 'Required';
    } else {
        // Ensure $user->id is available
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM user
            WHERE password = SHA1(?) AND id = ?
        ');
        $stm->execute([$old_password, $userId]);  // Check the user's old password

        // If no match found
        if ($stm->fetchColumn() == 0) {
            $_err['old_password'] = 'The password is incorrect.';
        }
    }

    // Validate: new_password
    if ($new_password == '') {
        $_err['new_password'] = 'Required';
    } elseif (strlen($new_password) < 8 ) {
        $_err['new_password'] = 'Password must be at least 8 CHARACTER !';
    } else if (!preg_match('/[A-Z]/', $new_password)) {
        $_err['new_password'] = 'Password must be at least 1 UPPERCASE !';
    } else if (!preg_match('/[a-z]/', $new_password)) {
        $_err['new_password'] = 'Password must be at least  1 LOWERCASE !';
    } else if (!preg_match('/[0-9]/', $new_password)) {
        $_err['new_password'] = 'Password must be at least 1 DIGIT !';
    } else if (!preg_match('/[\W_]/', $new_password)) { // Check for special characters
        $_err['new_password'] = 'Password must be at least 1 SPECIAL CHARACTER !';
    }

       // Verify new Password different
    $newPw = SHA1($new_password . $userId->userPasswordSalt);
    if ($newPw === $userId->userPassword) {
        $_err['new_password'] = 'New Password Cannot same be Old Password !!';
    }


    // Validate: confirm password
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    } elseif ($confirm != $new_password) {
        $_err['confirm'] = 'The passwords do not match.';
    }

    // If no errors, proceed to update the password
    if (!$_err) {
        $stm = $_db->prepare('
            UPDATE user
            SET password = SHA1(?)
            WHERE id = ?
        ');
        $stm->execute([$new_password, $userId]);  // Update the user's password

        temp('info', 'Password updated successfully');
        redirect('/User/page/changePassword.php');  // Redirect after successful password change
    }
}

// ----------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/changePassword.css">
    <script src="../../script/app.js"></script>
    <script src="../JS/userProfile.js"></script>
</head>
<body>
    <div class="container">
        <p>Change Password</p>

        <!-- Inner Container for Password Details -->
        <div class="password-container">
            <form method="post" class="form">
                <!-- Password Section -->
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" id="old_password" name="old_password" maxlength="100">
                </div>

                <!-- New Password Section -->
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" maxlength="100">
                           <div id="power-point" style="height: 5px; width: 0; background-color: #D73F40;"></div>
                    <span id="strength-text" style="display: none;">Weak</span> <!-- Initially hidden -->
                </div>

                 <!-- Password Strength Indicator -->
            <!-- Password Strength Indicator -->
                <div>
                    <div id="power-point" style="height: 5px; width: 0; background-color: #D73F40;"></div>
                    <span id="strength-text" style="display: none;">Weak</span> <!-- Initially hidden -->
                </div>

            
                <!-- Confirm Password Section -->
                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" maxlength="100">
                    <?php if (isset($_err['confirm'])): ?>
                        <span class="error"><?php echo $_err['confirm']; ?></span>
                    <?php endif; ?>
                </div>

                <!-- Buttons Section -->
                <div class="button-section">
                    <button type="submit">Submit</button>
                    <button type="reset">Reset</button>
                </div>
            </form>

           
        </div>
    </div>

    <!-- Include the script for password strength -->
    <script src="../js/script.js"></script>
</body>
</html>

<?php
include '../footer.php';
?>
