<?php
session_start();
$_user = $_SESSION['user'] ?? null;
function is_post()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null)
{
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Set or get temporary session variable
function temp($key, $value = null)
{
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    } else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}


// ============================================================================
// HTML Helpers
// ============================================================================

// Encode HTML special characters
function encode($value)
{
    return htmlentities($value);
}

// Generate <input type="file">
function html_file($key, $accept = '', $attr = '')
{
    // Get the value from the global variable or set an empty string if not set
    $value = $GLOBALS[$key] ?? '';
    // Generate the <input type="file"> element
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}



// Generate <textarea>
function html_textarea($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');  // Get the value from global variable
    echo "<textarea id='$key' name='$key' $attr>$value</textarea>";  // Generate the <textarea> element
}

// Generate <input type='text'>
function html_text($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='number'>
function html_number($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');  // Encode the value (from global) before displaying it
    echo "<input type='number' id='$key' name='$key' value='$value' $attr>";
}


// Generate <input type='search'>
function html_search($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='radio'> list
function html_radios($key, $items, $br = false)
{
    $value = encode($GLOBALS[$key] ?? '');
    echo '<div>';
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'checked' : '';
        echo "<label><input type='radio' id='{$key}_$id' name='$key' value='$id' $state>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
    echo '</div>';
}

// Generate <select>
function html_select($key, $items, $default = '- Select One -', $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<select id='$key' name='$key' $attr>";
    if ($default !== null) {
        echo "<option value=''>$default</option>";
    }
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'selected' : '';
        echo "<option value='$id' $state>$text</option>";
    }
    echo '</select>';
}


// Global error array
$_err = [];

// Generate <span class='err'>
function err($key)
{
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    } else {
        echo '<span></span>';
    }
}


// Obtain uploaded file --> cast to object
function get_file($key)
{
    $f = $_FILES[$key] ?? null;

    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// ============================================================================
// Database Setups and Functions
// ============================================================================

// Global PDO object
$_db = new PDO('mysql:dbname=bookdb', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

// Is unique?
function is_unique($value, $table, $field)
{
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() == 0;
}

// Is exists?
function is_exists($value, $table, $field)
{
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}

function redirect($url = null)
{
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}
// ============================================================================
// Global Constants and Variables
// ============================================================================

$_orderStatus = [
    'Pending' => 'Pending',
    'Processing' => 'Processing',
    'Shipped' =>  'Shipped',
    'Delivered' => 'Delivered',
    'Cancelled' => 'Cancelled'
];

//remember to change
//
$_productCategory = $_db->query('SELECT categoryID, categoryName FROM category WHERE categoryDesc <> "Inactive"')
    ->fetchAll(PDO::FETCH_KEY_PAIR);





function table_headers($fields, $sort, $dir, $href = '')
{
    foreach ($fields as $k => $v) {
        $d = 'asc'; // Default direction
        $c = '';    // Default class

        // TODO
        if ($k == $sort) {
            $d = $dir == 'asc' ? 'desc' : 'asc';
            $c = $dir;
        }
        echo "<th><a href='?sort=$k&dir=$d&$href' class='$c'>$v</a></th>";
    }
}


function save_photo($f, $folder, $width = 200, $height = 200)
{
    $photo = uniqid() . '.jpg';

    require_once 'lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');

    return $photo;
}


function login($user, $url = '/')
{
    $_SESSION['user'] = $user;
    var_dump($user);
    redirect($url);
}


function logout($url = '/')
{
    unset($_SESSION['user']);
    redirect($url);
}

function is_email($value)
{
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}


function html_password($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
}


function html_checkbox($key, $label = '', $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    $status = $value == 1 ? 'checked' : '';
    echo "<label><input type='checkbox' id='$key' name='$key' value='1' $status $attr>$label</label>";
}




// Generate <input type='text'>
function html_email($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='email' id='$key' name='$key' value='$value' $attr>";
}

function get_mail() {
    require_once 'lib/PHPMailer.php';
    require_once 'lib/SMTP.php';

    $m = new PHPMailer(true);
    $m->isSMTP();
    $m->SMTPAuth = true;
    $m->Host = 'smtp.gmail.com';
    $m->Port = 587;
    $m->Username = 'liaw.casual@gmail.com';
    $m->Password = 'buvq yftx klma vezl';
    $m->CharSet = 'utf-8';
    $m->setFrom($m->Username, 'Unpopular Admin');

    return $m;
}

function save_photo_from_data($data, $folder, $width = 200, $height = 200) {
    // Create a temporary file
    $tempFile = tempnam(sys_get_temp_dir(), 'img');

    // Write the raw image data to the temporary file
    file_put_contents($tempFile, $data);

    // Use the original save_photo function to process the temporary file
    $photo = save_photo((object) ['tmp_name' => $tempFile, 'type' => 'image/jpeg'], $folder, $width, $height);

    // Clean up the temporary file after processing
    unlink($tempFile);

    return $photo;
}
