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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" 
	      href="css/style.css">
	<link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
       .icon{
        cursor: pointer;
       }
      
    </style>
</head>
<body class="d-flex
             justify-content-center
             align-items-center
             vh-100">
    <div class="w-400 shadow p-4 rounded">
    	<a href="home.php"
    	   class="fs-4 link-dark">&#8592;</a>

           <div class="d-flex align-items-center">
    <img src="uploads/perfis/<?=$chatWith['p_p']?>" class="w-15 rounded-circle">
    <h3 class="display-4 fs-sm m-2">
        <?=$chatWith['name']?><br>
        <div class="d-flex align-items-center" title="online">
            <?php
            $lastSeenText = last_seen($chatWith['visto']);
            if ($lastSeenText == 'Ativo Agora') {
            ?>
                <div class="online"></div>
                <small class="d-block p-1">Online</small>
            <?php } else { ?>
                <small class="d-block p-1">
                    Visto em: <?=$lastSeenText?>
                </small>
            <?php } ?>
            <div class="d-flex align-items-center">



        </div>
    </h3>
</div>
<div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
    <?php 
    if (!empty($chats)) {
        foreach($chats as $chat){
            if($chat['from_id'] == $_SESSION['user_id']) { ?>
                <p class="rtext align-self-end border rounded p-2 mb-1">
                    <?=htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8')?>
                    <small class="d-block">
                        <?=date('d/m/Y H:i', strtotime($chat['created_at']))?>
                    </small>
                    <!-- Ícones de ação -->
                    <span class="message-actions">
                        <!-- Ícone de lixeira para excluir a mensagem -->
                        <i class="fa fa-trash icon" onclick="deleteMessage(<?=$chat['chat_id']?>)" title="Excluir mensagem"></i>
                        <!-- Ícone de lápis para editar a mensagem -->
                        <i class="fa fa-pencil icon" onclick="editMessage(<?=$chat['chat_id']?>, '<?=addslashes($chat['message'])?>')" title="Editar mensagem"></i>
                    </span>
                </p>
            <?php } else { ?>
                <p class="ltext border rounded p-2 mb-1">
                    <?=htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8')?>
                    <small class="d-block">
                        <?=date('d/m/Y H:i', strtotime($chat['created_at']))?>
                    </small>      
                </p>
            <?php }
        }
    } else { ?>
        <div class="alert alert-info text-center">
            <i class="fa fa-comments d-block fs-big"></i>
            Não existe mensagem nessa conversa!
        </div>
    <?php } ?>
</div>
		  <!-- HTML -->
<div class="input-group mb-3">
<div class="emoji-menu" style="display:none; position: absolute; bottom: 50px; left: 10px; background-color: rgba(255, 255, 255, 0.9); border-radius: 15px; padding: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); z-index: 1000;">
        <!-- Emojis de sorriso -->
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
        <!-- ... Continue adicionando emojis conforme desejado -->
        <!-- Emojis de tristeza -->
        <span onclick="inserirEmoji('😞')">😞</span>
        <span onclick="inserirEmoji('😔')">😔</span>
        <span onclick="inserirEmoji('😟')">😟</span>
        <span onclick="inserirEmoji('😕')">😕</span>
        <span onclick="inserirEmoji('🙁')">🙁</span>
        <span onclick="inserirEmoji('☹️')">☹️</span>
        <!-- ... -->
        <!-- Emojis de amor -->
        <span onclick="inserirEmoji('😍')">😍</span>
        <span onclick="inserirEmoji('🥰')">🥰</span>
        <span onclick="inserirEmoji('😘')">😘</span>
        <span onclick="inserirEmoji('😗')">😗</span>
        <span onclick="inserirEmoji('😙')">😙</span>
        <span onclick="inserirEmoji('😚')">😚</span>
        <!-- ... -->
        <!-- Outros emojis podem ser adicionados aqui, categorizados conforme desejado -->
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
// Função para excluir uma mensagem
function deleteMessage(chatId) {
    if (confirm('Tem certeza que deseja excluir esta mensagem?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_message.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200 && this.responseText === 'success') {
                var messageElement = document.querySelector('.send-msg[data-chat-id="' + chatId + '"]');
                if (messageElement) {
                    messageElement.remove(); // Remove a mensagem do DOM
                }
            } else {
                alert('Erro ao excluir a mensagem.');
            }
        };
        xhr.send('chat_id=' + chatId);
    }
}

