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

$sql = "SELECT * FROM Transact";
$resultTransact = $mysqli->query($sql);
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
    if (isset($_SESSION['errors_admin_transact']) && !empty($_SESSION['errors_admin_transact'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_admin_transact'] as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        echo '</div>';
        unset($_SESSION['errors_admin_transact']);
    }

    if (isset($_SESSION['message_admin_transact'])) {
        echo '<div class="success">' . htmlspecialchars($_SESSION['message_admin_transact']) . '</div>';
        unset($_SESSION['message_admin_transact']);
    }
    ?>

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
        <?php while ($row = $resultTransact->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['TRNSC_ID']; ?></td>
                <td><?php echo $row['TRNSC_MOP']; ?></td>
                <td><?php echo $row['TRNSC_TIME']; ?></td>
                <td><?php echo $row['TRNSC_DATE']; ?></td>
                <td><?php echo $row['TRNSC_AMOUNT']; ?></td>
                <td><?php echo $row['CUS_ID']; ?></td>
                <td><?php echo $row['CSHR_ID']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>

    <form action="admin_transact.php" method="post">
        <?php
        // Show Add Transaction form if Add button clicked
        if (isset($_POST['Add'])) {
        ?>
            <h3>Add Transaction</h3>

            <label>Mode of Payment:</label>

<!-- ADD FIELD FOR TRNSC ID XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX-->
		
            <select name="TRNSC_MOP" required>
                <option value="">--Select--</option>
                <option value="CASH">CASH</option>
                <option value="CASHLESS">CASHLESS</option>
            </select><br><br>

            <label>Transaction Time:</label>
            <input type="time" name="TRNSC_TIME" required><br><br>

            <label>Transaction Date:</label>
            <input type="date" name="TRNSC_DATE" required><br><br>

            <label>Amount:</label>
            <input type="number" name="TRNSC_AMOUNT" step="0.01" min="0" required><br><br>

            <label>Customer ID:</label>
            <input type="text" name="CUS_ID"><br>
            <br>

            <label>Cashier ID:</label>
            <input type="text" name="CSHR_ID"><br>
           

            <button type="submit" name="AddFinal">Add Transaction</button>
        <?php
        } else {
        ?>
            <button type="submit" name="Add">Add Transaction</button>
        <?php
        }
        ?>

        <?php
        // Handle AddFinal submission
        if (isset($_POST['AddFinal'])) {
            $mop    = $_POST['TRNSC_MOP'];
            $time   = $_POST['TRNSC_TIME'];
            $date   = $_POST['TRNSC_DATE'];
            $amount = $_POST['TRNSC_AMOUNT'];
            $cus_id = $_POST['CUS_ID'] ?: NULL;
            $cash_id = $_POST['CSHR_ID'] ?: NULL;

            $errors = [];

	// CHECK ERRORS FOR ALL IDS HERE XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXx
			
            if (empty($mop)) $errors[] = "Mode of Payment is required.";
            if (empty($time)) $errors[] = "Time is required.";
            if (empty($date)) $errors[] = "Date is required.";
            if (empty($amount)) $errors[] = "Amount is required.";

            if (count($errors) > 0) {
                $_SESSION['errors_admin_transact'] = $errors;
                header("Location: admin_transact.php");
                exit();
            }

            // Auto-generate TRNSC_ID
            $res = $mysqli->query("SELECT TRNSC_ID FROM Transact ORDER BY TRNSC_ID DESC LIMIT 1");
            $last_id = $res->fetch_assoc()['TRNSC_ID'] ?? 'TR000';
            $num = (int)substr($last_id, 2) + 1;
            $new_id = 'TR' . str_pad($num, 3, '0', STR_PAD_LEFT);

            $sql = "INSERT INTO Transact (TRNSC_ID, TRNSC_MOP, TRNSC_TIME, TRNSC_DATE, TRNSC_AMOUNT, CUS_ID, CSHR_ID)
                    VALUES ('$new_id','$mop','$time','$date','$amount','$cus_id','$cash_id')";

            if ($mysqli->query($sql)) {
                $_SESSION['message_admin_transact'] = "Transaction added successfully.";
                header("Location: admin_transact.php");
                exit();
            } else {
                $_SESSION['errors_admin_transact'] = ["Error: " . $mysqli->error];
                header("Location: admin_transact.php");
                exit();
            }
        }
        ?>
    </form>

</body>
</html>

