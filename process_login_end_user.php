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

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password_input = isset($_POST['password']) ? $_POST['password'] : '';

// Query database for matching customer
$sql = "SELECT CUS_ID, CUS_USER, CUS_PASS FROM InternetCafe.Customer WHERE CUS_USER = '$username' AND CUS_PASS = '$password_input'";
$result = $mysqli->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    // Correct login → store username and redirect to welcome page
    $_SESSION['username'] = $username;
    $_SESSION['id'] = $row['CUS_ID'];
    $mysqli->close();
    header("Location: end_user.php");
    exit();
}

// Wrong login → set error message and redirect back
$_SESSION['error'] = "Wrong username or password!";
$mysqli->close();
header("Location: login.php");
exit();

