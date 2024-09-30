<?php
include_once("../db_connection/db_connection.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginEmail = $_POST['loginEmail'];
    $loginPassword = $_POST['loginPassword'];

    $passengerSql = "SELECT * FROM Passenger WHERE email = '$loginEmail'";
    $passengerResult = $conn->query($passengerSql);

    $companySql = "SELECT * FROM Company WHERE email = '$loginEmail'";
    $companyResult = $conn->query($companySql);

    if ($passengerResult->num_rows > 0) {
        $row = $passengerResult->fetch_assoc();
        $storedPassword = $row['password'];
        $passengerId = $row['id'];
        if (password_verify($loginPassword, $storedPassword)) {
            session_start();
            echo "Passenger login successful!";
            $_SESSION['email'] = $loginEmail;
            $_SESSION['accountType'] = 'passenger';
            $_SESSION['passengerId'] = $passengerId;
            header("Location: ../passengerHome/passenger_home.php");
            exit();
        } else {
            echo "Invalid password for passenger";
        }
    } elseif ($companyResult->num_rows > 0) {
        $row = $companyResult->fetch_assoc();
        $storedPassword = $row['password'];
        $companyId = $row['id'];
        if (password_verify($loginPassword, $storedPassword)) {
            session_start();
            echo "Company login successful!";
            $_SESSION['email'] = $loginEmail;
            $_SESSION['accountType'] = 'company';
            $_SESSION['companyId'] = $companyId;
            header("Location: ../companyHome/company_home.php");
            exit();
        } else {
            echo "Invalid password for company";
        }
    } else {
        echo "User not found";
    }
}

$conn->close();
?>