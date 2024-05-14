<?php 
  session_start();

  if (isset($_SESSION['username'])) {

  	include 'app/db.conn.php';

  	include 'app/helpers/user.php';
  	include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

  	$user = getUser($_SESSION['username'], $conn);

  	$conversations = getConversation($user['user_id'], $conn);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat - Inicio</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" 
	      href="css/style.css">
	<link rel="icon" href="img/logo.jpg">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    * {
    transition: all 0.3s ease;
}
	.menu-btn {
    background-color: #007bff; 
    color: white; 
    border: none; 
    padding: 8px 20px;
    border-radius: 5px; 
    font-size: 16px;
    cursor: pointer; 
}

.dropdown-menu {
    display: none;
    position: absolute; 
    top: 100%; 
    right: 0; 
    background-color: white; 
    min-width: 160px; 
    box-shadow: 0px 8px px 0px rgba(0,0,0,0.2);
    z-index: 1; 
}


.dropdown-menu a {
    color: black; 
    padding: 12px 16px; 
    text-decoration: none; 
    display: block; 
}

.dropdown-menu a.excluir-conta {
    background-color: red; 
    color: white; 
}


.show {
    display: block;
}
#chatList {
    max-height: 400px;
    overflow-y: auto; 
}
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000000000px;
}
.input-group .btn {
    position: relative;
    z-index: 1;
}
.modal-content {
    background-color: white;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

label {
    display: block;
    margin-bottom: 10px;
}


textarea,
input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #0d6efd;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}


@media screen and (max-width: 600px) {
    .modal-content {
        max-width: 90%;
    }
}
.profile-image-small{
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
}

.verified-badge {
    display: inline;
    margin-left: 5px; /* Ajuste conforme necessário para aproximar do nome */
}

.ms-2 {
    margin-left: 0.5rem; /* Ajuste para alinhar com o nome */
}

.fs-xs {
    margin-bottom: 0; /* Remove a margem inferior para alinhar com a última mensagem */
}

</style>
</head>

<body 

class="d-flex
             justify-content-center
             align-items-center
             vh-100">
			 
    <div class="p-2 w-400
                rounded shadow">
    	<div>
		<div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
  <div class="d-flex align-items-center">
  <div class="profile-container">
  <div class="profile-image"></div>
<form action="user.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fotoPerfil" id="fotoPerfil" style="display: none;" onchange="form.submit()" required>
    <label for="fotoPerfil" class="btn btn-primary">Mudar Foto</label>
    <input type="hidden" name="user_id" value="<?=$user['user_id']?>">
</form>
</div>


<img src="uploads/perfis/<?= empty($user['p_p']) ? 'user-default.png' : $user['p_p'] ?>" class="w-25 rounded-circle">
<h3 class="fs-xs m-2"><?=$user['name']?></h3>
</div>
  <a href="logout.php" class="btn btn-dark">Sair</a>
</div>

    		<div class="input-group mb-3">
    			<input type="text"
    			       placeholder="Pesquisar..."
    			       id="searchText"
    			       class="form-control">
    			<button class="btn btn-primary" 
    			        id="serachBtn">
    			        <i class="fa fa-search"></i>	
    			</button>       
    		</div>
			
            <ul id="chatList" class="list-group mvh-50 overflow-auto">
    <?php if (!empty($conversations)) { ?>
        <?php foreach ($conversations as $conversation) { ?>
            <li class="list-group-item">
                <a href="chat.php?user=<?=$conversation['username']?>"
                   class="d-flex justify-content-between align-items-center p-2">
                    <div class="d-flex align-items-center">
                        <img src="uploads/perfis/<?=$conversation['p_p']?>"
                             class="w-10 rounded-circle">
                        <div class="ms-2">
                            <h3 class="fs-xs">
                                <?=$conversation['name']?>
                                <?php if ($conversation['user_id'] == 12 || $conversation['verificado'] == 1) { ?>
                                    <span class="verified-badge">✅</span>
                                <?php } ?>
                            </h3>
                            <small>
                                <?php 
                                    echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn);
                                ?>
                            </small>
                        </div>            	
                    </div>
                    <?php if (last_seen($conversation['visto']) == "Active") { ?>
                        <div title="online">
                            <div class="online"></div>
                        </div>
                    <?php } ?>
                </a>
            </li>


    			    <?php } ?>
    			<?php }else{ ?>
    				<div class="alert alert-info 
    				            text-center">
					   <i class="fa fa-comments d-block fs-big"></i>
                       Não ah mensagens, deseja iniciar uma conversa?
					</div>
    			<?php } ?>
    		</ul>
    	</div>
		<div class="container d-flex justify-content-between align-items-center">
    <a href="grupos.php" class="btn btn-primary">
        Ver Grupos
    </a>
    <div class="menu-container" style="position: relative;">
        <button id="menu-btn" class="menu-btn">
            <i class="fas fa-bars"></i> 
        </button>
        <div id="dropdown-menu" class="dropdown-menu">
            <a href="#" class="excluir-conta">Excluir Conta</a>
            <a href="#" class="notificacao">Notificação</a>
            <a href="#" id="openProfileModal">Perfil</a>

            <div id="profileModal" class="modal">
    <div class="modal-content">
        <h2>Perfil do Usuário</h2>
        <p>Foto Perfil: <img src="uploads/perfis/<?=$user['p_p']?>" class="profile-image-small" alt=""></p>
        <p>Nome: <?=$user['name']?></p>
        <p>Nome de Usuário: <?=$user['username']?></p>
        <form action="salvar_perfil.php" method="post">
            <label for="biografia">Biografia:</label>
            <textarea id="biografia" name="biografia" rows="4" cols="50"><?=$user['biography']?></textarea>
            <label for="hobbies">Hobbies:</label>
            <input type="text" id="hobbies" name="hobbies" value="<?=$user['hobbies']?>">
            <label for="profession">Profissão:</label>
            <input type="text" id="profession" name="profession" value="<?=$user['profession']?>">
            <label for="age">Idade:</label>
            <input type="number" id="age" name="age" value="<?=$user['age']?>">
            <button id="saveProfileButton" type="submit">Salvar</button>
        </form>
        <button id="closeProfileModal">Fechar</button>
    </div>
