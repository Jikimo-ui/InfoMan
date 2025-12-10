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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$cus_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

$sql = "SELECT * FROM InternetCafe.Customer WHERE CUS_ID = '$cus_id'";
$resultCustomer = $mysqli->query($sql);

$sql = "SELECT PC_ID FROM InternetCafe.Computer";
$resultPC = $mysqli->query($sql);
//$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php
    if (isset($_SESSION['errors_end_user_delete']) && !empty($_SESSION['errors_end_user_delete'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_end_user_delete'] as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        echo '</div>';
        unset($_SESSION['errors_end_user_delete']);
    }

    if (isset($_SESSION['message_end_user_delete.php'])) {
        echo '<div class="error">' . htmlspecialchars($_SESSION['message_end_user_delete']) . '</div>';
        unset($_SESSION['message_end_user_delete']);
    }
    ?>
    
    <h2>Are you sure you want to delete your account?</h2>
    <p>Click cancel to go back</p>
    <form action="end_user_delete_process.php" method="post">
        <!-- Buttons -->
        <input type="submit" value="Delete Account">
    </form>

    <form action="end_user.php" method="post">
        <input type="submit" value="Cancel">
    </form>
</body>

</html>