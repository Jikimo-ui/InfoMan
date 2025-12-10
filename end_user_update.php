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
$cus_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

$sql = "SELECT * FROM InternetCafe.Customer WHERE CUS_ID = '$cus_id'";
$resultCustomer = $mysqli->query($sql);

$sql = "SELECT PC_ID FROM InternetCafe.Computer";
$resultPC = $mysqli->query($sql);
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
    <?php
    if (isset($_SESSION['errors_end_user_update']) && !empty($_SESSION['errors_end_user_update'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors_new_user_update'] as $error) {
            echo htmlspecialchars($error) . '<br>';
        }
        echo '</div>';
        unset($_SESSION['errors_end_user_update']);
    }

    if (isset($_SESSION['message_end_user_update.php'])) {
        echo '<div class="error">' . htmlspecialchars($_SESSION['message_end_user_update']) . '</div>';
        unset($_SESSION['message_end_user_update']);
    }
    ?>
    <table>
        <tr>
            <th>Customer ID</th>
            <th>Customer Username</th>
            <th>Customer Password</th>
            <th>Customer Name</th>
            <th>Membership Status</th>
            <th>Assigned Computer</th>
        </tr>

        <?php
        while ($row = $resultCustomer->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['CUS_ID']; ?></td>
                <td><?php echo $row['CUS_USER']; ?></td>
                <td><?php echo $row['CUS_PASS']; ?></td>
                <td><?php echo $row['CUS_FNAME'] . " " . $row['CUS_LNAME']; ?></td>
                <td><?php echo $row['CUS_MEMBERSHIP_STAT']; ?></td>
                <td><?php echo $row['PC_ID']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>


    <h2>Update Personal Details</h2>
    <p>Leave the space blank if you wish to not change the details</p>
    <form action="end_user_update_process.php" method="post">
        <!-- Username -->
        <label for="CUS_USER">Username:</label>
        <input type="text" id="CUS_USER" name="CUS_USER" maxlength="20"><br><br>

        <!-- Password -->
        <label for="CUS_PASS">Password:</label>
        <input type="password" id="CUS_PASS" name="CUS_PASS" maxlength="20"><br><br>

        <!-- First Name -->
        <label for="CUS_FNAME">First Name:</label>
        <input type="text" id="CUS_FNAME" name="CUS_FNAME" maxlength="20"><br><br>

        <!-- Last Name -->
        <label for="CUS_LNAME">Last Name:</label>
        <input type="text" id="CUS_LNAME" name="CUS_LNAME" maxlength="20"><br><br>

        <!-- Membership Status -->
        <label for="CUS_MEMBERSHIP_STAT">Membership Status:</label>
        <select id="CUS_MEMBERSHIP_STAT" name="CUS_MEMBERSHIP_STAT" required>
            <option value="BASIC">BASIC</option>
            <option value="PREMUIM">PREMIUM</option>
        </select><br><br>

        <!-- Assigned Computer ID -->
        <label for="PC_ID">Assigned Computer ID:</label>
        <select id="PC_ID" name="PC_ID" required>
            <?php
            // Loop through the result set and create <option> tags
            while ($row = $resultPC->fetch_assoc()) {
                echo "<option value='" . $row['PC_ID'] . "'>" . $row['PC_ID'] . "</option>";
            }
            ?>
        </select><br><br>

        <!-- Buttons -->
        <input type="submit" value="Update Account">
    </form>

</body>

</html>