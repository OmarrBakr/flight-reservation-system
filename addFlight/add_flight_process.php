<?php
session_start();

include_once("../db_connection/db_connection.php");

$flightName = $_POST['flightName'];
$flightId = $_POST['flightId'];
$flightItinerary = $_POST['flightItinerary'];
$flightFees = $_POST['flightFees'];
$passengerCount = $_POST['passengerCount'];
$flightStart = $_POST['flightStart'];
$flightEnd = $_POST['flightEnd'];

if (isset($_SESSION['accountType']) && $_SESSION['accountType'] == 'company') {
    $companyId = $_SESSION['companyId'];
} else {
    echo "Invalid user or not logged in as a company.";
    exit();
}

$insertSql = "INSERT INTO Flight (name, custom_id, itinerary, fees, num_passengers, start_time, end_time, company_id) 
              VALUES ('$flightName', '$flightId', '$flightItinerary', '$flightFees', '$passengerCount', '$flightStart', '$flightEnd', '$companyId')";

if ($conn->query($insertSql) === TRUE) {
    echo "Flight added successfully!";
} else {
    echo "Error adding flight: " . $conn->error;
}
header("Location: ../companyHome/company_home.php");
$conn->close();
?>
