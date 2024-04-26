<?php
session_start();
include 'app/db.conn.php'; 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$groupId = $_GET['group_id'] ?? null; 

function getGroupChats($groupId, $conn) {

    $stmt = $conn->prepare("SELECT group_chats.*, users.name FROM group_chats JOIN users ON group_chats.from_id = users.user_id WHERE group_id = ? ORDER BY created_at ASC");
    $stmt->execute([$groupId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sendMessageToGroup($groupId, $userId, $message, $conn) {
    $stmt = $conn->prepare("INSERT INTO group_chats (group_id, from_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$groupId, $userId, $message]);
}

$stmt = $conn->prepare("SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ?");
$stmt->execute([$groupId, $userId]);
$isUserInGroup = $stmt->fetchColumn();

if (!$isUserInGroup) {
    echo "Você não é membro deste grupo.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        sendMessageToGroup($groupId, $userId, $message, $conn);
    }
}
function getGroupMembers($groupId, $conn) {
    $stmt = $conn->prepare("SELECT users.name FROM users JOIN group_members ON users.user_id = group_members.user_id WHERE group_members.group_id = ?");
    $stmt->execute([$groupId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$groupMembers = getGroupMembers($groupId, $conn);

$groupChats = getGroupChats($groupId, $conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat do Grupo</title>

</head>
<body>
<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
}

.chat-container {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    border-radius: 5px;
    overflow: hidden;
}


.chat-container h1 {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    margin: 0;
    text-align: center;
}


.messages {
    height: 300px;
    overflow-y: auto;
    padding: 10px;
    background-color: #e9ecef;
}

.message {
    margin-bottom: 10px;
    padding: 5px;
    border-radius: 5px;
}

.message.sent {
    background-color: #dcf8c6;
    text-align: right;
}

.message.received {
    background-color: #fff;
}

.message .user {
    font-weight: bold;
}

.message .text {
    display: inline-block;
    max-width: 80%;
}

.message .time {
    display: block;
    font-size: 0.8em;
    color: #888;
}

form {
    padding: 10px;
    background-color: #f7f7f7;
    border-top: 1px solid #e9ecef;
}

form textarea {
    width: calc(100% - 90px);
    margin-right: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 5px;
    resize: none;
}

form button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 15px;
    border-radius: 5px;
    cursor: pointer;
}

form button:hover {
    background-color: #0056b3;
}

a.button {
    display: inline-block;
    margin: 10px auto;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

a.button:hover {
    background-color: #0056b3;
}</style>    

<div class="chat-container">
        <h1>Chat do Grupo</h1>
        <div class="group-members">
    <strong>Membros:</strong>
    <?php foreach ($groupMembers as $index => $member): ?>
        <span><?= htmlspecialchars($member['name']) ?><?= $index < count($groupMembers) - 1 ? ', ' : '' ?></span>
    <?php endforeach; ?>
</div>
        <div class="messages">
        <?php foreach ($groupChats as $chat): ?>
    <div class="message <?= $chat['from_id'] == $userId ? 'sent' : 'received' ?>">

        <span class="user"><?= htmlspecialchars($chat['name']) ?>:</span>
        <p class="text"><?= htmlspecialchars($chat['message']) ?></p>
        <span class="time"><?= htmlspecialchars($chat['created_at']) ?></span>
    </div>
<?php endforeach; ?>
        </div>
        <form action="" method="post">
            <textarea name="message" placeholder="Digite uma mensagem..."></textarea>
            <button type="submit">Enviar</button>
            <a href="home.php" class="button">Inicio</a>
            <a href="grupos.php" class="button">Voltar</a>
        </form>
         
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var chatBox = document.querySelector('.messages');
    if (chatBox.scrollTop === 0) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

 
    var messageInput = document.querySelector('textarea[name="message"]');
    messageInput.addEventListener('keypress', function(event) {
   
        if (event.key === 'Enter') {
            event.preventDefault(); 
            var form = this.closest('form'); 
            form.submit(); 
        }
    });
});
    </script>
</body>
</html>