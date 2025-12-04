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
$id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

$sql = "SELECT * FROM Access_Hours";
$resultACC = $mysqli->query($sql);
//$mysqli->close();

// Handle Add Access Hours
if (isset($_POST['AddFinal'])) {
    $acc_time    = $_POST['ACC_TIME'];
    $acc_start   = $_POST['ACC_START'];
    $acc_end     = $_POST['ACC_END'];
    $acc_duration= $_POST['ACC_DURATION'];
    $acc_cost    = $_POST['ACC_COST'];
    $cus_id      = $_POST['CUS_ID'];
    $cashier_id  = $_POST['CSHR_ID'];

    $sql = "INSERT INTO Access_Hours 
            (ACC_TIME, ACC_START, ACC_END, ACC_DURATION, ACC_COST, CUS_ID, CSHR_ID)
            VALUES ('$acc_time', '$acc_start', '$acc_end', '$acc_duration', '$acc_cost', '$cus_id', '$cashier_id')";

    if ($mysqli->query($sql) === TRUE) {
        $_SESSION['message_end_user'] = "Access Hours record added successfully.";
    } else {
        $_SESSION['errors_end_user'][] = "Error: " . $mysqli->error;
    }

    header("Location: admin_access_hours.php");
    exit();
}
?>

?>

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
        while ($row = $resultACC->fetch_assoc()) {
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
        // If Add button was clicked earlier
        if (isset($_POST['Add'])) {
        ?>
            <h3>Add Access Hours</h3>
            <label>Access Time:</label>
            <input type="datetime-local" name="ACC_TIME"><br>

            <label>Start Time:</label>
            <input type="datetime-local" name="ACC_START"><br>

            <label>End Time:</label>
            <input type="datetime-local" name="ACC_END"><br>

            <label>Duration:</label>
            <input type="time" name="ACC_DURATION"><br>

            <label>Cost:</label>
            <input type="number" step="0.01" name="ACC_COST"><br>

            <label>Customer ID:</label>
            <input type="text" name="CUS_ID"><br>

            <label>Cashier ID:</label>
            <input type="text" name="CSHR_ID"><br>

            <button type="submit" name="AddFinal">Add Access Hours</button>
        <?php
        }

        // If Remove button was clicked earlier
        if (isset($_POST['Remove'])) {
        ?>
            <h3>Remove Access Hours</h3>
            <label>Access Time:</label>
            <input type="datetime-local" name="ACC_TIME" required><br>

            <button type="submit" name="RemoveFinal">Remove Access Hours</button>
        <?php
        }
        ?>

    </form>
</body>

</html>