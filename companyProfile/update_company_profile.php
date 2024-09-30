<?php
session_start();

include_once("../db_connection/db_connection.php");

$email = $_SESSION['email'];

$companyName = $_POST['companyName'];
$companyBio = $_POST['companyBio'];
$companyAddress = $_POST['companyAddress'];
$fileName = $_FILES["companyLogo"]["name"];
$fileSize = $_FILES["companyLogo"]["size"];
$tmpName = $_FILES["companyLogo"]["tmp_name"];

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

      move_uploaded_file($tmpName, '../uploads/companyLogo/' . $newImageName);
      echo
      "
      <script>
        alert('Successfully Added');
        document.location.href = 'data.php';
      </script>
      ";
}

$updateSql = "UPDATE Company SET logo_img = '$newImageName', name = '$companyName', bio = '$companyBio', address = '$companyAddress' WHERE email = '$email'";

if ($conn->query($updateSql) === TRUE) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . $conn->error;
}
header("Location: ../companyHome/company_home.php");
$conn->close();
?>
