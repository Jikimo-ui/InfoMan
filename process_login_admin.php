<?php
session_start();

// 1. Get Input
$username_input = isset($_POST['username']) ? trim($_POST['username']) : '';
$password_input = isset($_POST['password']) ? trim($_POST['password']) : '';



// 2. Check Credentials (Hardcoded)
if ($username_input == 'admin') {
    if($password_input == '123456789') {
    // --- SUCCESS ---
    // We set the session variables manually since we aren't getting them from a DB
    $_SESSION['username'] = 'Admin'; 
    $_SESSION['id'] = 1; // Assigning a dummy ID (1) so your other pages don't crash
    
    // Redirect to the admin page
    // Note: I fixed a typo in your previous code (you had 'adnin_view.php')
    header("Location: admin_view.php");
    exit();
    } else {
        // --- WRONG LOGIN ---
        $_SESSION['error_login'] = "Wrong password";
        header("Location: login.php");
        exit();
    }
} else {
     
    // --- WRONG LOGIN ---
    $_SESSION['error_login'] = "Wrong username";
    header("Location: login.php");
    exit();
}
?>