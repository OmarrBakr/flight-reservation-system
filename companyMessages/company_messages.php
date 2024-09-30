<?php
include_once("../db_connection/db_connection.php");
session_start();

function getAllChats($companyId, $conn)
{
    $chatsSql = "SELECT DISTINCT p.id AS passengerId, p.name AS passengerName
                 FROM Passenger p
                 JOIN messages m ON (p.id = m.sender_id OR p.id = m.receiver_id)
                 WHERE m.sender_id = $companyId OR m.receiver_id = $companyId";

    $chatsResult = $conn->query($chatsSql);
    $chats = [];

    if ($chatsResult->num_rows > 0) {
        while ($chatRow = $chatsResult->fetch_assoc()) {
            $chats[] = $chatRow;
        }
    }

    return $chats;
}

$companyId = $_SESSION['companyId'];
$chats = getAllChats($companyId, $conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="company_messages.css">
    <title>Company Messages</title>
</head>
<body>

<div class="container">
    <div class="logo-container">
            <p class="logo">TouriTrip</p>
    </div>
    <h2>Messages</h2>
    <table>
        <thead>
            <tr>
                <th>Passenger Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chats as $chat) : ?>
                <tr class="clickable-row" data-passenger-id="<?php echo $chat['passengerId']; ?>">
                    <td><?php echo $chat['passengerName']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><br>
    <button onclick="window.location.href='../companyHome/company_home.php'">Return</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var rows = document.querySelectorAll('.clickable-row');
        rows.forEach(function (row) {
            row.addEventListener('click', function () {
                var passengerId = this.dataset.passengerId;
                window.location.href = `../msgPassenger/msg_passenger.php?passengerId=${passengerId}`;
            });
        });
    });
</script>

</body>
</html>
