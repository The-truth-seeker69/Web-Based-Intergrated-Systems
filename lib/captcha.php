<?php
session_start();

$form_type = isset($_GET['form_type']) ? $_GET['form_type'] : 'user_login';

switch ($form_type) {
    case 'admin_login':
        $session_key = 'admin_login_captcha';
        break;
    case 'admin_registration':
        $session_key = 'admin_registration_captcha';
        break;
    case 'user_registration':
        $session_key = 'user_registration_captcha';
        break;
    case 'user_reset':
        $session_key = 'user_reset_captcha';
        break;
    case 'admin_reset':
        $session_key = 'admin_reset_captcha';
        break;
    default:
        $session_key = 'user_login_captcha';
        break;
}

// Generate random string
$captcha_text = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6/strlen($x)) )),1,6);
$_SESSION[$session_key] = $captcha_text; // Store the captcha text in session

// Create the image
header('Content-Type: image/png');
$image = imagecreate(120, 40);

// Allocate colors
$background_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0);             // Black text
$line_color = imagecolorallocate($image, 200, 200, 200);       // Light gray lines

// Add random lines for noise
for ($i = 0; $i < 5; $i++) {
    imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
}

// Add the text
$font_path = __DIR__ . '/arial.ttf'; // Path to a TTF font file
imagettftext($image, 20, rand(-10, 10), 10, 30, $text_color, $font_path, $captcha_text);

// Output the image
imagepng($image);
imagedestroy($image);
