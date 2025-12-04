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
$id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

$sql = "SELECT * FROM Access_Hours";
$resultAC = $mysqli->query($sql);
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
            <th>Access Hours Time</th>
            <th>Access Hours Start</th>
            <th>Access Hours End</th>
            <th>Access Hours Duration</th>
            <th>Access Hours Cost</th>
            <th>Used by Customer</th>
            <th>Handled by Cashier</th>
        </tr>

        <?php
        while ($row = $resultAC->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['ACC_TIME']; ?></td>
                <td><?php echo $row['ACC_START']; ?></td>
                <td><?php echo $row['ACC_END']; ?></td>
                <td><?php echo $row['ACC_DURATION']; ?></td>
                <td><?php echo $row['ACC_COST']; ?></td>
                <td><?php echo $row['CUS_ID']; ?></td>
                <td><?php echo $row['CSHR_ID']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
    <form action="admin_access_hours.php" method="post">
        <?php
        if (isset($_POST['Add'])) {
        ?>
            <Button type="submit" name="Add">Add Access Hours</button>
        <?php
        }
        ?>
        <?php
        if (isset($_POST['Remove'])) {
        ?>
            <Button type="submit" name="Remove">Remove Access Hours</button>
        <?php
        }
        ?>
    </form>
    <br><br>
</body>

</html>