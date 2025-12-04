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
    if (isset($_SESSION['errors_admin_access_hours']) && !empty($_SESSION['errors_admin_access_hours'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_admin_access_hours'] as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        echo '</div>';
        unset($_SESSION['errors_admin_access_hours']);
    }

    if (isset($_SESSION['message_admin_access_hours'])) {
        echo '<div class="error">' . htmlspecialchars($_SESSION['message_admin_access_hours']) . '</div>';
        unset($_SESSION['message_admin_access_hours']);
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

        // Handle Add Access Hours
        if (isset($_POST['AddFinal'])) {
            $acc_time    = $_POST['ACC_TIME'];
            $acc_start   = $_POST['ACC_START'];
            $acc_end     = $_POST['ACC_END'];
            $acc_duration = $_POST['ACC_DURATION'];
            $acc_cost    = $_POST['ACC_COST'];
            $cus_id      = $_POST['CUS_ID'];
            $cashier_id  = $_POST['CSHR_ID'];

            $errors = [];

            if (empty($acc_time)) {
                $errors[] = "Access Time is required.";
            }
            if (empty($acc_start)) {
                $errors[] = "Start Time is required.";
            }
            if (empty($acc_end)) {
                $errors[] = "End Time is required.";
            }
            if (empty($acc_duration)) {
                $errors[] = "Duration is required.";
            }
            if (empty($acc_cost)) {
                $errors[] = "Cost is required.";
            }

            if (count($errors) > 0) {
                $_SESSION['errors_admin_access_hours'] = $errors;
                header("Location: admin_access_hours.php");
                exit();
            }

            $sql = "INSERT INTO Access_Hours 
                        (ACC_TIME, ACC_START, ACC_END, ACC_DURATION, ACC_COST, CUS_ID, CSHR_ID)
                        VALUES ('$acc_time', '$acc_start', '$acc_end', '$acc_duration', '$acc_cost', '$cus_id', '$cashier_id')";

            if ($mysqli->query($sql)) {
                $_SESSION['message_admin_access_hours'] = "Access Hours record added successfully.";
                header("Location: admin_view.php");
                exit();
            } else {
                $_SESSION['errors_admin_access_hours'] = "Error: " . $mysqli->error;
                header("Location: admin_access_hours.php");
                exit();
            }
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

        // Handle Remove Access Hours
        if (isset($_POST['RemoveFinal'])) {
            $acc_time = $_POST['ACC_TIME'];

            $errors = [];

            // Validation
            if (empty($acc_time)) {
                $errors[] = "Access Time is required to remove a record.";
            }

            // If validation errors exist
            if (count($errors) > 0) {
                $_SESSION['errors_admin_access_hours'] = $errors;
                header("Location: admin_access_hours.php");
                exit();
            }

            // Try to delete
            $sql = "DELETE FROM Access_Hours WHERE ACC_TIME = '$acc_time'";
            if ($mysqli->query($sql)) {
                if ($mysqli->affected_rows > 0) {
                    $_SESSION['message_admin_access_hours'] = "Access Hours record removed successfully.";
                    header("Location: admin_view.php");
                    exit();
                } else {
                    $_SESSION['errors_admin_access_hours'] = ["No record found with that Access Time."];
                    header("Location: admin_access_hours.php");
                    exit();
                }
            } else {
                $_SESSION['errors_admin_access_hours'] = ["Error removing record: " . $mysqli->error];
                header("Location: admin_access_hours.php");
                exit();
            }
        }
        ?>

    </form>
</body>

</html>