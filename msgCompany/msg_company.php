<?php
include_once("../db_connection/db_connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $messageInput = $_POST['message'];
    $companyId = $_POST['companyId'];
    $passengerId = $_SESSION['passengerId'];

    $insertMessageSql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ($passengerId, $companyId, '$messageInput')";
    $conn->query($insertMessageSql);
}

function getMessages($companyId, $conn)
{
    $passengerId = $_SESSION['passengerId'];
    $messagesSql = "SELECT * FROM messages WHERE (sender_id = $passengerId AND receiver_id = $companyId) OR (sender_id = $companyId AND receiver_id = $passengerId) ORDER BY timestamp";
    $messagesResult = $conn->query($messagesSql);
    $messages = [];
    if ($messagesResult->num_rows > 0) {
        while ($messageRow = $messagesResult->fetch_assoc()) {
            $messages[] = $messageRow;
        }
    }
    return $messages;
}

$companyId = $_GET['companyId'];

$companyNameSql = "SELECT name FROM Company WHERE id = $companyId";
$companyNameResult = $conn->query($companyNameSql);

if ($companyNameResult && $companyNameResult->num_rows > 0) {
    $companyNameRow = $companyNameResult->fetch_assoc();
    $companyName = $companyNameRow['name'];
} else {
    $companyName = 'Unknown Company';
}

$messages = getMessages($companyId, $conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="msg_company.css">
    <title>Messages with Company</title>
</head>
<body>

<div class="container">
    <h2>Messages with <?php echo $companyName; ?></h2>
    <div class="logo-container">
        <p class="logo">TouriTrip</p>
    </div>
    <div class="message-list" id="message-list">
        <?php foreach ($messages as $message) : ?>
            <div class="message">
                <strong><?php echo $message['sender_id'] == $passengerId ? 'You' : $companyName; ?>:</strong>
                <p><?php echo $message['message']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <form action="#" method="post">
        <input type="hidden" name="companyId" value="<?php echo $companyId; ?>">
        <label for="message">New Message:</label>
        <textarea id="message-input" name="message" rows="4" required></textarea>

        <button type="submit">Send</button>
    </form>
    <br>
    <button onclick="window.location.href='../passengerHome/passenger_home.php'">Return</button>

</div>

</body>
</html>
