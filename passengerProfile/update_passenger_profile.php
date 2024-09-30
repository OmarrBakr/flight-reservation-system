<?php
session_start();

include_once("../db_connection/db_connection.php");

$email = $_SESSION['email'];

$passengerName = $_POST['passengerName'];
$passengerEmail = $_POST['passengerEmail'];
$passengerTel = $_POST['passengerTel'];
$fileName = $_FILES["passengerImage"]["name"];
$fileSize = $_FILES["passengerImage"]["size"];
$tmpName = $_FILES["passengerImage"]["tmp_name"];

$validImageExtension = ['jpg', 'jpeg', 'png'];
$imageExtension = explode('.', $fileName);
$imageExtension = strtolower(end($imageExtension));
if ( !in_array($imageExtension, $validImageExtension) ){
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
}
else if($fileSize > 2000000){
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
}
else{
      $newImageName = $email;
      $newImageName .= '.' . $imageExtension;

      move_uploaded_file($tmpName, '../uploads/passengerImage/' . $newImageName);
      echo
      "
      <script>
        alert('Successfully Added');
        document.location.href = 'data.php';
      </script>
      ";
}

$updateSql = "UPDATE Passenger SET name = '$passengerName', email = '$passengerEmail', tel = '$passengerTel', photo = '$newImageName' WHERE email = '$email'";

if ($conn->query($updateSql) === TRUE) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . $conn->error;
}
header("Location: ../passengerHome/passenger_home.php");
$conn->close();
?>
