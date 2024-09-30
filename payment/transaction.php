<?php
session_start();

include_once("../db_connection/db_connection.php");

$flightId = $_GET['flightId'];
$paymentMethod = $_GET['paymentMethod'];
$userId = $_SESSION['passengerId'];

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

if ($paymentMethod === 'cash') {
    $insertPassengerFlightSql = "INSERT INTO passengerflight (passengerID, flightID) VALUES ($userId, $flightId)";
    $conn->query($insertPassengerFlightSql);
    $paymentResult = "Payment successful with cash.";
} elseif ($paymentMethod === 'online') {
    $userBalanceSql = "SELECT balance FROM passenger WHERE id = $userId";
    $userBalanceResult = $conn->query($userBalanceSql);

    if ($userBalanceResult && $userBalanceResult->num_rows > 0) {
        $userBalanceRow = $userBalanceResult->fetch_assoc();
        $userBalance = $userBalanceRow['balance'];

        if ($userBalance >= $flightRow['fees']) {
            $newUserBalance = $userBalance - $flightRow['fees'];
            $updateUserBalanceSql = "UPDATE passenger SET balance = $newUserBalance WHERE id = $userId";
            $conn->query($updateUserBalanceSql);
            $companyId = $flightRow['company_id'];
            $companyBalanceSql = "SELECT balance FROM company WHERE id = $companyId";
            $companyBalanceResult = $conn->query($companyBalanceSql);

            if ($companyBalanceResult && $companyBalanceResult->num_rows > 0) {
                $companyBalanceRow = $companyBalanceResult->fetch_assoc();
                $companyBalance = $companyBalanceRow['balance'];

                $newCompanyBalance = $companyBalance + $flightRow['fees'];
                $updateCompanyBalanceSql = "UPDATE company SET balance = $newCompanyBalance WHERE id = $companyId";
                $conn->query($updateCompanyBalanceSql);
                $insertPassengerFlightSql = "INSERT INTO passengerflight (passengerID, flightID) VALUES ($userId, $flightId)";
                $conn->query($insertPassengerFlightSql);
                $paymentResult = "Payment successful with online balance.";
            } else {
                $paymentResult = "Error updating company balance.";
            }
        } else {
            $paymentResult = "Insufficient balance for online payment.";
        }
    } else {
        $paymentResult = "Error fetching user balance.";
    }
} else {
    $paymentResult = "Invalid payment method.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="transaction.css">
    <title>Payment Result</title>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <p class="logo">TouriTrip</p>
        </div>
        <h2><?php echo $paymentResult; ?></h2>
        <button type="button" onclick="redirect()">Proceed</button>
    </div>
    <script>
        function redirect() {
            window.location.href = '../passengerHome/passenger_home.php';
        }
    </script>
</body>
</html>
