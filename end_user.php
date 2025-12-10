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
$result = $mysqli->query($sql);
//$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <style>
        table {
            border-collapse: collapse;
            width: 500px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>

</head>

<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <?php
    if (isset($_SESSION['errors_end_user']) && !empty($_SESSION['errors_end_user'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_end_user'] as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        echo '</div>';
        unset($_SESSION['errors_end_user']);
    }

    if (isset($_SESSION['message_end_user'])) {
        echo '<div class="error">' . htmlspecialchars($_SESSION['message_end_user']) . '</div>';
        unset($_SESSION['message_end_user']);
    }
    ?>
    <table>
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Membership Status</th>
            <th>Assigned Computer</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['CUS_ID']; ?></td>
                <td><?php echo $row['CUS_FNAME'] . " " . $row['CUS_LNAME']; ?></td>
                <td><?php echo $row['CUS_MEMBERSHIP_STAT']; ?></td>
                <td><?php echo $row['PC_ID']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
    <button><a href="end_user_update.php">Update Personal Details</a></button>
    <button><a href="end_user_delete.php">Delete Account</a></button>
</body>

</html>