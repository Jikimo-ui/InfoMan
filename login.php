<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // clear error after showing
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <?php
  if (isset($_SESSION['errors_login']) && !empty($_SESSION['errors_login'])) {
    echo '<div class="error">';
    foreach ($_SESSION['errors_login'] as $error) {
      echo htmlspecialchars($error) . '<br>';
    }
    echo '</div>';
    unset($_SESSION['errors_login']);
  }
  
  if (isset($_SESSION['message_login'])) {
    echo '<div class="error">' . htmlspecialchars($_SESSION['message_login']) . '</div>';
    unset($_SESSION['message_login']);
  }
  ?>

  XAMPP FOLDER
  <h1>Customer Login</h1>
  <form action="process_login_end_user.php" method="post">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Login">
  </form>

  <form action="new_account.php" method="post">
    <input type="submit" value="Create New Account">
  </form>

  <h1>Admin Login</h1>
  <form action="process_login_admin.php" method="post">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Login">
  </form>
</body>
</html>
