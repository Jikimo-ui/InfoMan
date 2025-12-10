<?php
session_start();

$user = 'root';
$password = 'D1dhen1102';
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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$cus_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

$sql = "SELECT * FROM InternetCafe.Customer WHERE CUS_ID = '$cus_id'";
$result = $mysqli->query($sql);

// Read values
$username = post('CUS_USER');
$password_plain = post('CUS_PASS');
$fname = post('CUS_FNAME');
$lname = post('CUS_LNAME');
$membership = post('CUS_MEMBERSHIP_STAT');
$pc_id = post('PC_ID');

$row = $result->fetch_assoc();

$store_username = $row['CUS_USER'];
$store_password = $row['CUS_PASS'];
$storefname = $row['CUS_FNAME'];
$store_lname = $row['CUS_LNAME'];
$store_membership = $row['CUS_MEMBERSHIP_STAT'];
$store_pc_id = $row['PC_ID'];



//check if its not null
if(!empty(trim($username))){
    $store_username = $username;
}
if(!empty(trim($password_plain))){
    $store_password = $password_plain;
}
if(!empty(trim($fname))){
    $storefname = $fname;
}
if(!empty(trim($lname))){
    $store_lname = $lname;
}
if(!empty(trim($membership))){
    $store_membership = $membership;
}
if(!empty(trim($pc_id))){
    $store_pc_id = $pc_id;
}

// Build the UPDATE query
$sql = "UPDATE InternetCafe.Customer 
        SET 
            CUS_USER = '$store_username',
            CUS_PASS = '$store_password',
            CUS_FNAME = '$storefname',
            CUS_LNAME = '$store_lname',
            CUS_MEMBERSHIP_STAT = '$store_membership',
            PC_ID = '$store_pc_id'
        WHERE CUS_ID = '$cus_id'";

// Execute the query
if ($mysqli->query($sql)) {
    $_SESSION['message_end_user'] = "Customer record updated successfully.";
    $_SESSION['username'] = $username;
    header("Location: end_user.php");
    exit();
} else {
    $_SESSION['error_end_user_update'] = "Update failed: " . $mysqli->error;
    header("Location: end_user_update.php");
    exit();
}
?>