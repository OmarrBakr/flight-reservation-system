<?php
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

$passengerSql = "SELECT p.* FROM passenger p
                 JOIN passengerflight pf ON p.id = pf.passengerID
                 WHERE pf.flightID = $flightId";
$passengerResult = $conn->query($passengerSql);

if (!$passengerResult) {
    die("Query failed: " . $conn->error);
}

$passengers = [];

if ($passengerResult->num_rows > 0) {
    while ($passengerRow = $passengerResult->fetch_assoc()) {
        $passengers[] = $passengerRow;
    }
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
    <div class="flight-details">
        <strong>ID:</strong> <?php echo $flightRow['id']; ?><br>
        <strong>Name:</strong> <?php echo $flightRow['name']; ?><br>
        <strong>Itinerary:</strong> <?php echo $flightRow['itinerary']; ?><br>
    </div>
    <div>
        <h3>Passengers:</h3>
        <?php
        if (!empty($passengers)) {
            echo "<ul>";
            foreach ($passengers as $passenger) {
                echo "<li>{$passenger['name']}</li>";
            }
            echo "</ul>";
        } else {
            echo "No passengers found for this flight.";
        }
        ?>
    </div>
    <br>
    <button onclick="cancelFlight()">Cancel Flight</button>
    <button onclick="Return()">Return</button>
    <script>
        function cancelFlight() {
            window.location.href = 'delete_flight.php?flightId=<?php echo $flightRow['id']; ?>';
        }
        function Return() {
            window.location.href = '../companyHome/company_home.php';
        }
    </script>
</div>

</body>

</html>
