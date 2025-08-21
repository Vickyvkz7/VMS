<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Dates
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$thisMonth = date('Y-m');

// Query to get visitors today
$sqlToday = "SELECT COUNT(*) AS visitorsToday FROM visitors WHERE DATE(created_at) = '$today'";
$resultToday = $conn->query($sqlToday);
$visitorsToday = $resultToday->fetch_assoc()['visitorsToday'] ?? 0;

// Query to get visitors yesterday
$sqlYesterday = "SELECT COUNT(*) AS visitorsYesterday FROM visitors WHERE DATE(created_at) = '$yesterday'";
$resultYesterday = $conn->query($sqlYesterday);
$visitorsYesterday = $resultYesterday->fetch_assoc()['visitorsYesterday'] ?? 0;

// Query to get visitors this month
$sqlThisMonth = "SELECT COUNT(*) AS visitorsThisMonth FROM visitors WHERE DATE_FORMAT(created_at, '%Y-%m') = '$thisMonth'";
$resultThisMonth = $conn->query($sqlThisMonth);
$visitorsThisMonth = $resultThisMonth->fetch_assoc()['visitorsThisMonth'] ?? 0;

// If visitors this month >= 100, delete all entries for this month
if ($visitorsThisMonth >= 100) {
    $deleteSQL = "DELETE FROM visitors WHERE DATE_FORMAT(created_at, '%Y-%m') = '$thisMonth'";
    if ($conn->query($deleteSQL) === TRUE) {
        $visitorsThisMonth = 0; // Reset count after deletion
    }
}

// Return data as JSON
echo json_encode([
    "visitorsToday" => $visitorsToday,
    "visitorsYesterday" => $visitorsYesterday,
    "visitorsThisMonth" => $visitorsThisMonth
]);

$conn->close();
?>
