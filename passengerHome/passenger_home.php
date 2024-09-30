<?php
session_start();

include_once("../db_connection/db_connection.php");

$email = $_SESSION['email'];

$passengerSql = "SELECT photo, id, name, tel , balance FROM Passenger WHERE email = '$email'";
$passengerResult = $conn->query($passengerSql);

if ($passengerResult->num_rows > 0) {
    $passengerData = $passengerResult->fetch_assoc();
    $passengerId = $passengerData['id'];
    $passengerName = $passengerData['name'];
    $passengerTel = $passengerData['tel'];
    $passengerBalance = $passengerData['balance'];
    $passengerPhoto = $passengerData['photo'];

    $activeFlightsSql = "SELECT f.id, f.name, f.itinerary, f.fees, f.num_passengers, f.start_time, f.end_time
    FROM passengerflight pf
    JOIN flight f ON pf.FlightID = f.id
    WHERE pf.PassengerID = $passengerId AND f.start_time < NOW() AND f.end_time > NOW()";

    $activeFlightsResult = $conn->query($activeFlightsSql);

    if ($activeFlightsResult === false) {
        die("Active flights query failed: " . $conn->error);
    }

    $upcomingFlightsSql = "SELECT f.id, f.name, f.itinerary, f.fees, f.num_passengers, f.start_time, f.end_time
    FROM passengerflight pf
    JOIN flight f ON pf.FlightID = f.id
    WHERE pf.PassengerID = $passengerId AND f.completed = 0 AND f.start_time > NOW()";

    $upcomingFlightsResult = $conn->query($upcomingFlightsSql);

    if ($upcomingFlightsResult === false) {
        die("Current flights query failed: " . $conn->error);
    }

    $previousFlightsSql = "SELECT f.id, f.name, f.itinerary, f.fees, f.num_passengers, f.start_time, f.end_time
    FROM passengerflight pf
    JOIN flight f ON pf.FlightID = f.id
    WHERE pf.PassengerID = $passengerId AND f.end_time < NOW()";

    $previousFlightsResult = $conn->query($previousFlightsSql);

    if ($previousFlightsResult === false) {
        die("Previous flights query failed: " . $conn->error);
    }

} else {
    $passengerName = "Passenger Name";
    $passengerTel = "Passenger Telephone";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Home</title>
    <link rel="stylesheet" href="passenger_home.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $passengerName; ?>!</h2>
        <div class="logo-container">
            <p class="logo">TouriTrip</p>
        </div>
        <div class="passenger-info">
            <img src="../uploads/passengerImage/<?php echo $passengerPhoto; ?>" alt="User Image" class="user-image">
            <div class="user-details">
                <p><strong>Name:</strong> <?php echo $passengerName; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Tel:</strong> <?php echo $passengerTel; ?></p>
                <p><strong>Balance:</strong> <?php echo $passengerBalance; ?></p>
            </div>
        </div>
        <div class="company-navigation">
            <ul>
                <li><a href="../passengerProfile/passenger_profile.php">Profile</a></li>
                <li><a href="../searchFlight/search_flight.php">Search Flights</a></li>
                <li><a href="../login/login.html">Log out</a></li>
            </ul>
        </div>
        <div class="flights-section">
            <h3>Upcoming Flights</h3>
            <table class="current-flights">
                <tr>
                    <th>Name</th>
                    <th>Itinerary</th>
                    <th>Fees</th>
                    <th>#Passengers</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                <?php while ($row = $upcomingFlightsResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['itinerary']; ?></td>
                        <td><?php echo $row['fees']; ?></td>
                        <td><?php echo $row['num_passengers']; ?></td>
                        <td><?php echo $row['start_time']; ?></td>
                        <td><?php echo $row['end_time']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h3>Active Flights</h3>
            <table class="current-flights">
                <tr>
                    <th>Name</th>
                    <th>Itinerary</th>
                    <th>Fees</th>
                    <th>#Passengers</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                <?php while ($row = $activeFlightsResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['itinerary']; ?></td>
                        <td><?php echo $row['fees']; ?></td>
                        <td><?php echo $row['num_passengers']; ?></td>
                        <td><?php echo $row['start_time']; ?></td>
                        <td><?php echo $row['end_time']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h3>Previous Flights</h3>
            <table class="completed-flights">
                <tr>
                    <th>Name</th>
                    <th>Itinerary</th>
                    <th>Fees</th>
                    <th>#Passengers</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                <?php while ($row = $previousFlightsResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['itinerary']; ?></td>
                        <td><?php echo $row['fees']; ?></td>
                        <td><?php echo $row['num_passengers']; ?></td>
                        <td><?php echo $row['start_time']; ?></td>
                        <td><?php echo $row['end_time']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>         
    </div>
</body>
</html>
