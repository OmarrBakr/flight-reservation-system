<?php
include_once("../db_connection/db_connection.php");

session_start();

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

$companyId = $flightRow['company_id'];

$companySql = "SELECT name FROM Company WHERE id = $companyId";
$companyResult = $conn->query($companySql);

if (!$companyResult) {
    die("Query failed: " . $conn->error);
}

if ($companyResult->num_rows > 0) {
    $companyRow = $companyResult->fetch_assoc();
    $companyName = $companyRow['name'];
} else {
    $companyName = 'Unknown Company';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="flight_info.css">
    <title>Flight Info</title>
</head>
<body>

<div class="container">
    <h2>Flight Info</h2>
    <div class="logo-container">
        <p class="logo">TouriTrip</p>
    </div>
    <table class="flight-details">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Itinerary</th>
            <th>Fees</th>
            <th># Passengers</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
        <tr>
            <td><?php echo $flightRow['id']; ?></td>
            <td><?php echo $flightRow['name']; ?></td>
            <td><?php echo $flightRow['itinerary']; ?></td>
            <td><?php echo $flightRow['fees']; ?></td>
            <td><?php echo $flightRow['num_passengers']; ?></td>
            <td><?php echo $flightRow['start_time']; ?></td>
            <td><?php echo $flightRow['end_time']; ?></td>
        </tr>
    </table>
    <br>
    <div class="buttons">
        <button onclick="takeFlight()">Take Flight</button> 
        <button onclick="messageCompany()">Message Company</button>
    </div>

    <script>
        function messageCompany() {
            window.location.href = '../msgCompany/msg_company.php?companyId=<?php echo $companyId; ?>';
        }
        function takeFlight() {
            window.location.href = '../payment/payment.php?flightId=<?php echo $flightId; ?>';
        }
    </script>
</div>

</body>
</html>
