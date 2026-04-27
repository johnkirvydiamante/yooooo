<?php
include 'logic.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['court_name']);
    $m_rate = $_POST['morning_rate'];
    $p_rate = $_POST['peak_rate'];
    
    $query = "INSERT INTO courts (court_name, morning_rate, peak_rate) VALUES ('$name', '$m_rate', '$p_rate')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>