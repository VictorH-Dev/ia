<?php
session_start();
include 'app/db.conn.php'; 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$groupId = $_GET['group_id'] ?? null; 
$groupName = getGroupName($groupId, $conn);
$groupDetails = getGroupDetails($groupId, $conn);

function getGroupChats($groupId, $conn) {

    $stmt = $conn->prepare("SELECT group_chats.*, users.name, users.p_p FROM group_chats JOIN users ON group_chats.from_id = users.user_id WHERE group_id = ? ORDER BY created_at ASC");
    $stmt->execute([$groupId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo'); 
function getGroupDetails($groupId, $conn) {
    $stmt = $conn->prepare("
        SELECT groups.*, 
               COUNT(group_members.user_id) as member_count,
               users.username as created_by_username
        FROM groups 
        LEFT JOIN group_members ON groups.group_id = group_members.group_id
        JOIN users ON groups.created_by = users.user_id
        WHERE groups.group_id = ?
        GROUP BY groups.group_id
    ");
    $stmt->execute([$groupId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getGroupName($groupId, $conn) {
    $stmt = $conn->prepare("SELECT group_name FROM groups WHERE group_id = ?");
    $stmt->execute([$groupId]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['group_name'];
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
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}
function getGroupMembers($groupId, $conn) {
    $stmt = $conn->prepare("SELECT users.name FROM users JOIN group_members ON users.user_id = group_members.user_id WHERE group_members.group_id = ?");
    $stmt->execute([$groupId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$groupMembers = getGroupMembers($groupId, $conn);

$groupChats = getGroupChats($groupId, $conn);
function isNewDay($currentDate, $lastDate) {
    return $lastDate === null || $currentDate->format('Y-m-d') !== $lastDate->format('Y-m-d');
}

$lastDate = null; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat do Grupo</title>
    </head>
    <body>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e5ddd5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .chat-container {
            width: 100%;
            max-width: 400px;
            height: 600px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        .chat-header {
            background-color: #0d6efd;
            color: #fff;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header h1 {
            font-size: 1.2em;
            margin: 0;
        }

        .chat-header .back-arrow {
            background-image: url('caminho/para/imagem/seta.png');
            width: 24px;
            height: 24px;
            background-size: cover;
            cursor: pointer;
        }

        .messages {
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    padding: 10px;
    background-color: #e5ddd5;
}

.message {
    padding: 8px 10px;
    border-radius: 7px;
    word-wrap: break-word;
    font-size: 0.9em;
    margin-bottom: 10px;
    width: fit-content;
    max-width: 80%;
}

.message.sent {
    background-color: #b7d5e5;
    align-self: flex-end;
    float: right;
    clear: both;
}

.message.received {
    background-color: #fff;
    align-self: flex-start;
    float: left;
    clear: both;
}
        .message .user {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .message .text {
            background-color: inherit;
            padding: 5px;
            border-radius: 7px;
            word-wrap: break-word;
            max-width: 100%;
        }

        .message .time {
            align-self: flex-end;
            font-size: 0.75em;
            color: #777;
            margin-top: 2px;
        }

        form {   margin-top: auto; 
    display: flex;
    padding: 10px;
    background-color: #f0f0f0;
    border-top: 1px solid #ddd;
        }

        form textarea {
            flex: 1;
            margin-right: 10px;
            border: none;
            border-radius: 18px;
            padding: 10px;
            resize: none;
            font-size: 0.9em;
        }

        form button {
            background-color: #0d6efd;
            border: none;
            padding: 8px 16px;
            border-radius: 18px;
            color: #fff;
            cursor: pointer;
            font-size: 0.9em;
        }
        .back-arrow i,
form button i {
    color: #fff; 
}

form button {
    display: flex;
    align-items: center;
    justify-content: center;
}

form button i {
    font-size: 1.2em; 
}
.profile-pic {
    width: 30px; 
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
    float: left;
}
.group-members span {
    display: inline-block;
    margin-right: 5px;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
button {
    background: linear-gradient(to right, #6dd5fa, #0d6efd);
    border: none;
    color: white;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 25px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
}
.group-members {
    background-color: #f7f7f7;
    border-radius: 15px;
    padding: 2px;
    margin: 2px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.group-members strong {
    color: #0d6efd;
    font-weight: bold;
}

.group-members span {
    display: inline-block;
    background-color: #e1ecf4;
    border-radius: 15px;
    padding: 5px 10px;
    margin: 5px;
    font-size: 14px;
    color: #333;
}
button:hover {
    box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
}
.date-separator {
    text-align: center;
    margin: 20px 0;
    color: #555;
    font-size: 14px;
}
    </style>
    <div class="chat-container">
    <div class="chat-header">
    <a href="grupos.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
    <h1><?= htmlspecialchars($groupName) ?></h1>
    <a href="grupos.php" class="back-arrow"></a>
</div>
<div class="group-members">
            <strong>Membros:</strong>
            <?php $count = 0; ?>
            <?php foreach ($groupMembers as $member): ?>
                <?php if ($count < 2): ?>
                    <span><?= htmlspecialchars($member['name']) ?></span>
                <?php endif; ?>
                <?php $count++; ?>
            <?php endforeach; ?>
            <?php if ($count > 2): ?>
                <span>...</span>
            <?php endif; ?>
            <button onclick="document.getElementById('groupModal').style.display='block'">Ver Detalhes</button>
        </div>

        <div id="groupModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('groupModal').style.display='none'">×</span>
                <h2>Detalhes do Grupo</h2>
                <p><strong>Nome do Grupo:</strong> <?= htmlspecialchars($groupDetails['group_name']) ?></p>
                <p><strong>Criado por:</strong> <?= htmlspecialchars($groupDetails ['created_by_username']) ?></p>
                <p><strong>Data de Criação:</strong> <?= date('d/m/Y', strtotime($groupDetails['created_at'])) ?></p>
                <p><strong>Número de Membros:</strong> <?= htmlspecialchars($groupDetails['member_count']) ?></p>
                <p><strong>Membros Atuais:</strong></p>
                <ul>
                    <?php foreach ($groupMembers as $member): ?>
                        <li><?= htmlspecialchars($member['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="messages">
        <?php
$lastUserId = null;
foreach ($groupChats as $chat):
    $currentDate = new DateTime($chat['created_at']);
    if (isNewDay($currentDate, $lastDate)) {
        echo '<div class="date-separator">' . strftime('%A, %d de %B de %Y', $currentDate->getTimestamp()) . '</div>';
        $lastDate = $currentDate;
    }
    ?>
    <div class="message <?= $chat['from_id'] == $userId ? 'sent' : 'received' ?>">
        <?php if ($chat['from_id'] != $userId): ?>
            <?php if ($lastUserId != $chat['from_id']): ?>
                <?php if (!empty($chat['p_p'])): ?>
                    <img src="uploads/perfis/<?= htmlspecialchars($chat['p_p']) ?>" alt="Foto de perfil" class="profile-pic">
                <?php endif; ?>
                <span class="user"><?= htmlspecialchars($chat['name']) ?></span>
            <?php endif; ?>
        <?php endif; ?>
        <div class="text"><?= htmlspecialchars($chat['message']) ?></div>
        <span class="time"><?= $currentDate->format('H:i') ?></span>
    </div>
    <?php
    $lastUserId = $chat['from_id'];
endforeach;
?>
</div>
        <form action="" method="post">
    <textarea name="message" placeholder="Digite uma mensagem..."></textarea>
    <button type="submit"><i class="fas fa-paper-plane"></i></button>
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