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

$sql = "SELECT * FROM Technician";
$resultTECH = $mysqli->query($sql);

if (!$resultTECH) {
    die("Error fetching technicians: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Technician Management</title>
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
    <h2>Technician Management</h2>
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

    <?php
    if (isset($_SESSION['errors_admin_technician']) && !empty($_SESSION['errors_admin_technician'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_admin_technician'] as $error) {
            echo '<br>' . htmlspecialchars($error);
        }
        echo '</div>';
        unset($_SESSION['errors_admin_technician']);
    }

    if (isset($_SESSION['message_admin_technician'])) {
        echo '<div class="error">' . htmlspecialchars($_SESSION['message_admin_technician']) . '</div>';
        unset($_SESSION['message_admin_technician']);
    }
    ?>

    <?php
    // SHOW ADD TECHNICIAN FORM
    if (isset($_POST['Add']) || isset($_GET['action']) === 'add') {
    ?>
        <h3>Add Technician</h3>


        <form method="post">
            Technician ID (TECH_ID): <input type="text" name="TECH_ID" value="<?php echo isset($_POST['TECH_ID']) ? htmlspecialchars($_POST['TECH_ID']) : ''; ?>" required><br><br>
            First Name: <input type="text" name="TECH_FNAME" value="<?php echo isset($_POST['TECH_FNAME']) ? htmlspecialchars($_POST['TECH_FNAME']) : ''; ?>"><br><br>
            Last Name: <input type="text" name="TECH_LNAME" value="<?php echo isset($_POST['TECH_LNAME']) ? htmlspecialchars($_POST['TECH_LNAME']) : ''; ?>"><br><br>
            Shift: <input type="datetime-local" name="TECH_SHIFT" value="<?php echo htmlspecialchars($old_shift ? date('Y-m-d\TH:i', strtotime($old_shift)) : ''); ?>"><br><br>
            Salary: <input type="number" name="TECH_SALARY" value="<?php echo isset($_POST['TECH_SALARY']) ? htmlspecialchars($_POST['TECH_SALARY']) : ''; ?>"><br><br>
            Specialization: <input type="text" name="TECH_SPECIALIZATION" value="<?php echo isset($_POST['TECH_SPECIALIZATION']) ? htmlspecialchars($_POST['TECH_SPECIALIZATION']) : ''; ?>"><br><br>

            <button type="submit" name="AddSubmit">Submit</button>
        </form>
    <?php
    }

    //PROCESS ADD TECHNICIAN
    if (isset($_POST['AddSubmit'])) {

        $tech_id = $_POST['TECH_ID'];
        $fname = $_POST['TECH_FNAME'];
        $lname = $_POST['TECH_LNAME'];
        $shift = $_POST['TECH_SHIFT'];
        $salary = $_POST['TECH_SALARY'];
        $spec = $_POST['TECH_SPECIALIZATION'];

        $errors = [];

        // Duplicate TECH_ID
        $check = $mysqli->query("SELECT TECH_ID FROM Technician WHERE TECH_ID='$tech_id'");
        if ($check->num_rows > 0) {
            $errors[] = "Technician ID already exists.";
        }
        $check->close();

        // Basic Validation
        if (empty($tech_id)) $errors[] = "Technician ID is required.";
        elseif (!preg_match('/^T[0-9]{1,5}$/', $tech_id)) $errors[] = "TECH_ID must follow format T0-T99999.";

        if (empty(trim($fname))) $errors[] = "First name is required.";
        if (empty(trim($lname))) $errors[] = "Last name is required.";
        if (empty(trim($shift))) $errors[] = "Shift is required.";
        if (empty(trim($salary))) $errors[] = "Salary is required.";

        if (!empty($errors)) {
            $_SESSION['errors_admin_technician'] = $errors;
            // redirect back to form
            header("Location: admin_technician.php?action=add");
            exit();
        }

        // Insert technician
        $sql = "INSERT INTO Technician 
            (TECH_ID, TECH_FNAME, TECH_LNAME, TECH_SHIFT, TECH_SALARY, TECH_SPECIALIZATION)
            VALUES ('$tech_id', '$fname', '$lname', '$shift', '$salary', '$spec')";

        if ($mysqli->query($sql)) {
            $_SESSION['message_admin_view'] = "Technician added successfully!";
            header("Location: admin_view.php");
            exit();
        } else {
            $_SESSION['errors_admin_technician'] = ["Error updating technician: " . $mysqli->error];
            header("Location: admin_technician.php?action=add");
            exit();
        }
    }

    // SHOW UPDATE TECHNICIAN FORM
    if (isset($_POST['Update']) || isset($_GET['action']) === 'update') {
    ?>
        <h3>Update Technician Details</h3>
        <form method="post">
            Select Technician ID (TECH_ID):
            <select name="TECH_ID" required>
                <option value="">--Select Technician--</option>
                <?php
                $result = $mysqli->query("SELECT TECH_ID FROM Technician");
                while ($comp = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($comp['TECH_ID']) . '">'
                        . htmlspecialchars($comp['TECH_ID']) . '</option>';
                }
                ?>
            </select><br><br>
            First Name: <input type="text" name="TECH_FNAME" value="<?php echo isset($_POST['TECH_FNAME']) ? htmlspecialchars($_POST['TECH_FNAME']) : ''; ?>"><br><br>
            Last Name: <input type="text" name="TECH_LNAME" value="<?php echo isset($_POST['TECH_LNAME']) ? htmlspecialchars($_POST['TECH_LNAME']) : ''; ?>"><br><br>
            Shift: <input type="text" name="TECH_SHIFT" value="<?php echo isset($_POST['TECH_SHIFT']) ? htmlspecialchars($_POST['TECH_SHIFT']) : ''; ?>"><br><br>
            Salary: <input type="number" name="TECH_SALARY" value="<?php echo isset($_POST['TECH_SALARY']) ? htmlspecialchars($_POST['TECH_SALARY']) : ''; ?>"><br><br>
            Specialization: <input type="text" name="TECH_SPECIALIZATION" value="<?php echo isset($_POST['TECH_SPECIALIZATION']) ? htmlspecialchars($_POST['TECH_SPECIALIZATION']) : ''; ?>"><br><br>

            <button type="submit" name="UpdateSubmit">Submit</button>
        </form>
    <?php
    }

    // PROCESS UPDATE TECHNICIAN WITH EMPTY-FIELD HANDLING
    if (isset($_POST['UpdateSubmit'])) {

        $id = $_POST['TECH_ID'];
        $errors = [];

        // Validate TECH_ID format
        if (empty($id)) {
            $errors[] = "Technician ID is required.";
        } elseif (!preg_match('/^T[0-9]{1,5}$/', $id)) {
            $errors[] = "TECH_ID must follow format T0-T99999.";
        }

        // Check if technician exists
        if (empty($errors)) {
            $check = $mysqli->query("SELECT * FROM Technician WHERE TECH_ID='$id'");
            if ($check->num_rows === 0) {
                $errors[] = "Technician with ID '$id' does not exist.";
            }
            $existing = $check->fetch_assoc();
            $check->close();
        }

        // Get new values (keep old if blank)
        $fname = isset($_POST['TECH_FNAME']) && $_POST['TECH_FNAME'] !== "" ? $_POST['TECH_FNAME'] : $existing['TECH_FNAME'];
        $lname = isset($_POST['TECH_LNAME']) && $_POST['TECH_LNAME'] !== "" ? $_POST['TECH_LNAME'] : $existing['TECH_LNAME'];
        $shift = isset($_POST['TECH_SHIFT']) && $_POST['TECH_SHIFT'] !== "" ? $_POST['TECH_SHIFT'] : $existing['TECH_SHIFT'];
        $salary = isset($_POST['TECH_SALARY']) && $_POST['TECH_SALARY'] !== "" ? $_POST['TECH_SALARY'] : $existing['TECH_SALARY'];
        $spec = isset($_POST['TECH_SPECIALIZATION']) && $_POST['TECH_SPECIALIZATION'] !== "" ? $_POST['TECH_SPECIALIZATION'] : $existing['TECH_SPECIALIZATION'];

        // Additional field validation
        if (empty(trim($fname))) $errors[] = "First name cannot be empty.";
        if (empty(trim($lname))) $errors[] = "Last name cannot be empty.";
        if (empty(trim($shift))) $errors[] = "Shift cannot be empty.";
        if (!is_numeric($salary)) $errors[] = "Salary must be a numeric value.";
        if (empty(trim($spec))) $errors[] = "Specialization cannot be empty.";

        // If there are errors, redirect with messages
        if (!empty($errors)) {
            $_SESSION['errors_admin_technician'] = $errors;
            header("Location: admin_technician.php?action=update");
            exit();
        }

        // Perform update
        $sql = "UPDATE Technician 
            SET TECH_FNAME='$fname',
                TECH_LNAME='$lname',
                TECH_SHIFT='$shift',
                TECH_SALARY='$salary',
                TECH_SPECIALIZATION='$spec'
            WHERE TECH_ID='$id'";

        if ($mysqli->query($sql)) {
            $_SESSION['message_admin_technician'] = "Technician updated successfully! (Blank fields kept original values)";
            header("Location: admin_view.php");
            exit();
        } else {
            $_SESSION['errors_admin_technician'] = ["Error updating technician: " . $mysqli->error];
            header("Location: admin_technician.php?action=update");
            exit();
        }
    }
    ?>

    <?php
    // SHOW REMOVE TECHNICIAN FORM
    if (isset($_POST['Remove']) || isset($_GET['action']) === 'remove') {
    ?>
        <h3>Remove Technician</h3>
        <form method="post">
            Technician ID (TECH_ID):
            <select name="TECH_ID" required>
                <option value="">--Select Technician--</option>
                <?php
                $result = $mysqli->query("SELECT TECH_ID FROM Technician");
                while ($comp = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($comp['TECH_ID']) . '">'
                        . htmlspecialchars($comp['TECH_ID']) . '</option>';
                }
                ?>
            </select><br><br>
            <button type="submit" name="RemoveSubmit">Remove Technician</button>
        </form>
    <?php
    }

    // PROCESS REMOVE TECHNICIAN
    if (isset($_POST['RemoveSubmit'])) {

        $id = $_POST['TECH_ID'];
        $errors = [];

        // Validate TECH_ID format
        if (empty($id)) {
            $errors[] = "Technician ID is required.";
        } elseif (!preg_match('/^T[0-9]{1,5}$/', $id)) {
            $errors[] = "TECH_ID must follow format T0-T99999.";
        }

        // Check if technician exists
        if (empty($errors)) {
            $check = $mysqli->query("SELECT TECH_ID FROM Technician WHERE TECH_ID='$id'");
            if ($check->num_rows === 0) {
                $errors[] = "Technician with ID '$id' does not exist.";
            }
            $check->close();
        }

        if (!empty($errors)) {
            $_SESSION['errors_admin_technician'] = $errors;
            header("Location: admin_technician.php?action=remove");
            exit();
        }

        // Delete technician
        $sql = "DELETE FROM Technician WHERE TECH_ID='$id'";
        if ($mysqli->query($sql)) {
            $_SESSION['message_admin_view'] = "Technician removed successfully!";
            header("Location: admin_view.php");
            exit();
        } else {
            $_SESSION['errors_admin_technician'] = ["Error deleting technician: " . $mysqli->error];
            header("Location: admin_technician.php?action=remove");
            exit();
        }
    }
    ?>

    <br>
    <form action="admin_view.php" method="post">
        <button type=submit>Return to Admin View</button>
    </form>

</body>


</html>

