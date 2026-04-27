<?php
$conn = mysqli_connect("localhost", "root", "", "pickleyo_db");

if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

// FIX: Prevents the "Call to undefined function getFacility()" error
function getFacility($conn) {
    $res = mysqli_query($conn, "SELECT * FROM facility_info LIMIT 1");
    if (!$res || mysqli_num_rows($res) == 0) {
        return ['facility_name' => 'PICKLE YO!', 'full_address' => 'Kamagayan, Cebu City'];
    }
    return mysqli_fetch_assoc($res);
}

function getCourts($conn) {
    return mysqli_query($conn, "SELECT * FROM courts");
}

function getReservations($conn) {
    return mysqli_query($conn, "SELECT r.*, c.court_name FROM reservations r 
                                LEFT JOIN courts c ON r.court_id = c.court_id 
                                ORDER BY r.created_at DESC");
}

function formatPHP($price) {
    return "₱" . number_format($price, 0);
}

// RECOMMENDATION: Revenue tracking
function getTotalRevenue($conn) {
    $result = mysqli_query($conn, "SELECT SUM(total_price) as total FROM reservations");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

// RECOMMENDATION: Live occupancy status
function isCourtBusy($conn, $court_id) {
    $current_time = date("H:i:s");
    $current_date = date("Y-m-d");
    $query = "SELECT * FROM reservations 
              WHERE court_id = '$court_id' 
              AND reservation_date = '$current_date' 
              AND '$current_time' BETWEEN start_time AND end_time";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}
?>