<?php
session_start();

include_once("../db_connection/db_connection.php");

$email = $_SESSION['email'];
$accountType = $_SESSION['accountType'];
$companyId = $_SESSION['companyId'];

$companySql = "SELECT logo_img, name, bio, address FROM Company WHERE email = '$email'";
$companyResult = $conn->query($companySql);

if ($companyResult->num_rows > 0) {
    $companyData = $companyResult->fetch_assoc();
    $companyLogo = $companyData['logo_img'];
    $companyName = $companyData['name'];
    $companyBio = $companyData['bio'];
    $companyAddress = $companyData['address'];
} else {
    $logoImg = "default_logo.png";
    $companyName = "Company Name";
    $companyBio = "Company Bio";
    $companyAddress = "Company Address";
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
    <title>Company Profile</title>
    <link rel="stylesheet" href="company_profile.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <p class="logo">TouriTrip</p>
        </div>
        <h2>Company Profile</h2>
        <form action="update_company_profile.php" method="post" enctype="multipart/form-data">
            <label for="companyName">Name:</label>
            <input type="text" id="companyName" name="companyName" value="<?php echo $companyName; ?>">
            <label for="companyLogo">Logo Image:</label>
            <input type="file" id="companyLogo" name="companyLogo">
            <img src="../uploads/companyLogo/<?php echo $companyLogo; ?>" alt="Current Logo" class="current-logo">

            <label for="companyBio">Bio:</label>
            <textarea id="companyBio" name="companyBio"><?php echo $companyBio; ?></textarea>

            <label for="companyAddress">Address:</label>
            <input type="text" id="companyAddress" name="companyAddress" value="<?php echo $companyAddress; ?>">

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
                        echo "<tr class='clickable-row' data-href='#flight-details'>";
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
            </table><br>
        </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
