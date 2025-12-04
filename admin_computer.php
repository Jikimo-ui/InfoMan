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

$sql = "SELECT * FROM Computer";
$resultComputer = $mysqli->query($sql);
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
            <th>PC ID</th>
            <th>PC Number</th>
            <th>PC Status</th>
            <th>PC Specifications</th>
            <th>PC Location</th>
            <th>PC Last Maintenance</th>
            <th>PC Software</th>
            <th>Handled by Technician</th>
        </tr>

        <?php
        while ($row = $resultComputer->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['PC_ID']; ?></td>
                <td><?php echo $row['PC_NUMBER']; ?></td>
                <td><?php echo $row['PC_STATUS']; ?></td>
                <td><?php echo $row['PC_SPECS']; ?></td>
                <td><?php echo $row['PC_LOC']; ?></td>
                <td><?php echo $row['PC_LASTMAIN']; ?></td>
                <td><?php echo $row['PC_SOFTWARE']; ?></td>
                <td><?php echo $row['TECH_ID']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
    <form action="admin_computer.php" method="post">
        <?php
        if (isset($_POST['Add'])) {
        ?>
            <Button type="submit" name="Add">Add PC</button>
        <?php
        }
        ?>
        <?php
        if (isset($_POST['Update'])) {
        ?>
            <Button type="submit" name="Remove">Update PC Details</button>
        <?php
        }
        ?>
        <?php
        if (isset($_POST['Remove'])) {
        ?>
            <Button type="submit" name="Remove">Remove PC</button>
        <?php
        }
        ?>
    </form>
    <br><br>
</body>

</html>