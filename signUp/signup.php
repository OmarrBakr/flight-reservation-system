<?php
include_once("../db_connection/db_connection.php");

$name = $_POST['signupName'];
$email = $_POST['signupEmail'];
$password = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);
$tel = $_POST['signupTel'];
$accountType = $_POST['signupType'];

if ($accountType == "company") {
    $address = $_POST['companyAddress'];
    $bio = $_POST['companyBio'];
    $location = $_POST['companyLocation'];
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
    $sql = "INSERT INTO Company (name, bio, address, location, username, password, email, tel, logo_img) 
            VALUES ('$name', '$bio', '$address', '$location', '$email', '$password', '$email', '$tel', '$newImageName')";
} 
elseif ($accountType == "passenger") {
    $fileName2 = $_FILES["passengerPhoto"]["name"];
    $fileSize2 = $_FILES["passengerPhoto"]["size"];
    $tmpName2 = $_FILES["passengerPhoto"]["tmp_name"];

    $validImageExtension2 = ['jpg', 'jpeg', 'png'];
    $imageExtension2 = explode('.', $fileName2);
    $imageExtension2 = strtolower(end($imageExtension2));
    if ( !in_array($imageExtension2, $validImageExtension2) ){
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
    }
    else if($fileSize2 > 2000000){
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
    }
    else{
      $newImageName2 = $email;
      $newImageName2 .= '.' . $imageExtension2;

      move_uploaded_file($tmpName2, '../uploads/passengerImage/' . $newImageName2);
      echo
      "
      <script>
        alert('Successfully Added');
        document.location.href = 'data.php';
      </script>
      ";
    }

    $fileName3 = $_FILES["passengerPassport"]["name"];
    $fileSize3 = $_FILES["passengerPassport"]["size"];
    $tmpName3 = $_FILES["passengerPassport"]["tmp_name"];

    $validImageExtension3 = ['jpg', 'jpeg', 'png'];
    $imageExtension3 = explode('.', $fileName3);
    $imageExtension3 = strtolower(end($imageExtension3));
    if ( !in_array($imageExtension3, $validImageExtension3) ){
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
    }
    else if($fileSize3 > 2000000){
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
    }
    else{
      $newImageName3 = $email;
      $newImageName3 .= '.' . $imageExtension3;

      move_uploaded_file($tmpName3, '../uploads/passportImage/' . $newImageName3);
      echo
      "
      <script>
        alert('Successfully Added');
        document.location.href = 'data.php';
      </script>
      ";
    }

    $sql = "INSERT INTO Passenger (name, email, password, tel, photo, passport_img) 
            VALUES ('$name', '$email', '$password', '$tel', '$newImageName2', '$newImageName3')";
} else {
    echo "Invalid account type";
    exit();
}

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Registration successful!');</script>";
    header("Location: ../login/login.html");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

function uploadFile($fileInputName, $uploadDirectory)
{
    $targetDir = "../uploads/" . $uploadDirectory;
    $targetFile = $targetDir . basename($_FILES[$fileInputName]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES[$fileInputName]["size"] > 1000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    $allowedExtensions = array("jpg", "jpeg", "png");
    if (!in_array($imageFileType, $allowedExtensions)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return "path_to_default_image";
    } else {
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return "path_to_default_image";
        }
    }
}

?>