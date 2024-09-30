<?php
session_start();

include_once("../db_connection/db_connection.php");

$flightId = $_GET['flightId'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="payment.css">
    <title>Payment Method</title>
</head>
<body>

<div class="container">
    <h2>Select Payment Method</h2>
    <div class="logo-container">
            <p class="logo">TouriTrip</p>
    </div>

    <div class="payment-options">
        <button onclick="choosePayment('cash')">Pay with Cash</button><br>
        <button onclick="choosePayment('online')">Pay with Online Balance</button>
    </div>

    <script>
        function choosePayment(method) {
            var flightId = <?php echo $flightId; ?>;
            window.location.href = 'transaction.php?flightId=' + flightId + '&paymentMethod=' + method;
        }
    </script>
</div>

</body>
</html>
