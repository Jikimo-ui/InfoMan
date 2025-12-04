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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician Management</title>
</head>
<body>

<h2>Technician Management</h2>

<?php
// SHOW ADD TECHNICIAN FORM
if (isset($_POST['Add'])) {
?>
    <h3>Add Technician</h3>
    <form method="post">
        Technician ID (TECH_ID): <input type="text" name="TECH_ID" required><br><br>
        First Name: <input type="text" name="TECH_FNAME"><br><br>
        Last Name: <input type="text" name="TECH_LNAME"><br><br>
        Shift: <input type="text" name="TECH_SHIFT"><br><br>
        Salary: <input type="number" name="TECH_SALARY"><br><br>
        Specialization: <input type="text" name="TECH_SPECIALIZATION"><br><br>

        <button type="submit" name="AddSubmit">Submit</button>
    </form>
<?php
}

// PROCESS ADD TECHNICIAN
if (isset($_POST['AddSubmit'])) {
    
    $tech_id = $_POST['TECH_ID'];
    $fname = $_POST['TECH_FNAME'];
    $lname = $_POST['TECH_LNAME'];
    $shift = $_POST['TECH_SHIFT'];
    $salary = $_POST['TECH_SALARY'];
    $spec = $_POST['TECH_SPECIALIZATION'];

    $sql = "INSERT INTO Technician (TECH_ID, TECH_FNAME, TECH_LNAME, TECH_SHIFT, TECH_SALARY, TECH_SPECIALIZATION)
            VALUES ('$tech_id', '$fname', '$lname', '$shift', '$salary', '$spec')";

    $mysqli->query($sql);

    echo "<p>Technician added successfully!</p>";
}

?>

<?php
// SHOW UPDATE TECHNICIAN FORM
if (isset($_POST['Update'])) {
?>
    <h3>Update Technician</h3>
    <form method="post">
        Technician ID (TECH_ID): <input type="text" name="TECH_ID" required><br><br>

        New First Name: <input type="text" name="TECH_FNAME"><br><br>
        New Last Name: <input type="text" name="TECH_LNAME"><br><br>
        New Shift: <input type="text" name="TECH_SHIFT"><br><br>
        New Salary: <input type="number" name="TECH_SALARY"><br><br>
        New Specialization: <input type="text" name="TECH_SPECIALIZATION"><br><br>

        <button type="submit" name="UpdateSubmit">Submit Changes</button>
    </form>
<?php
}

// PROCESS UPDATE TECHNICIAN WITH EMPTY-FIELD HANDLING
if (isset($_POST['UpdateSubmit'])) {

    $id = $_POST['TECH_ID'];

    // Get existing record
    $result = $mysqli->query("SELECT * FROM Technician WHERE TECH_ID='$id'");
    $existing = $result->fetch_assoc();

    // Keep old values if new ones are blank
    $fname = $_POST['TECH_FNAME'] !== "" ? $_POST['TECH_FNAME'] : $existing['TECH_FNAME'];
    $lname = $_POST['TECH_LNAME'] !== "" ? $_POST['TECH_LNAME'] : $existing['TECH_LNAME'];
    $shift = $_POST['TECH_SHIFT'] !== "" ? $_POST['TECH_SHIFT'] : $existing['TECH_SHIFT'];
    $salary = $_POST['TECH_SALARY'] !== "" ? $_POST['TECH_SALARY'] : $existing['TECH_SALARY'];
    $spec = $_POST['TECH_SPECIALIZATION'] !== "" ? $_POST['TECH_SPECIALIZATION'] : $existing['TECH_SPECIALIZATION'];

    // Perform update
    $sql = "UPDATE Technician 
            SET TECH_FNAME='$fname',
                TECH_LNAME='$lname',
                TECH_SHIFT='$shift',
                TECH_SALARY='$salary',
                TECH_SPECIALIZATION='$spec'
            WHERE TECH_ID='$id'";

    $mysqli->query($sql);

    echo "<p>Technician updated successfully! (Blank fields kept original values)</p>";
}
?>

<?php
// SHOW REMOVE TECHNICIAN FORM
if (isset($_POST['Remove'])) {
?>
    <h3>Remove Technician</h3>
    <form method="post">
        Technician ID (TECH_ID): <input type="text" name="TECH_ID"><br><br>
        <button type="submit" name="RemoveSubmit">Remove Technician</button>
    </form>
<?php
}

// PROCESS REMOVE TECHNICIAN
if (isset($_POST['RemoveSubmit'])) {

    $id = $_POST['TECH_ID'];

    $sql = "DELETE FROM Technician WHERE TECH_ID='$id'";
    $mysqli->query($sql);

    echo "<p>Technician removed successfully!</p>";
}
?>

<br>
<form action="admin_view.php" method="post">
    <button type=submit>Return to Admin View</button>
</form>

</body>
</html>
