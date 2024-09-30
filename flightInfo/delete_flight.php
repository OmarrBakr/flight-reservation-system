<?php
session_start();

include_once("../db_connection/db_connection.php");

$flightId = $_GET['flightId'];

$flightSql = "SELECT * FROM flight WHERE id = $flightId";
$flightResult = $conn->query($flightSql);

if (!$flightResult) {
    die("Query failed: " . $conn->error);
}

if ($flightResult->num_rows > 0) {
    $flightRow = $flightResult->fetch_assoc();
} else {
    die("Flight not found");
}

$passengerFlightSql = "SELECT passengerID FROM passengerflight WHERE flightID = $flightId";
$passengerFlightResult = $conn->query($passengerFlightSql);

if ($passengerFlightResult->num_rows > 0) {
    while ($passengerRow = $passengerFlightResult->fetch_assoc()) {
        $passengerId = $passengerRow['passengerID'];
        $refundSql = "UPDATE passenger SET balance = balance + {$flightRow['fees']} WHERE id = $passengerId";
        $conn->query($refundSql);
        $companyId = $flightRow['company_id'];
        $updateCompanyBalanceSql = "UPDATE company SET balance = balance - {$flightRow['fees']} WHERE id = $companyId";
        $conn->query($updateCompanyBalanceSql);
    }
}


$deleteSql = "DELETE FROM flight WHERE id = $flightId";
if ($conn->query($deleteSql) === TRUE) {
    header("Location: ../companyHome/company_home.php");
    exit();
} else {
    echo "Error deleting flight: " . $conn->error;
}

$conn->close();
?>
