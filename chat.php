<?php 
  session_start();
  if (isset($_SESSION['username'])) {
  	include 'app/db.conn.php';
  	include 'app/helpers/user.php';
  	include 'app/helpers/chat.php';
  	include 'app/helpers/opened.php';
  	include 'app/helpers/timeAgo.php';
  	if (!isset($_GET['user'])) {
  		header("Location: home.php");
  		exit;	}
  	$chatWith = getUser($_GET['user'], $conn);
  	if (empty($chatWith)) {
  		header("Location: home.php");
  		exit;
  	}
  	$chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);
  	opened($chatWith['user_id'], $conn, $chats);
      setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
      function isPrimeiraMensagemDoDia($dataAtual, $ultimaData) {
        return $ultimaData === null || $dataAtual->format('Y-m-d') !== $ultimaData->format('Y-m-d');
    }
 
    function formatarData($data) {
        $diasDaSemana = [
            'Sun' => 'dom',
            'Mon' => 'seg',
            'Tue' => 'ter',
            'Wed' => 'qua',
            'Thu' => 'qui',
            'Fri' => 'sex',
            'Sat' => 'sab'
        ];
        $diaSemanaIngles = strftime('%a', $data->getTimestamp());
        $diaSemanaPortugues = $diasDaSemana[$diaSemanaIngles] ?? $diaSemanaIngles;
        return $diaSemanaPortugues . strftime(', %d/%m', $data->getTimestamp());
    }
    
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" 
	      href="css/style.css">
	<link rel="icon" href="img/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
       .icon{
        cursor: pointer;
       }
       
       .azuli{
        color: #0d6efd;
       }
       .message-actions i{
        margin-left: 5px;
        font-size: 18px;
       }
       .data-mensagem {
        text-align: center;
    margin: 20px 0;
    color: #555;
    font-size: 14px;
}

    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 shadow p-4 rounded">
        <a href="home.php" class="fs-4 link-dark">←</a>

        <div class="d-flex align-items-center">
    <img src="uploads/perfis/<?=$chatWith['p_p']?>" class="w-15 rounded-circle">
    <h3 class="display-4 fs-sm m-2">
    <?=$chatWith['name']?>
    <?php if ($chatWith['user_id'] == 12 || $chatWith['verificado'] == 1) { ?>
        <span class="verified-badge">✅</span>
    <?php } ?>
    <br>
        <small class="d-block">
            <?php
            $lastSeenText = last_seen($chatWith['visto']);
            if ($lastSeenText == 'Ativo Agora') {
                echo '<span class="online"></span> Online';
            } else {
                echo 'Visto em: ' . $lastSeenText;
            }
            ?>
        </small>
    </h3>
</div>

        <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
    <?php
    $ultimaData = null;
    foreach ($chats as $chat) {
        $dataAtual = new DateTime($chat['created_at']);
        $horaMensagem = $dataAtual->format('H:i');

        if (isPrimeiraMensagemDoDia($dataAtual, $ultimaData)) {
            $dataFormatada = formatarData($dataAtual);
            echo '<div class="data-mensagem">' . $dataFormatada . '</div>';
            $ultimaData = $dataAtual;
        }

        echo '<p class="' . ($chat['from_id'] == $_SESSION['user_id'] ? 'rtext align-self-end' : 'ltext') . ' border rounded p-2 mb-1">';
        echo htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8');
        if ($chat['from_id'] == $_SESSION['user_id']) {
            echo '<small class="d-block">' . $horaMensagem;
            echo '<span class="azuli message-actions">';
            echo '<i class="fa fa-trash icon" onclick="deleteMessage(' . $chat['chat_id'] . ')" title="Excluir mensagem"></i>';
            echo '<i class="fa fa-pencil icon" onclick="editMessage(' . $chat['chat_id'] . ', \'' . addslashes($chat['message']) . '\')" title="Editar mensagem"></i>';
            echo '</span></small>';
        } else {
            echo '<small class="d-block">' . $horaMensagem . '</small>';
        }
        echo '</p>';
    }
    ?>
</div>
	
<div class="input-group mb-3">
<div class="emoji-menu" style="display:none; position: absolute; bottom: 50px; left: 10px; background-color: rgba(255, 255, 255, 0.9); border-radius: 15px; padding: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); z-index: 1000;">

        <span onclick="inserirEmoji('😀')">😀</span>
        <span onclick="inserirEmoji('😃')">😃</span>
        <span onclick="inserirEmoji('😄')">😄</span>
        <span onclick="inserirEmoji('😁')">😁</span>
        <span onclick="inserirEmoji('😆')">😆</span>
        <span onclick="inserirEmoji('😅')">😅</span>
        <span onclick="inserirEmoji('😂')">😂</span>
        <span onclick="inserirEmoji('🤣')">🤣</span>
        <span onclick="inserirEmoji('😊')">😊</span>
        <span onclick="inserirEmoji('😇')">😇</span>
        <span onclick="inserirEmoji('😞')">😞</span>
        <span onclick="inserirEmoji('😔')">😔</span>
        <span onclick="inserirEmoji('😟')">😟</span>
        <span onclick="inserirEmoji('😕')">😕</span>
        <span onclick="inserirEmoji('🙁')">🙁</span>
        <span onclick="inserirEmoji('☹️')">☹️</span>
        <span onclick="inserirEmoji('😍')">😍</span>
        <span onclick="inserirEmoji('🥰')">🥰</span>
        <span onclick="inserirEmoji('😘')">😘</span>
        <span onclick="inserirEmoji('😗')">😗</span>
        <span onclick="inserirEmoji('😙')">😙</span>
        <span onclick="inserirEmoji('😚')">😚</span>
    </div>
    <textarea cols="3" id="message" class="form-control"></textarea>
    <button onclick="toggleEmojiMenu()" class="btn btn-outline-secondary">
        😊
    </button>
    <button class="btn btn-primary" id="sendBtn">
        <i class="fa fa-paper-plane"></i>
    </button> 
