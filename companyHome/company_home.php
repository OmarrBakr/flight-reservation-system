<?php
session_start();

include_once("../db_connection/db_connection.php");

$email = $_SESSION['email'];
$accountType = $_SESSION['accountType'];
$companyId = $_SESSION['companyId'];
if ($accountType == 'company') {
    $companySql = "SELECT name, balance, logo_img FROM company WHERE id = '$companyId'";
    $companyResult = $conn->query($companySql);

    if ($companyResult->num_rows > 0) {
        $companyRow = $companyResult->fetch_assoc();
        $companyName = $companyRow['name'];
        $companyBalance = $companyRow['balance'];
        $companyLogo = $companyRow['logo_img'];
    } else {
        $companyName = 'Unknown Company';
    }
} else {
    $companyName = 'Invalid Account Type';
}

$flightSql = "SELECT * FROM Flight WHERE company_id = $companyId";
$flightResult = $conn->query($flightSql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Home</title>
    <link rel="stylesheet" href="company_home.css">
</head>
<body>
    <div class="company-home-container">
        <div class="logo-container">
             <p class="logo">TouriTrip</p>
        </div>
        
        <div class="company-header">
            <img src="../uploads/companyLogo/<?php echo $companyLogo; ?>" alt="Company Logo" class="company-logo">
            <h1 class="company-name"><?php echo $companyName; ?></h1>
        </div>
        <br>
        <div class="company-navigation">
            <ul>
                <li><a href="../companyProfile/company_profile.php">Profile</a></li>
                <li><a href="../addFlight/add_flight.html">Add Flight</a></li>
                <li><a href="../companyMessages/company_messages.php">Messages</a></li>
                <li><a href="#">Balance: $<?php echo $companyBalance; ?></a></li>
                <li><a href="../login/login.html">Log out</a></li>
            </ul>
        </div>

        <div class="flight-list-container">
            <h2>Flight List</h2>

            <table>
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
                    while ($row = $flightResult->fetch_assoc()) {
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
        </div>
    </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var rows = document.querySelectorAll('.clickable-row');
                rows.forEach(function (row) {
                    row.addEventListener('click', function () {
                        var customId = this.querySelector('td:first-child').innerText;
                        var flightId = this.dataset.flightId;

                        window.location.href = `../flightInfo/flight_info_company.php?flightId=${flightId}`;
                    });
                });
            });
        </script>
</body>
</html>
