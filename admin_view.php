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

$sql = "SELECT * FROM Technician";
$resultTECH = $mysqli->query($sql);

$sql = "SELECT * FROM Cashier";
$resultCSHR = $mysqli->query($sql);

$sql = "SELECT * FROM Computer";
$resultPC = $mysqli->query($sql);

$sql = "SELECT * FROM Transact";
$resultTRNSC = $mysqli->query($sql);

$sql = "SELECT * FROM Access_Hours";
$resultACC = $mysqli->query($sql);
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
            <th>Technician ID</th>
            <th>Technician Name</th>
            <th>Technician Shift</th>
            <th>Technician Salary</th>
            <th>Technician Specialization</th>
        </tr>

        <?php
        while ($row = $resultTECH->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['TECH_ID']; ?></td>
                <td><?php echo $row['TECH_FNAME'] . " " . $row['TECH_LNAME']; ?></td>
                <td><?php echo $row['TECH_SHIFT']; ?></td>
                <td><?php echo $row['TECH_SALARY']; ?></td>
                <td><?php echo $row['TECH_SPECIALIZATION']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
    <form action="admin_technician.php" method="post">
        <Button type="submit" name="Add">Add Technician</button>
        <Button type="submit" name="Update">Update Technician Details</button>
        <Button type="submit" name="Remove">Remove Technician</button>
    </form>
    <br><br>

    <table>
        <tr>
            <th>Cashier ID</th>
            <th>Cashier Name</th>
            <th>Cashier Shift</th>
            <th>Cashier Salary</th>
        </tr>

        <?php
        while ($row = $resultCSHR->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['CSHR_ID']; ?></td>
                <td><?php echo $row['CSHR_FNAME'] . " " . $row['CSHR_LNAME']; ?></td>
                <td><?php echo $row['CSHR_SHIFT']; ?></td>
                <td><?php echo $row['CSHR_SALARY']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
    <form action="admin_cashier.php" method="post">
        <Button type="submit" name="Add">Add Cashier</button>
        <Button type="submit" name="Update">Update Cashier Details</button>
        <Button type="submit" name="Remove">Remove Cashier</button>
    </form>
    <br><br>

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
        while ($row = $resultPC->fetch_assoc()) {
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
    <form action="admin_pc.php" method="post">
        <Button type="submit" name="Add">Add PC</button>
        <Button type="submit" name="Update">Update PC Details</button>
        <Button type="submit" name="Remove">Remove PC</button>
    </form>
    <br><br>

    <table>
        <tr>
            <th>Transaction ID</th>
            <th>Transaction Mode of Payment</th>
            <th>Transaction Time</th>
            <th>Transaction Date</th>
            <th>Transaction Amount</th>
            <th>Purchased by Customer</th>
            <th>Handled by Cashier</th>
        </tr>

        <?php
        while ($row = $resultTRNSC->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['TRNSC_ID']; ?></td>
                <td><?php echo $row['TRNSC_MOP']; ?></td>
                <td><?php echo $row['TRNSC_TIME']; ?></td>
                <td><?php echo $row['TRNSC_DATE']; ?></td>
                <td><?php echo $row['TRNSC_AMOUNT']; ?></td>
                <td><?php echo $row['CUS_ID']; ?></td>
                <td><?php echo $row['CSHR_ID']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <br>
    <form action="admin_transaction.php" method="post">
        <Button type="submit" name="Add">Add Transaction</button>
    </form>
    <br><br>

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
        <Button type="submit" name="Add">Add Access Hours</button>
        <Button type="submit" name="Remove">Remove Access Hours</button>
    </form>
</body>

</html>