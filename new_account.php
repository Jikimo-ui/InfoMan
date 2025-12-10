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

$sql = "SELECT PC_ID FROM InternetCafe.Computer";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Customer Account</title>
  <style>
    .error { color: red; font-weight: bold; margin-bottom: 10px; }
  </style>
</head>
<body>
  <h1>Create Your Account</h1>
  
  <?php
  if (isset($_SESSION['errors_new_account']) && !empty($_SESSION['errors_new_account'])) {
    echo '<div class="error">';
    foreach ($_SESSION['errors_new_account'] as $error) {
      echo htmlspecialchars($error) . '<br>';
    }
    echo '</div>';
    unset($_SESSION['errors_new_account']);
  }
  
  if (isset($_SESSION['message_new_account'])) {
    echo '<div class="error">' . htmlspecialchars($_SESSION['message_new_account']) . '</div>';
    unset($_SESSION['message_new_account']);
  }
  ?>
  
  <form action="new_account_create.php" method="post">
    <!-- Customer ID -->
    <label for="CUS_ID">Customer ID:</label>
    <input type="text" id="CUS_ID" name="CUS_ID" maxlength="6" required><br><br>

    <!-- Username -->
    <label for="CUS_USER">Username:</label>
    <input type="text" id="CUS_USER" name="CUS_USER" maxlength="20" required><br><br>

    <!-- Password -->
    <label for="CUS_PASS">Password:</label>
    <input type="password" id="CUS_PASS" name="CUS_PASS" maxlength="20" required><br><br>

    <!-- First Name -->
    <label for="CUS_FNAME">First Name:</label>
    <input type="text" id="CUS_FNAME" name="CUS_FNAME" maxlength="20" required><br><br>

    <!-- Last Name -->
    <label for="CUS_LNAME">Last Name:</label>
    <input type="text" id="CUS_LNAME" name="CUS_LNAME" maxlength="20" required><br><br>

    <!-- Membership Status -->
    <label for="CUS_MEMBERSHIP_STAT">Membership Status:</label>
    <select id="CUS_MEMBERSHIP_STAT" name="CUS_MEMBERSHIP_STAT" required>
      <option value="BASIC">BASIC</option>
      <option value="PREMIUM">PREMIUM</option>
    </select><br><br>

    <!-- Assigned Computer ID -->
    <label for="PC_ID">Assigned Computer ID:</label>
    <select id="PC_ID" name="PC_ID" required>
        <?php
        // Loop through the result set and create <option> tags
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['PC_ID'] . "'>" . $row['PC_ID'] . "</option>";
        }
        ?>
    </select><br><br>

    <!-- Buttons -->
    <input type="submit" value="Create Account">
  </form>
</body>

</html>
