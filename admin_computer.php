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

// Fetch all computers
$sql = "SELECT * FROM Computer";
$resultCOMP = $mysqli->query($sql);
if (!$resultCOMP) {
    die("Error fetching computers: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Computer Management</title>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 700px;
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

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <h2>Computer Management</h2>

    <table>
        <tr>
            <th>PC ID</th>
            <th>PC Number</th>
            <th>Status</th>
            <th>Specs</th>
            <th>Location</th>
            <th>Last Maintenance</th>
            <th>Software</th>
            <th>Assigned Technician</th>
        </tr>
        <?php while ($row = $resultCOMP->fetch_assoc()) { ?>
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
        <?php } ?>
    </table>

    <br>

    <?php
    // Show errors/messages
    if (isset($_SESSION['errors_admin_computer']) && !empty($_SESSION['errors_admin_computer'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_admin_computer'] as $error) {
            echo '<br>' . htmlspecialchars($error);
        }
        echo '</div>';
        unset($_SESSION['errors_admin_computer']);
    }

    if (isset($_SESSION['message_admin_computer'])) {
        echo '<div class="success">' . htmlspecialchars($_SESSION['message_admin_computer']) . '</div>';
        unset($_SESSION['message_admin_computer']);
    }
    ?>

    <!-- ADD COMPUTER FORM -->
    <?php if (isset($_POST['Add']) || isset($_GET['action']) === 'add') { ?>
        <h3>Add Computer</h3>
        <form method="post">
            PC ID: <input type="text" name="PC_ID" value="<?php echo isset($_POST['PC_ID']) ? htmlspecialchars($_POST['PC_ID']) : ''; ?>" required><br><br>
            PC Number: <input type="number" name="PC_NUMBER" value="<?php echo isset($_POST['PC_NUMBER']) ? htmlspecialchars($_POST['PC_NUMBER']) : ''; ?>" required><br><br>
            Status:
            <select name="PC_STATUS">
                <option value="ACTIVE">ACTIVE</option>
                <option value="INACTIVE">INACTIVE</option>
                <option value="MAINTENANCE">MAINTENANCE</option>
            </select><br><br>
            Specs:
            <select name="PC_SPECS">
                <option value="ENTRY LEVEL">ENTRY LEVEL</option>
                <option value="MID RANGE">MID RANGE</option>
                <option value="HIGH-END">HIGH-END</option>
            </select><br><br>
            Location: <input type="text" name="PC_LOC" value="<?php echo isset($_POST['PC_LOC']) ? htmlspecialchars($_POST['PC_LOC']) : ''; ?>"><br><br>
            Last Maintenance: <input type="datetime-local" name="PC_LASTMAIN"
                value="<?php echo htmlspecialchars($old_shift ? date('Y-m-d\TH:i', strtotime($old_shift)) : ''); ?>"><br><br>
            Software: <input type="text" name="PC_SOFTWARE" value="<?php echo isset($_POST['PC_SOFTWARE']) ? htmlspecialchars($_POST['PC_SOFTWARE']) : ''; ?>"><br><br>
            Technician ID (optional): <input type="text" name="TECH_ID" value="<?php echo isset($_POST['TECH_ID']) ? htmlspecialchars($_POST['TECH_ID']) : ''; ?>"><br><br>

            <button type="submit" name="AddSubmit">Submit</button>
        </form>
    <?php } ?>

    <!-- UPDATE COMPUTER FORM -->
    <?php if (isset($_POST['Update']) || isset($_GET['action']) === 'update') { ?>
        <h3>Update Computer</h3>
        <form method="post">
            Select Computer (PC_ID):
            <select name="PC_ID" required>
                <option value="">--Select Computer--</option>
                <?php
                $result = $mysqli->query("SELECT PC_ID, PC_NUMBER FROM Computer");
                while ($comp = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($comp['PC_ID']) . '">'
                        . htmlspecialchars($comp['PC_ID'] . ' (PC ' . $comp['PC_NUMBER'] . ')')
                        . '</option>';
                }
                ?>
            </select><br><br>

            PC Number: <input type="number" name="PC_NUMBER"><br><br>
            Status:
            <select name="PC_STATUS">
                <option value="">--Keep Current--</option>
                <option value="ACTIVE">ACTIVE</option>
                <option value="INACTIVE">INACTIVE</option>
                <option value="MAINTENANCE">MAINTENANCE</option>
            </select><br><br>
            Specs:
            <select name="PC_SPECS">
                <option value="">--Keep Current--</option>
                <option value="ENTRY LEVEL">ENTRY LEVEL</option>
                <option value="MID RANGE">MID RANGE</option>
                <option value="HIGH-END">HIGH-END</option>
            </select><br><br>
            Location: <input type="text" name="PC_LOC"><br><br>
            Last Maintenance: <input type="datetime-local" name="PC_LASTMAIN"
                value="<?php echo htmlspecialchars($old_shift ? date('Y-m-d\TH:i', strtotime($old_shift)) : ''); ?>"><br><br>
            Software: <input type="text" name="PC_SOFTWARE"><br><br>
            Technician ID: <input type="text" name="TECH_ID"><br><br>

            <button type="submit" name="UpdateSubmit">Submit</button>
        </form>
    <?php } ?>

    <!-- REMOVE COMPUTER FORM -->
    <?php if (isset($_POST['Remove']) || isset($_GET['action']) === 'remove') { ?>
        <h3>Remove Computer</h3>
        <form method="post">
            Select Computer (PC_ID):
            <select name="PC_ID" required>
                <option value="">--Select Computer--</option>
                <?php
                $result = $mysqli->query("SELECT PC_ID, PC_NUMBER FROM Computer");
                while ($comp = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($comp['PC_ID']) . '">'
                        . htmlspecialchars($comp['PC_ID'] . ' (PC ' . $comp['PC_NUMBER'] . ')')
                        . '</option>';
                }
                ?>
            </select><br><br>

            <button type="submit" name="RemoveSubmit">Remove Computer</button>
        </form>
    <?php } ?>

    <?php
    // PROCESS ADD COMPUTER
    if (isset($_POST['AddSubmit'])) {
        $pc_id = $_POST['PC_ID'];
        $pc_number = $_POST['PC_NUMBER'];
        $pc_status = $_POST['PC_STATUS'];
        $pc_specs = $_POST['PC_SPECS'];
        $pc_loc = $_POST['PC_LOC'];
        $pc_lastmain = $_POST['PC_LASTMAIN'];
        $pc_software = $_POST['PC_SOFTWARE'];
        $tech_id = $_POST['TECH_ID'];

        $errors = [];

        if (empty($pc_id)) $errors[] = "PC ID is required.";
        elseif (!preg_match('/^PC[0-9]{1,5}$/', $pc_id)) $errors[] = "PC_ID must follow format PC0-PC99999.";

        $check = $mysqli->query("SELECT PC_ID FROM Computer WHERE PC_ID='$pc_id'");
        if ($check->num_rows > 0) $errors[] = "PC ID already exists.";
        $check->close();

        if (!is_numeric($pc_number)) $errors[] = "PC Number must be numeric.";
        if (!in_array($pc_status, ['ACTIVE', 'INACTIVE', 'MAINTENANCE'])) $errors[] = "Invalid status.";
        if (!in_array($pc_specs, ['ENTRY LEVEL', 'MID RANGE', 'HIGH-END'])) $errors[] = "Invalid specs.";
        if (!empty($tech_id) && !preg_match('/^T[0-9]{1,5}$/', $tech_id)) $errors[] = "Technician ID must follow format T0-T99999.";

        if (!empty($errors)) {
            $_SESSION['errors_admin_computer'] = $errors;
            header("Location: admin_computer.php?action=add");
            exit();
        }

        $sql = "INSERT INTO Computer (PC_ID, PC_NUMBER, PC_STATUS, PC_SPECS, PC_LOC, PC_LASTMAIN, PC_SOFTWARE, TECH_ID)
            VALUES ('$pc_id', '$pc_number', '$pc_status', '$pc_specs', '$pc_loc', '$pc_lastmain', '$pc_software', " . ($tech_id ? "'$tech_id'" : "NULL") . ")";

        if ($mysqli->query($sql)) {
            $_SESSION['message_admin_view'] = "Computer added successfully!";
            header("Location: admin_view.php");
            exit();
        } else {
            $_SESSION['errors_admin_computer'] = ["Error adding computer: " . $mysqli->error];
            header("Location: admin_computer.php?action=add");
            exit();
        }
    }

    // PROCESS UPDATE COMPUTER
    if (isset($_POST['UpdateSubmit'])) {
        $pc_id = $_POST['PC_ID'];
        $errors = [];

        if (empty($pc_id)) $errors[] = "PC ID is required.";
        elseif (!preg_match('/^PC[0-9]{1,5}$/', $pc_id)) $errors[] = "PC_ID must follow format PC0-PC99999.";

        $check = $mysqli->query("SELECT * FROM Computer WHERE PC_ID='$pc_id'");
        if ($check->num_rows === 0) $errors[] = "Computer with ID '$pc_id' does not exist.";
        else $existing = $check->fetch_assoc();
        $check->close();

        $pc_number = isset($_POST['PC_NUMBER']) && $_POST['PC_NUMBER'] !== "" ? $_POST['PC_NUMBER'] : $existing['PC_NUMBER'];
        $pc_status = !empty($_POST['PC_STATUS']) ? $_POST['PC_STATUS'] : $existing['PC_STATUS'];
        $pc_specs = !empty($_POST['PC_SPECS']) ? $_POST['PC_SPECS'] : $existing['PC_SPECS'];
        $pc_loc = isset($_POST['PC_LOC']) && $_POST['PC_LOC'] !== "" ? $_POST['PC_LOC'] : $existing['PC_LOC'];
        $pc_lastmain = isset($_POST['PC_LASTMAIN']) && $_POST['PC_LASTMAIN'] !== "" ? $_POST['PC_LASTMAIN'] : $existing['PC_LASTMAIN'];
        $pc_software = isset($_POST['PC_SOFTWARE']) && $_POST['PC_SOFTWARE'] !== "" ? $_POST['PC_SOFTWARE'] : $existing['PC_SOFTWARE'];
        $tech_id = isset($_POST['TECH_ID']) && $_POST['TECH_ID'] !== "" ? $_POST['TECH_ID'] : $existing['TECH_ID'];

        if (!is_numeric($pc_number)) $errors[] = "PC Number must be numeric.";
        if (!in_array($pc_status, ['ACTIVE', 'INACTIVE', 'MAINTENANCE'])) $errors[] = "Invalid status.";
        if (!in_array($pc_specs, ['ENTRY LEVEL', 'MID RANGE', 'HIGH-END'])) $errors[] = "Invalid specs.";
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $pc_lastmain)) $errors[] = "Last Maintenance must be in YYYY-MM-DD HH:MM:SS format.";
        if (!empty($tech_id) && !preg_match('/^T[0-9]{1,5}$/', $tech_id)) $errors[] = "Technician ID must follow format T0-T99999.";

        if (!empty($errors)) {
            $_SESSION['errors_admin_computer'] = $errors;
            header("Location: admin_computer.php?action=update");
            exit();
        }

        $sql = "UPDATE Computer SET
                PC_NUMBER='$pc_number',
                PC_STATUS='$pc_status',
                PC_SPECS='$pc_specs',
                PC_LOC='$pc_loc',
                PC_LASTMAIN='$pc_lastmain',
                PC_SOFTWARE='$pc_software',
                TECH_ID=" . ($tech_id ? "'$tech_id'" : "NULL") . "
            WHERE PC_ID='$pc_id'";

        if ($mysqli->query($sql)) {
            $_SESSION['message_admin_view'] = "Computer updated successfully!";
            header("Location: admin_view.php");
            exit();
        } else {
            $_SESSION['errors_admin_computer'] = ["Error updating computer: " . $mysqli->error];
            header("Location: admin_computer.php?action=update");
            exit();
        }
    }

    // PROCESS REMOVE COMPUTER
    if (isset($_POST['RemoveSubmit'])) {
        $pc_id = $_POST['PC_ID'];
        $errors = [];

        if (empty($pc_id)) $errors[] = "PC ID is required.";
        elseif (!preg_match('/^PC[0-9]{1,5}$/', $pc_id)) $errors[] = "PC_ID must follow format PC0-PC99999.";

        $check = $mysqli->query("SELECT PC_ID FROM Computer WHERE PC_ID='$pc_id'");
        if ($check->num_rows === 0) $errors[] = "Computer with ID '$pc_id' does not exist.";
        $check->close();

        if (!empty($errors)) {
            $_SESSION['errors_admin_computer'] = $errors;
            header("Location: admin_computer.php?action=remove");
            exit();
        }

        $sql = "DELETE FROM Computer WHERE PC_ID='$pc_id'";
        if ($mysqli->query($sql)) {
            $_SESSION['message_admin_view'] = "Computer removed successfully!";
            header("Location: admin_view.php");
            exit();
        } else {
            $_SESSION['errors_admin_computer'] = ["Error deleting computer: " . $mysqli->error];
            header("Location: admin_computer.php?action=remove");
            exit();
        }
    }
    ?>

    <br>
    <form action="admin_view.php" method="post">
        <button type="submit">Return to Admin View</button>
    </form>
</body>


</html>
