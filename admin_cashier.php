<?php
session_start();

$user = 'root';
$password = '12345678';
$database = 'InternetCafe';
$servername = 'localhost:3310';

$mysqli = new mysqli($servername, $user, $password, $database);
if ($mysqli->connect_error) {
    die('Connect Error(' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
}

$username = $_SESSION['username'] ?? '';
$id = $_SESSION['id'] ?? '';

$resultCashier = $mysqli->query("SELECT * FROM Cashier");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Management</title>
    <style>
        table {
            border-collapse: collapse;
            width: 700px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

<?php

if (isset($_SESSION['errors_admin_cashier']) && !empty($_SESSION['errors_admin_cashier'])) {
    echo '<div class="error">';
    foreach ($_SESSION['errors_admin_cashier'] as $error) {
        echo htmlspecialchars($error) . '<br>';
    }
    echo '</div>';
    unset($_SESSION['errors_admin_cashier']);
}

if (isset($_SESSION['message_admin_cashier'])) {
    echo '<div class="success">' . htmlspecialchars($_SESSION['message_admin_cashier']) . '</div>';
    unset($_SESSION['message_admin_cashier']);
}
?>


<table>
    <tr>
        <th>Cashier ID</th>
        <th>Name</th>
        <th>Shift</th>
        <th>Salary</th>
    </tr>
    <?php while ($row = $resultCashier->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['CSHR_ID']; ?></td>
            <td><?php echo $row['CSHR_FNAME'] . " " . $row['CSHR_LNAME']; ?></td>
            <td><?php echo $row['CSHR_SHIFT']; ?></td>
            <td><?php echo $row['CSHR_SALARY']; ?></td>
        </tr>
    <?php } ?>
</table>
<br>

<form action="admin_cashier.php" method="post">
    <?php
    
    if (isset($_POST['Add']) || isset($_GET['action']) === 'add') {
        $old_id = $_POST['CSHR_ID'] ?? '';
        $old_fname = $_POST['CSHR_FNAME'] ?? '';
        $old_lname = $_POST['CSHR_LNAME'] ?? '';
        $old_shift = $_POST['CSHR_SHIFT'] ?? '';
        $old_salary = $_POST['CSHR_SALARY'] ?? '';
    ?>
        <h3>Add Cashier</h3>
        <label>Cashier ID:</label>
        <input type="text" name="CSHR_ID" value="<?php echo htmlspecialchars($old_id); ?>" required><br><br>

        <label>First Name:</label>
        <input type="text" name="CSHR_FNAME" value="<?php echo htmlspecialchars($old_fname); ?>"><br><br>

        <label>Last Name:</label>
        <input type="text" name="CSHR_LNAME" value="<?php echo htmlspecialchars($old_lname); ?>"><br><br>


        <label>Shift:</label>
		<input type="datetime-local" name="CSHR_SHIFT" 
		value="<?php echo htmlspecialchars($old_shift ? date('Y-m-d\TH:i', strtotime($old_shift)) : ''); ?>"><br><br>


        <label>Salary:</label>
        <input type="number" name="CSHR_SALARY" value="<?php echo htmlspecialchars($old_salary); ?>" step="0.01"><br><br>

        <button type="submit" name="AddSubmit">Add Cashier</button>
    <?php
    } else {
    ?>
        <button type="submit" name="Add">Add Cashier</button>
    <?php
    }
    ?>

    <?php
    
    if (isset($_POST['Update']) || isset($_GET['action']) === 'update') {
    ?>
        <h3>Update Cashier</h3>
        <label>Cashier ID:</label>
        <input type="text" name="CSHR_ID" required><br><br>

        <label>New First Name:</label>
        <input type="text" name="CSHR_FNAME"><br><br>

        <label>New Last Name:</label>
        <input type="text" name="CSHR_LNAME"><br><br>

        <label>New Shift:</label>
		<input type="datetime-local" name="CSHR_SHIFT" 
       value="<?php echo htmlspecialchars($existing['CSHR_SHIFT'] ? date('Y-m-d\TH:i', strtotime($existing['CSHR_SHIFT'])) : ''); ?>"><br><br>

        <label>New Salary:</label>
        <input type="number" name="CSHR_SALARY" step="0.01"><br><br>

        <button type="submit" name="UpdateSubmit">Update Cashier</button>
    <?php
    }
    ?>

    <?php
    // Show Remove form
    if (isset($_POST['Remove']) || (isset($_GET['action'])) === 'remove') {
    ?>
        <h3>Remove Cashier</h3>
        <label>Cashier ID:</label>
        <input type="text" name="CSHR_ID" required><br><br>
        <button type="submit" name="RemoveSubmit">Remove Cashier</button>
    <?php
    }
    ?>
</form>

<?php
// Handle AddSubmit
if (isset($_POST['AddSubmit'])) {
    $id = $_POST['CSHR_ID'];
    $fname = $_POST['CSHR_FNAME'];
    $lname = $_POST['CSHR_LNAME'];
    $shift = $_POST['CSHR_SHIFT'];
    $salary = $_POST['CSHR_SALARY'];

    $errors = [];
//ADD MORE ERRORS HERE XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxx
	
    if (empty($id)) $errors[] = "Cashier ID is required.";

    if ($errors) {
        $_SESSION['errors_admin_cashier'] = $errors;
        header("Location: admin_cashier.php?action=add");
        exit();
    }

    $sql = "INSERT INTO Cashier (CSHR_ID, CSHR_FNAME, CSHR_LNAME, CSHR_SHIFT, CSHR_SALARY)
            VALUES ('$id', '$fname', '$lname', '$shift', '$salary')";
    if ($mysqli->query($sql)) {
        $_SESSION['message_admin_cashier'] = "Cashier added successfully.";
    } else {
        $_SESSION['errors_admin_cashier'] = ["Error: " . $mysqli->error];
    }
    header("Location: admin_cashier.php");
    exit();
}

// Handle UpdateSubmit
if (isset($_POST['UpdateSubmit'])) {
    $id = $_POST['CSHR_ID'];
    $res = $mysqli->query("SELECT * FROM Cashier WHERE CSHR_ID='$id'");
    $existing = $res->fetch_assoc();

    if (!$existing) {
        $_SESSION['errors_admin_cashier'] = ["Cashier ID not found."];
        header("Location: admin_cashier.php?action=update");
        exit();
    }

    $fname = $_POST['CSHR_FNAME'] !== "" ? $_POST['CSHR_FNAME'] : $existing['CSHR_FNAME'];
    $lname = $_POST['CSHR_LNAME'] !== "" ? $_POST['CSHR_LNAME'] : $existing['CSHR_LNAME'];
    $shift = $_POST['CSHR_SHIFT'] !== "" ? $_POST['CSHR_SHIFT'] : $existing['CSHR_SHIFT'];
    $salary = $_POST['CSHR_SALARY'] !== "" ? $_POST['CSHR_SALARY'] : $existing['CSHR_SALARY'];

    $sql = "UPDATE Cashier SET
                CSHR_FNAME='$fname',
                CSHR_LNAME='$lname',
                CSHR_SHIFT='$shift',
                CSHR_SALARY='$salary'
            WHERE CSHR_ID='$id'";
    if ($mysqli->query($sql)) {
        $_SESSION['message_admin_cashier'] = "Cashier updated successfully.";
    } else {
        $_SESSION['errors_admin_cashier'] = ["Error: " . $mysqli->error];
    }
    header("Location: admin_cashier.php");
    exit();
}

// Handle RemoveSubmit
if (isset($_POST['RemoveSubmit'])) {
    $id = $_POST['CSHR_ID'];
    $sql = "DELETE FROM Cashier WHERE CSHR_ID='$id'";
    if ($mysqli->query($sql)) {
        $_SESSION['message_admin_cashier'] = "Cashier removed successfully.";
    } else {
        $_SESSION['errors_admin_cashier'] = ["Error: " . $mysqli->error];
    }
    header("Location: admin_cashier.php");
    exit();
}
?>

<br>
<form action="admin_view.php" method="post">
    <button type="submit">Return to Admin View</button>
</form>
</body>
</html>



