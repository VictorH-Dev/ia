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
    			       placeholder="Search..."
    			       id="searchText"
    			       class="form-control">
    			<button class="btn btn-primary" 
    			        id="serachBtn">
    			        <i class="fa fa-search"></i>	
    			</button>       
    		</div>
			
    		<ul id="chatList"
    		    class="list-group mvh-50 overflow-auto">
    			<?php if (!empty($conversations)) { ?>
    			    <?php 

    			    foreach ($conversations as $conversation){ ?>
	    			<li class="list-group-item">
	    				<a href="chat.php?user=<?=$conversation['username']?>"
	    				   class="d-flex
	    				          justify-content-between
	    				          align-items-center p-2">
	    					<div class="d-flex
	    					            align-items-center">
	    					    <img src="uploads/perfis/<?=$conversation['p_p']?>"
	    					         class="w-10 rounded-circle">
	    					    <h3 class="fs-xs m-2">
	    					    	<?=$conversation['name']?><br>
                      <small>
                        <?php 
                          echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn);
                        ?>
                      </small>
	    					    </h3>            	
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
        </div>
    </div>
</div>
	  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
	
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