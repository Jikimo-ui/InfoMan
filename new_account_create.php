<?php
session_start();

$user = 'root';
$password = '123456';
$database = 'InternetCafe';
$servername = 'localhost:3310';

$mysqli = new mysqli($servername, $user, $password, $database);
if ($mysqli->connect_error) {
    die('Connect Error(' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
}

// Helper: get and trim POST value
function post($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

// Read values
$cus_id = post('CUS_ID');
$username = post('CUS_USER');
$password_plain = post('CUS_PASS');
$fname = post('CUS_FNAME');
$lname = post('CUS_LNAME');
$membership = post('CUS_MEMBERSHIP_STAT');
$pc_id = post('PC_ID');

$errors = [];

//Duplicate checks
$sql = "SELECT CUS_ID FROM InternetCafe.Customer WHERE CUS_ID ='$cus_id'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $errors[] = 'Customer ID already exists.';
}
$result->close();


$sql = "SELECT CUS_USER FROM InternetCafe.Customer WHERE CUS_USER='$username'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $errors[] = 'Username already exists.';
}
$result->close();

// Basic validation
if (empty($cus_id)) {
    $errors[] = 'Customer ID is required.';
} elseif (ctype_digit($cus_id)) {
    $errors[] = 'Customer ID cannot be only numbers. Must start with C.';
} elseif (!preg_match('/^C([0-9]{1,5})$/', $cus_id)) {
    $errors[] = 'Customer ID must follow format C0 to C99999.';
}

if (empty(trim($username))) $errors[] = 'Username is required.';
if (empty(trim($password_plain))) $errors[] = 'Password is required.';
if (empty(trim($fname)) || empty(trim($lname))) $errors[] = 'Full name is required.';
if (empty(trim($membership))) $errors[] = 'Membership required.';
if (empty(trim($pc_id))) $errors[] = 'Assigned PC ID required.';

if (count($errors) > 0) {
    $_SESSION['errors_new_account'] = $errors;
    header("Location: new_account.php");
    exit();
}

$sql = "INSERT INTO InternetCafe.Customer (CUS_ID, CUS_USER, CUS_PASS, CUS_FNAME, CUS_LNAME, CUS_MEMBERSHIP_STAT, PC_ID)
VALUES ('$cus_id', '$username', '$password_plain', '$fname', '$lname', '$membership', '$pc_id')";

if(mysqli_query($mysqli, $sql)){
    $_SESSION['username'] = $username;
    $_SESSION['id'] = $cus_id;
    header('Location: end_user.php');
    exit();
}else {
    $_SESSION['message_end_user'] = 'An error has occurred: ' . $mysqli->error;
    header('Location: new_account.php');
    exit();
}

?>
