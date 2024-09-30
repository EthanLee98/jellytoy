<?php

// ============================================================================
// PHP Setups
// ============================================================================

date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

// ============================================================================
// General Page Functions
// ============================================================================

// Is GET request?
function is_get()
{
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Is POST request?
function is_post()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Obtain GET parameter
function get($key, $value = null)
{
    $value = $_GET[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain POST parameter
function post($key, $value = null)
{
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null)
{
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Redirect to URL
function redirect($url = null)
{
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
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

// Obtain uploaded file --> cast to object
function get_file($key)
{
    $f = $_FILES[$key] ?? null;

    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

// Crop, resize and save photo
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

// Is money?
function is_money($value)
{
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}

// Is email?
function is_email($value)
{
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

// Return local root path
function root($path = '')
{
    return "$_SERVER[DOCUMENT_ROOT]/$path";
}

// Return base url (host + port)
function base($path = '')
{
    return "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/$path";
}

function temp_new($type, $message) {
    $_SESSION['toast'] = [
        'type' => $type,
        'message' => $message
    ];
}


// ============================================================================
// HTML Helpers
// ============================================================================

// Placeholder for TODO
function TODO()
{
    echo '<span>TODO</span>';
}

// Encode HTML special characters
function encode($value)
{
    return htmlentities($value);
}

// Generate <input type='hidden'>
function html_hidden($key, $attr = '')
{
    $value ??= encode($GLOBALS[$key] ?? '');
    echo "<input type='hidden' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='text'>
function html_text($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='password'>
function html_password($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
}

// Generate <input type='number'>
function html_number($key, $min = '', $max = '', $step = '', $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value'
                 min='$min' max='$max' step='$step' $attr>";
}

// Generate <input type='search'>
function html_search($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}

// Generate <textarea>
function html_textarea($key, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<textarea id='$key' name='$key' $attr>$value</textarea>";
}

// Generate SINGLE <input type='checkbox'>
function html_checkbox($key, $label = '', $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    $status = $value == 1 ? 'checked' : '';
    echo "<label><input type='checkbox' id='$key' name='$key' value='1' $status $attr>$label</label>";
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

// Generate <input type='file'>
function html_file($key, $accept = '', $attr = '')
{
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}

// Generate table headers <th>
function table_headers($fields, $sort, $dir, $href = '')
{
    foreach ($fields as $k => $v) {
        $d = 'asc'; // Default direction
        $c = '';    // Default class

        if ($k == $sort) {
            $d = $dir == 'asc' ? 'desc' : 'asc';
            $c = $dir;
        }

        echo "<th><a href='?sort=$k&dir=$d&$href' class='$c'>$v</a></th>";
    }
}

// Generate <input type='text'>
function html_text_new($key, $label, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<div class='form-group $key'>";
    echo "<label for='$key'>$label</label>";
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<small class='error-text'>$_err[$key]</small>";
    }
    echo "</div>";
}

// Generate <input type='text'>
function html_password_new($key, $label, $attr = '')
{
    $value = encode($GLOBALS[$key] ?? '');
    echo "<div class='form-group $key'>";
    echo "<label for='$key'>$label</label>";
    echo "<input type='password' id='$key' name='$key' value='$value' $attr>";
    echo "<i id='pass-toggle-btn' class='fa-solid fa-eye-slash'></i>";
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<small class='error-text'>$_err[$key]</small>";
    }
    echo "</div>";
}

function html_file_new($key, $label, $accept = '', $attr = '') {
    $f = get_file($key);
    global $_err; // Assuming $f contains the file information

    echo "<div class='form-group $key'>";
    echo "<label class='file' tabindex='1' for='$key'>$label</label>";
    
    echo "<div class='file-uploader'>";
    echo "<ul class='file-list'>";

    // If $f is provided and contains valid image information
    if ($f && isset($f->tmp_name) && file_exists($f->tmp_name)) {
        // Get the file info
        $fileName = $f->name;
        $fileSize = $f->size;
        $fileSizeFormatted = $fileSize >= 1024 * 1024 
            ? number_format($fileSize / (1024 * 1024), 2) . " MB"
            : number_format($fileSize / 1024, 2) . " KB";
        $fileType = mime_content_type($f->tmp_name);
        $isImage = strpos($fileType, 'image/') === 0;
        $uniqueIdentifier = time(); // Unique identifier based on timestamp

        // Generate HTML for the uploaded file with image preview
        echo "
            <li class='file-item' id='file-item-$uniqueIdentifier'>
                <div class='file-extension'>";

        if ($isImage) {
            // Display the image using the temporary file
            $imgSrc = 'data:' . $fileType . ';base64,' . base64_encode(file_get_contents($f->tmp_name));
            echo "<img src='$imgSrc' alt='Image preview' class='image-preview' style='max-width: 100%; max-height: 100%;' />";
        } else {
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            echo "$fileExtension";
        }

        echo "
                </div>
                <div class='file-content-wrapper'>
                    <div class='file-content'>
                        <div class='file-details'>
                            <h5 class='file-name'>$fileName</h5>
                            <div class='file-info'>
                                <small class='file-size'>$fileSizeFormatted</small>
                            </div>
                        </div>
                        <button class='delete-button'>🗑️</button>
                    </div>
                </div>
            </li>";
    }

    echo "</ul>";
    
    // File upload box with drag and drop or browse functionality
    echo "<div class='file-upload-box'>";
    echo "<h2 class='box-title'>";
    echo "<span class='file-instruction'>Drag files here or </span>";
    echo "<span class='file-browse-button'>browse</span>";
    echo "</h2>";
    echo "<input class='file-browse-input' type='file' id='$key' name='$key' multiple accept='$accept' hidden $attr>";
    echo "</div>";
    echo "</div>";

    // Display error if there's any
    if ($_err[$key] ?? false) {
        echo "<small class='error-text'>$_err[$key]</small>";
    }
    
    echo "</div>";
}

// ============================================================================
// Error Handlings
// ============================================================================

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

// ============================================================================
// Security
// ============================================================================

// Global user object
$_user = $_SESSION['user'] ?? null;

// Login user
function login($user, $url = '/')
{
    $_SESSION['user'] = $user;
    redirect($url);
}

// Logout user
function logout($url = '/')
{
    unset($_SESSION['user']);
    redirect($url);
}

// Authorization
function auth(...$roles)
{
    if ($_SESSION['user']) {
        if ($roles) {
            if (in_array($_SESSION['user']['role'], $roles)) {
                return; // OK
            }
        } else {
            return; // OK
        }
    }

    temp_new('warning', 'You dont have permission to access Admin Page.');
    redirect('/index.php');
}

function is_strong_password($password)
{
    return strlen($password) >= 8 && // Minimum 8 characters
        preg_match('/[A-Z]/', $password) && // Contain uppercase
        preg_match('/[a-z]/', $password) && // Contain lowercase
        preg_match('/[0-9]/', $password) && // Contain digit
        preg_match('/[_\W]/', $password); // Contain symbol
}

// ============================================================================
// Email Functions
// ============================================================================

// Demo Accounts:
// --------------
// AACS3173@gmail.com           npsg gzfd pnio aylm
// BAIT2173.email@gmail.com     ytwo bbon lrvw wclr
// liaw.casual@gmail.com        wtpa kjxr dfcb xkhg
// liawcv1@gmail.com            obyj shnv prpa kzvj

// Initialize and return mail object
function get_mail()
{
    require_once 'lib/PHPMailer.php';
    require_once 'lib/SMTP.php';

    $m = new PHPMailer(true);
    $m->isSMTP();
    $m->SMTPAuth = true;
    $m->Host = 'smtp.gmail.com';
    $m->Port = 587;
    $m->Username = 'AACS3173@gmail.com';
    $m->Password = 'npsg gzfd pnio aylm';
    $m->CharSet = 'utf-8';
    $m->setFrom($m->Username, '😺 Lozodo');

    return $m;
}

// ============================================================================
// Shopping Cart
// ============================================================================

// Get shopping cart
function get_cart()
{
    // TODO
    return $_SESSION['cart'] ?? [];
}

// Set shopping cart
function set_cart($cart = [])
{
    // TODO
    $_SESSION['cart'] = $cart;
}

// Update shopping cart
function update_cart($id, $unit)
{
    // TODO
    $cart = get_cart();

    if (is_exists($id, 'product', 'id')) {
        $cart[$id] = $unit;
        ksort($cart);
    } else {
        unset($cart[$id]);
    }

    set_cart($cart);
}

// ============================================================================
// Database Setups and Functions
// ============================================================================

// Global PDO object
$_db = new PDO('mysql:dbname=lozodo', 'root', '', [
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

// ============================================================================
// Global Constants and Variables
// ============================================================================

// Range 1-10
// TODO
$_units = array_combine(range(1, 10), range(1, 10));
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
