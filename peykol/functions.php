<?php
include 'config/db.php';

// 1. JOIN QUERY: Get bookings with Player and Court names
function getDetailedBookings($conn) {
    $sql = "SELECT b.booking_id, p.full_name, c.court_name, b.fee 
            FROM bookings b
            JOIN players p ON b.player_id = p.player_id
            JOIN courts c ON b.court_id = c.court_id";
    return $conn->query($sql);
}

// 2. SUBQUERY: Get players who paid more than the average fee
function getPremiumPlayers($conn) {
    $sql = "SELECT full_name FROM players 
            WHERE player_id IN (SELECT player_id FROM bookings WHERE fee > 20)";
    return $conn->query($sql);
}

// 3. TRANSACTION: Securely book a court
function createBookingTransaction($conn, $playerId, $courtId, $fee) {
    $conn->begin_transaction();
    try {
        $date = date('Y-m-d');
        $conn->query("INSERT INTO bookings (player_id, court_id, booking_date, fee) VALUES ($playerId, $courtId, '$date', $fee)");
        $conn->query("UPDATE courts SET status = 'Occupied' WHERE court_id = $courtId");
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}
?>