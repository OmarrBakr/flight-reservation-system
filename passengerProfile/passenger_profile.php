<?php
session_start();

include_once("../db_connection/db_connection.php");

$email = $_SESSION['email'];

$passengerSql = "SELECT photo, name, email, tel FROM Passenger WHERE email = '$email'";
$passengerResult = $conn->query($passengerSql);

if ($passengerResult->num_rows > 0) {
    $passengerData = $passengerResult->fetch_assoc();
    $passengerName = $passengerData['name'];
    $passengerEmail = $passengerData['email'];
    $passengerTel = $passengerData['tel'];
    $passengerPhoto = $passengerData['photo'];
} else {
    $passengerName = "Error finding name";
    $passengerEmail = "Error finding email";
    $passengerTel = "Error finding tel";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Profile</title>
    <link rel="stylesheet" href="passenger_profile.css">
</head>
<body>
    <div class="container">
        <h2>Passenger Profile</h2>
        <div class="logo-container">
            <p class="logo">TouriTrip</p>
        </div>
        <form action="update_passenger_profile.php" method="post" enctype="multipart/form-data">
            <label for="passengerName">Name:</label>
            <input type="text" id="passengerName" name="passengerName" value="<?php echo $passengerName; ?>">

            <label for="passengerEmail">Email:</label>
            <input type="email" id="passengerEmail" name="passengerEmail" value="<?php echo $passengerEmail; ?>">

            <label for="passengerImage">Image:</label>
            <input type="file" id="passengerImage" name="passengerImage">
            <img src="../uploads/passengerImage/<?php echo $passengerPhoto; ?>" alt="Current Image" class="current-image">

            <label for="passengerTel">Tel:</label>
            <input type="tel" id="passengerTel" name="passengerTel" value="<?php echo $passengerTel; ?>">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
