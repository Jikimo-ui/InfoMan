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
        echo '<div class="error">' . htmlspecialchars($_SESSION['message_admin_transact']) . '</div>';
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
        if (isset($_POST['Add']) || isset($_GET['action']) === 'add') {
        ?>
            <h3>Add Transaction</h3>
            Transaction ID : <input type="text" name="TRNSC_ID" required><br><br>

            <label>Mode of Payment:</label>
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
            <input type="text" name="TRNSC_AMOUNT"><br><br>

            <label>Customer ID:</label>
            <select name="CUS_ID" required>
                <option value="">--Select Customer--</option>
                <?php
                $result = $mysqli->query("SELECT CUS_ID FROM Customer");
                while ($comp = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($comp['CUS_ID']) . '">'
                        . htmlspecialchars($comp['CUS_ID']) . '</option>';
                }
                ?>
            </select><br><br>

            <label>Cashier ID:</label>
            <select name="CSHR_ID" required>
                <option value="">--Select Cashier--</option>
                <?php
                $result = $mysqli->query("SELECT CSHR_ID FROM Cashier");
                while ($comp = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($comp['CSHR_ID']) . '">'
                        . htmlspecialchars($comp['CSHR_ID']) . '</option>';
                }
                ?>
            </select><br><br>


            <br><button type="submit" name="AddFinal">Add Transaction</button>
        <?php
        } else {
        ?>
            <button type="submit" name="Add">Add Transaction</button><br>
        <?php
        }
        ?>

        <?php
        // Handle AddFinal submission
        if (isset($_POST['AddFinal'])) {
            $id = $_POST['TRNSC_ID'];
            $mop    = $_POST['TRNSC_MOP'];
            $time   = $_POST['TRNSC_TIME'];
            $date   = $_POST['TRNSC_DATE'];
            $amount = $_POST['TRNSC_AMOUNT'];
            $cus_id = $_POST['CUS_ID'] ?: NULL;
            $cash_id = $_POST['CSHR_ID'] ?: NULL;

            $errors = [];

            // CHECK ERRORS FOR ALL IDS HERE XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXx
            if (empty($id)) $errors[] = "Transaction ID is required.";
            elseif (!preg_match('/^TR[0-9]{1,4}$/', $id)) $errors[] = "Transaction ID must follow format TR0-TR9999.";

            if (empty($cus_id)) $errors[] = "Customer ID is required.";
            elseif (!preg_match('/^C[0-9]{1,5}$/', $cus_id)) $errors[] = "Customer ID must follow format C0-C99999.";

            if (empty($cash_id)) $errors[] = "Cashier ID is required.";
            elseif (!preg_match('/^CS[0-9]{1,4}$/', $cash_id)) $errors[] = "Cashier ID must follow format CS0-CS9999.";

            if (empty($mop)) $errors[] = "Mode of Payment is required.";
            if (empty($time)) $errors[] = "Time is required.";
            if (empty($date)) $errors[] = "Date is required.";
            if (!isset($_POST['TRNSC_AMOUNT']) || trim((string)$amount) === '') {
                $errors[] = "Amount is required.";
            } elseif (!is_numeric($amount) || floatval($amount) < 0) {
                $errors[] = "Amount must be a non-negative numeric value.";
            }

            if (count($errors) > 0) {
                $_SESSION['errors_admin_transact'] = $errors;
                header("Location: admin_transact.php?action=add");
                exit();
            }

            $sql = "INSERT INTO Transact (TRNSC_ID, TRNSC_MOP, TRNSC_TIME, TRNSC_DATE, TRNSC_AMOUNT, CUS_ID, CSHR_ID)
                    VALUES ('$id','$mop','$time','$date','$amount','$cus_id','$cash_id')";

            if ($mysqli->query($sql)) {
                $_SESSION['message_admin_view'] = "Transaction added successfully.";
                header("Location: admin_view.php");
                exit();
            } else {
                $_SESSION['errors_admin_transact'] = ["Error: " . $mysqli->error];
                header("Location: admin_transact.php?action=add");
                exit();
            }
        }
        ?>
        
    </form>
    
    <form action="admin_view.php" method="post">
        <br><button type=submit>Return to Admin View</button>
    </form>

</body>


</html>
