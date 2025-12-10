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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$cus_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
//$mysqli->close();

$sql = "DELETE FROM InternetCafe.Customer WHERE CUS_ID = '$cus_id'";
$result = $mysqli->query($sql);

session_unset();
session_destroy();

session_start();
$_SESSION['message_login'] = "Customer record deleted successfully.";
header('Location: login.php');

?>