// Função para editar uma mensagem
function editMessage(chatId, message) {
    var newMessage = prompt('Editar mensagem:', message);
    if (newMessage !== null && newMessage !== message) { // Verifica se a mensagem foi alterada
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'edit_message.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200 && this.responseText === 'success') {
                var messageElement = document.querySelector('.send-msg[data-chat-id="' + chatId + '"] .rtext');
                if (messageElement) {
                    messageElement.textContent = newMessage; // Atualiza a mensagem no DOM
                }
            } else {
                alert('Erro ao editar a mensagem.');
            }
        };
        xhr.send('chat_id=' + chatId + '&message=' + encodeURIComponent(newMessage));
    }
}

// Adicionando evento para mudar o cursor para 'pointer' nos ícones
document.addEventListener('DOMContentLoaded', function() {
    var icons = document.querySelectorAll('.icon');
    icons.forEach(function(icon) {
        icon.style.cursor = 'pointer';
    });
});
var messageInput = document.getElementById('message');
messageInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault(); // Impede a quebra de linha no campo de texto
        document.getElementById('sendBtn').click(); // Simula um clique no botão de enviar
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
    alert(data); // Alerta com a resposta do servidor
});
    
    });
	// JavaScript
function toggleEmojiMenu() {
    var emojiMenu = document.querySelector('.emoji-menu');
    emojiMenu.style.display = emojiMenu.style.display == 'none' ? 'block' : 'none';
}

function inserirEmoji(emoji) {
    var messageInput = document.getElementById('message');
    messageInput.value += emoji; // Adiciona o emoji no final do texto
    messageInput.focus(); // Mantém o foco na caixa de texto
}// JavaScript
function toggleEmojiMenu() {
    var emojiMenu = document.querySelector('.emoji-menu');
    emojiMenu.style.display = emojiMenu.style.display == 'none' ? 'block' : 'none';
}

function inserirEmoji(emoji) {
    var messageInput = document.getElementById('message');
    messageInput.value += emoji; // Adiciona o emoji no final do texto
    messageInput.focus(); // Mantém o foco na caixa de texto
}
// Front-end: Detectar digitação e enviar sinal de digitação
const inputField = document.getElementById('inputField');
const typingIndicator = document.getElementById('typingIndicator');
let typingTimeout;

// Configurar a conexão WebSocket
const socket = new WebSocket('ws://seu-servidor.com');

inputField.addEventListener('input', () => {
    // Limpar o timeout anterior se houver
    clearTimeout(typingTimeout);

    // Enviar sinal de "digitando" para o servidor
    socket.send(JSON.stringify({ typing: true }));

    // Configurar um timeout para parar de mostrar "digitando" após um tempo sem digitação
    typingTimeout = setTimeout(() => {
        socket.send(JSON.stringify({ typing: false }));
    }, 3000); // 3 segundos de intervalo
});

// Back-end: Receber e transmitir sinal de digitação (usando Node.js e WebSocket)
const WebSocketServer = require('ws').Server;
const wss = new WebSocketServer({ port: 8080 });

wss.on('connection', (ws) => {
    ws.on('message', (message) => {
        const data = JSON.parse(message);
        // Transmitir para todos os usuários exceto o que está digitando
        wss.clients.forEach((client) => {
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });
});

// Front-end: Receber notificação de digitação e exibir
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