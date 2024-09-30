<?php
include_once("../db_connection/db_connection.php");

if (isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $searchFlightsSql = "SELECT * FROM flight WHERE 
                         SUBSTRING_INDEX(itinerary, '-->', 1) = '$from' AND 
                         SUBSTRING_INDEX(itinerary, '-->', -1) = '$to' AND
                         start_time > NOW()";
    $searchFlightsResult = $conn->query($searchFlightsSql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Flights</title>
    <link rel="stylesheet" href="search_flight.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <p class="logo">TouriTrip</p>
        </div>
    <div class="search-flight-container">
        <h2>Search Flights</h2>
        <form method="post" action="">
            <label for="from">From:</label>
            <input type="text" name="from" required>

            <label for="to">To:</label>
            <input type="text" name="to" required>

            <button type="submit" name="search">Search</button>
            <button onclick="Return()">Return</button>
        </form>

        <?php if (isset($searchFlightsResult) && $searchFlightsResult->num_rows > 0): ?>
            <table class="current-flights">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Itinerary</th>
                        <th>Fees</th>
                        <th># Passengers</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $searchFlightsResult->fetch_assoc()) {
                        echo "<tr class='clickable-row' data-flight-id='{$row['id']}'>";
                        echo "<td>" . $row['custom_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['itinerary'] . "</td>";
                        echo "<td>" . $row['fees'] . "</td>";
                        echo "<td>" . $row['num_passengers'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php elseif (isset($searchFlightsResult)): ?>
            <p>No flights found for the specified itinerary.</p>
        <?php endif; ?> 
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var rows = document.querySelectorAll('.clickable-row');
            rows.forEach(function (row) {
                row.addEventListener('click', function () {
                    var flightId = this.dataset.flightId;
                    window.location.href = `../flightInfo/flight_info_passenger.php?flightId=${flightId}`;
                });
            });
        });

        function Return(){
            window.location.href = `../passengerHome/passenger_home.php`;
        }
    </script>

</body>
</html>

