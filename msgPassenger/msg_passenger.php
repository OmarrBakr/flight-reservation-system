<?php
include_once("../db_connection/db_connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $messageInput = $_POST['message'];
    $companyId = $_SESSION['companyId'];
    $passengerId = $_POST['passengerId'];


    $insertMessageSql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ($companyId, $passengerId, '$messageInput')";
    $conn->query($insertMessageSql);
}

function getMessages($passengerId, $conn)
{
    $companyId = $_SESSION['companyId'];
    $messagesSql = "SELECT * FROM messages WHERE (sender_id = $companyId AND receiver_id = $passengerId) OR (sender_id = $passengerId AND receiver_id = $companyId) ORDER BY timestamp";
    $messagesResult = $conn->query($messagesSql);
    $messages = [];

    if ($messagesResult->num_rows > 0) {
        while ($messageRow = $messagesResult->fetch_assoc()) {
            $messages[] = $messageRow;
        }
    }

    return $messages;
}

$passengerId = $_GET['passengerId'];

$passengerNameSql = "SELECT name FROM Passenger WHERE id = $passengerId";
$passengerNameResult = $conn->query($passengerNameSql);

if ($passengerNameResult && $passengerNameResult->num_rows > 0) {
    $passengerNameRow = $passengerNameResult->fetch_assoc();
    $passengerName = $passengerNameRow['name'];
} else {
    $passengerName = 'Unknown Passenger';
}

$messages = getMessages($passengerId, $conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="msg_passenger.css">
    <title>Messages with Passenger</title>
</head>
<body>

<div class="container">
    <h2>Messages with <?php echo $passengerName; ?></h2>
    <div class="logo-container">
        <p class="logo">TouriTrip</p>
    </div>
    <div class="message-list" id="message-list">
        <?php foreach ($messages as $message) : ?>
            <div class="message">
                <strong><?php echo $message['sender_id'] == $companyId ? 'You' : $passengerName; ?>:</strong>
                <p><?php echo $message['message']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="#" method="post">
        <input type="hidden" name="passengerId" value="<?php echo $passengerId; ?>">
        <label for="message">New Message:</label>
        <textarea id="message-input" name="message" rows="4" required></textarea>

        <button type="submit">Send</button>
    </form>
    <br>
    <button onclick="window.location.href='../companyHome/company_home.php'">Return</button>

</div>

</body>
</html>