</div>
    </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function deleteMessage(chatId) {
    if (confirm('Tem certeza que deseja excluir esta mensagem?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_message.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200 && this.responseText === 'success') {
                var messageElement = document.querySelector('.send-msg[data-chat-id="' + chatId + '"]');
                if (messageElement) {
                    messageElement.remove(); 
                }
            } else {
                alert('Erro ao excluir a mensagem.');
            }
        };
        xhr.send('chat_id=' + chatId);
    }
}

function editMessage(chatId, message) {
    var newMessage = prompt('Editar mensagem:', message);
    if (newMessage !== null && newMessage !== message) { 
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'edit_message.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200 && this.responseText === 'success') {
                var messageElement = document.querySelector('.send-msg[data-chat-id="' + chatId + '"] .rtext');
                if (messageElement) {
                    messageElement.textContent = newMessage; 
                }
            } else {
                alert('Erro ao editar a mensagem.');
            }
        };
        xhr.send('chat_id=' + chatId + '&message=' + encodeURIComponent(newMessage));
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var icons = document.querySelectorAll('.icon');
    icons.forEach(function(icon) {
        icon.style.cursor = 'pointer';
    });
});
var messageInput = document.getElementById('message');
messageInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('sendBtn').click(); 
    }
});
	var scrollDown = function(){
        let chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
	}
	scrollDown();
	$(document).ready(function(){
      
      $("#sendBtn").on('click', function(){
      	message = $("#message").val();
      	if (message == "") return;

      	$.post("app/ajax/insert.php",
      		   {
      		   	message: message,
      		   	to_id: <?=$chatWith['user_id']?>
      		   },
      		   function(data, status){
                  $("#message").val("");
                  $("#chatBox").append(data);
                  scrollDown();
      		   });
      });

      let lastSeenUpdate = function(){
      	$.get("app/ajax/update_last_seen.php");
      }
      lastSeenUpdate();

      setInterval(lastSeenUpdate, 10000);
      let fechData = function(){
      	$.post("app/ajax/getMessage.php", 
      		   {
      		   	id_2: <?=$chatWith['user_id']?>
      		   },
      		   function(data, status){
                  $("#chatBox").append(data);
                  if (data != "") scrollDown();
      		    });
      }

      fechData();
      setInterval(fechData, 500);
$.post('marcarComoLida.php', { conversationId: idDaConversa }, function(data) {
    alert(data); 
});
    
    });

function toggleEmojiMenu() {
    var emojiMenu = document.querySelector('.emoji-menu');
    emojiMenu.style.display = emojiMenu.style.display == 'none' ? 'block' : 'none';
}

function inserirEmoji(emoji) {
    var messageInput = document.getElementById('message');
    messageInput.value += emoji;
    messageInput.focus(); 
}
function toggleEmojiMenu() {
    var emojiMenu = document.querySelector('.emoji-menu');
    emojiMenu.style.display = emojiMenu.style.display == 'none' ? 'block' : 'none';
}

function inserirEmoji(emoji) {
    var messageInput = document.getElementById('message');
    messageInput.value += emoji; 
    messageInput.focus(); 
}

const inputField = document.getElementById('inputField');
const typingIndicator = document.getElementById('typingIndicator');
let typingTimeout;


const socket = new WebSocket('ws://seu-servidor.com');

inputField.addEventListener('input', () => {

    clearTimeout(typingTimeout);


    socket.send(JSON.stringify({ typing: true }));

 
    typingTimeout = setTimeout(() => {
        socket.send(JSON.stringify({ typing: false }));
    }, 3000); 
});

const WebSocketServer = require('ws').Server;
const wss = new WebSocketServer({ port: 8080 });

wss.on('connection', (ws) => {
    ws.on('message', (message) => {
        const data = JSON.parse(message);
  
        wss.clients.forEach((client) => {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });
});

socket.onmessage = (event) => {
    const data = JSON.parse(event.data);
    if (data.typing) {
        typingIndicator.textContent = 'Alguém está digitando...';
    } else {
        typingIndicator.textContent = '';
    }
};
</script>
 </body>
 </html>
<?php
  }else{
  	header("Location: index.php");
   	exit;
  }
 ?>