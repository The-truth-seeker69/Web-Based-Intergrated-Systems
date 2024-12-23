<?php
session_start();

// Generate random string
$captcha_text = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6/strlen($x)) )),1,6);
$_SESSION['captcha'] = $captcha_text; // Store the captcha text in session

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
