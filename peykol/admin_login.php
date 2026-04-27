<?php
session_start();
include 'logic.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Admin Credentials
    if ($username === "admin" && $password === "admin123") {
        $_SESSION['admin_logged_in'] = true;
        
        // Redirect directly to the Admin Panel
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        echo "<script>alert('Invalid Credentials'); window.location.href='dashboard.php';</script>";
    }
}
?>