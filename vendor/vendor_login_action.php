<?php
session_start();
include("../conn_db.php");

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the user exists and is a vendor
$query = "SELECT s_id FROM shop WHERE username = ? AND password = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Successful login
    $row = $result->fetch_assoc();
    $_SESSION['utype'] = 'VENDOR';
    $_SESSION['s_id'] = $row['s_id'];
    header("Location: vendor_home.php");
    exit();
} else {
    // Redirect to login page with an error
    $_SESSION['login_error'] = 'Invalid username or password';
    header("Location: vendor_login.php");
    exit();
}
?>