</div>
	  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

function togglePasswordVisibility() {
    const senhaInput = document.getElementById('senha');
    const toggleSenha = document.getElementById('toggleSenha');

    if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        toggleSenha.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
        senhaInput.type = 'password';
        toggleSenha.innerHTML = '<i class="fa fa-eye"></i>';
    }
}
	     document.getElementById('openProfileModal').addEventListener('click', function() {
            document.getElementById('profileModal').style.display = 'block';
        });

        document.getElementById('closeProfileModal').addEventListener('click', function() {
            document.getElementById('profileModal').style.display = 'none';
        });
	document.addEventListener('DOMContentLoaded', function() {
    var excluirContaBtn = document.querySelector('.excluir-conta');
    excluirContaBtn.addEventListener('click', function(event) {
        event.preventDefault();
        if (confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
            window.location.href = 'excluir_conta.php';
        }
    });
});
	$(document).ready(function(){
      
		const menuBtn = document.getElementById('menu-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');

        menuBtn.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

       $("#searchText").on("input", function(){
       	 var searchText = $(this).val();
         if(searchText == "") return;
         $.post('app/ajax/search.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $("#chatList").html(data);
         	   });
       });


       $("#serachBtn").on("click", function(){
       	 var searchText = $("#searchText").val();
         if(searchText == "") return;
         $.post('app/ajax/search.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $("#chatList").html(data);
         	   });
       });


      let lastSeenUpdate = function(){
      	$.get("app/ajax/update_last_seen.php");
      }
      lastSeenUpdate();

      setInterval(lastSeenUpdate, 10000);

    });
    
</script>
</body>
</html>
<?php
  }else{
  	header("Location: index.php");
   	exit;
  }
 ?>