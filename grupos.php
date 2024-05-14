<?php
session_start();
include 'app/db.conn.php';
include 'app/helpers/user.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
function criarGrupo($nomeDoGrupo, $userId, $conn) {
    $stmt = $conn->prepare("INSERT INTO groups (group_name, created_by) VALUES (?, ?)");
    $stmt->execute([$nomeDoGrupo, $userId]);
    return $conn->lastInsertId();
}

function adicionarMembroAoGrupo($groupId, $userId, $conn) {
    $stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
    $stmt->execute([$groupId, $userId]);
}

function isUserMemberOfGroup($groupId, $userId, $conn) {
    $stmt = $conn->prepare("SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ?");
    $stmt->execute([$groupId, $userId]);
    return $stmt->fetchColumn();
}

function sairDoGrupo($groupId, $userId, $conn) {
    if (isUserMemberOfGroup($groupId, $userId, $conn)) {
        $stmt = $conn->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
        $stmt->execute([$groupId, $userId]);
        return true;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nomeDoGrupo'])) {
    $nomeDoGrupo = $_POST['nomeDoGrupo'];

    // Verifique se o nome do grupo tem 30 caracteres ou menos
    if (strlen($nomeDoGrupo) > 30) {
        $_SESSION['error'] = "O nome do grupo deve ter no máximo 30 caracteres.";
        header('Location: grupos.php');
        exit;
    }
     else {
        // Se o nome do grupo estiver dentro do limite, prossiga com a criação e adição do membro
        $groupId = criarGrupo($nomeDoGrupo, $userId, $conn);
        adicionarMembroAoGrupo($groupId, $userId, $conn);
        header('Location: chat_group.php?group_id=' . $groupId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['sair_do_grupo'])) {
    $groupId = $_GET['sair_do_grupo'];
    if (sairDoGrupo($groupId, $userId, $conn)) {
        header('Location: grupos.php');
        exit;
    } else {
        echo "Erro ao sair do grupo.";
    }
}

$grupos = $conn->query("SELECT g.*, gm.user_id FROM groups g LEFT JOIN group_members gm ON g.group_id = gm.group_id WHERE gm.user_id = $userId");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seus Grupos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
    transition: all 0.3s ease;
}


    .search-box-overlay {
        position: fixed; /* Posição fixa na tela */
        top: 50%; /* Centraliza verticalmente */
        left: 50%; /* Centraliza horizontalmente */
        transform: translate(-50%, -50%); /* Ajuste fino da centralização */
        z-index: 1000; /* Garante que fique sobre os outros elementos */
        background-color: #fff; /* Fundo branco */
        padding: 20px; /* Espaçamento interno */
        border-radius: 10px; /* Bordas arredondadas */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
    }
    .close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    border: none;
    background: none;
    font-size: 20px;
    cursor: pointer;
}

.search-box-overlay {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Adicione um espaço para o botão de fechamento */
    padding-top: 40px;
}
.btn-secondary.dropdown-toggle {
    color: #0d6efd;
    background-color: #dfdfdf; /* Mantém o fundo transparente */
    border: none; /* Remove a borda, se houver */

    
}

/* Estilo para o botão de fechamento do modal de pesquisa */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    border: none;
    background: none;
    font-size: 20px;
    cursor: pointer;
    color: #dfdfdf; /* Cor do "X" */
}
.btn-danger, .dropdown-item[href*="sair_do_grupo"] {
    background-color: red;
    color: white;
    border-radius: 25px;
    border-bottom: none;
}
.d-flex {
    position: relative; /* Posicionamento relativo */
}

/* Estilo para o contêiner do dropdown */
.dropdown {
    position: relative;
}

/* Estilo para o menu dropdown */
.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    z-index: 1000px; /* Aumenta o z-index para garantir sobreposição */
    background-color: #fff; /* Define a cor de fundo para branco */
    border-radius: 25px;
}

/* Estilo para o botão de três pontos verticais */
.btn-secondary.dropdown-toggle {
    color: #0d6efd; /* Cor azul para os três pontos */
    background-color: transparent;
    border: none;
    position: relative;
    z-index: 100px; /* Aumenta o z-index para ficar acima do menu */
    border-radius: 25px;
}
/* Estilo para o contêiner da lista de grupos */
.list-group {
    min-height: 300px; /* Define uma altura mínima */
    max-height: 300px; /* Define uma altura mínima */
    overflow: auto; /* Mantém a rolagem se necessário */
    border-radius: 25px; /* Bordas mais arredondadas para cada item da lista */
    margin-bottom: 10px; /* Espaçamento entre os itens da lista */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra suave para cada item da lista */
}
.btn {
    border-radius: 25px;
}
.form-control{
    border-radius: 25px;
}
   /* Estilos anteriores */

      /* Estilos anteriores */

        /* Estilo para o título 'Seus Grupos' */
        .title-container {
            background-color: #fff; /* Cor azul para destaque */
            color: #007bff; /* Texto branco para contraste */
            border-radius: 30px; /* Bordas arredondadas */
            text-align: center; /* Centraliza o texto */
            padding: 1px 0; /* Espaçamento vertical */
            margin-bottom: 1px; /* Reduz a margem inferior */
            display: inline-block; /* Permite o arredondamento das bordas */
            width: auto; /* Largura automática baseada no conteúdo */
            font-size: 1.5rem; /* Tamanho do texto reduzido */
        }

             /* Estilo para o contêiner da lupa e do campo de entrada */
             .search-group-container {
            display: flex; /* Alinha os itens horizontalmente */
            align-items: center; /* Centraliza verticalmente */
            justify-content: space-between; /* Espaçamento entre os itens */
            background-color: #007bff; /* Cor azul para destaque */
            color: white; /* Texto branco para contraste */
            border-radius: 25px; /* Bordas arredondadas */
            padding: 10px; /* Espaçamento interno */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Sombra para efeito 3D */
            cursor: pointer; /* Muda o cursor para indicar clicabilidade */
            width: 100%; /* Largura total */
        }

            /* Estilos anteriores */

        /* Estilo para o contêiner da lupa e do campo de entrada */
        .search-group-container {
            display: flex; /* Alinha os itens horizontalmente */
            align-items: center; /* Centraliza verticalmente */
            justify-content: start; /* Alinha os itens ao início */
            background-color: #007bff; /* Cor azul para destaque */
            color: white; /* Texto branco para contraste */
            border-radius: 25px; /* Bordas arredondadas */
            padding: 10px; /* Espaçamento interno */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Sombra para efeito 3D */
            cursor: pointer; /* Muda o cursor para indicar clicabilidade */
            width: 100%; /* Largura total */
        }

        .search-group-container i {
            margin-right: 30px; /* Espaçamento de 30px à direita do ícone */
        }

        /* Estilo para o campo de nome do grupo oculto */
        .hidden-group-name {
            display: none; /* Oculta inicialmente */
            flex-grow: 1; /* Ocupa o espaço disponível */
        }

        /* Estilo para o botão 'Criar' oculto */
        .hidden-create-button {
            display: none; /* Oculta inicialmente */
        }

        /* Estilos adicionais conforme necessário */
</style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="p-2 w-400 rounded shadow">
        <div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center ">
        <div class="title-container">
            <h3>Seus Grupos</h3>
        </div>
            <a href="logout.php" class="btn btn-dark">Sair</a>
        </div>
        <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<form action="grupos.php" method="post" class="mb-3">
            <div class="search-group-container" onclick="showGroupNameInput()">
                <i id="search-icon" class="fas fa-search"></i>
                <span id="create-group-text"> Criar Grupo</span>
                <input id="group-name-input" type="text" name="nomeDoGrupo" placeholder="Nome do Grupo" class="form-control hidden-group-name" required>
                <button id="create-button" type="submit" class="btn btn-primary hidden-create-button">
                    Criar
                </button>
            </div>
        </form>
        
        <ul class="list-group mvh-50 overflow-auto">
    <?php foreach ($grupos as $grupo): ?>
        <li class="list-group-item">
            <!-- Adiciona um evento de clique que redireciona para o chat do grupo -->
            <div onclick="window.location='chat_group.php?group_id=<?= $grupo['group_id'] ?>';" class="d-flex justify-content-between align-items-center p-2">
                <h3 class="fs-xs m-2"><?= $grupo['group_name'] ?></h3>
                <div class="dropdown">
                    <!-- Botão de três pontos verticais que abre o dropdown -->
                    <a href="javascript:void(0);" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation();">
                        ⋮
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="toggleSearchBox(<?= $grupo['group_id'] ?>); event.stopPropagation();">Adicionar</a></li>
                        <li><a class="dropdown-item" href="?sair_do_grupo=<?= $grupo['group_id'] ?>" onclick="event.stopPropagation();">Sair</a></li>
                    </ul>
                </div>
            </div>
            <div id="searchBox-<?= $grupo['group_id'] ?>" class="search-box-overlay" style="display:none;" onclick="event.stopPropagation();">
    <!-- Botão 'X' para fechar o modal -->
    <button type="button" class="close-btn" onclick="closeSearchBox(<?= $grupo['group_id'] ?>);">×</button>
    <input type="text" id="searchText-<?= $grupo['group_id'] ?>" class="form-control" placeholder="Pesquisar membro..." onkeyup="searchMember(<?= $grupo['group_id'] ?>);">
    <div id="memberResults-<?= $grupo['group_id'] ?>"></div>
    <button type="button" class="btn btn-primary" onclick="addMemberToGroup(<?= $grupo['group_id'] ?>); event.stopPropagation();">Adicionar</button>
</div>
        </li>
    <?php endforeach; ?>
</ul>
<a href="home.php" class="btn btn-primary mt-3 w-100">Início</a>
    <script>
     // Função para mostrar o campo de nome do grupo e o botão 'Criar'
     function showGroupNameInput() {
            var searchIcon = document.getElementById('search-icon');
            var groupNameInput = document.getElementById('group-name-input');
            var createButton = document.getElementById('create-button');
            var createGroupText = document.getElementById('create-group-text');

            // Esconde o texto e mostra o campo de entrada e o botão 'Criar'
            createGroupText.style.display = 'none';
            searchIcon.style.display = 'block'; /* Mantém a lupa visível */
            groupNameInput.style.display = 'block';
            createButton.style.display = 'block';
        }

        // Função para reverter ao estado inicial se clicar fora da área
        window.addEventListener('click', function(event) {
            var searchGroupContainer = document.getElementById('search-group-container');
            if (!searchGroupContainer.contains(event.target)) {
                var searchIcon = document.getElementById('search-icon');
                var groupNameInput = document.getElementById('group-name-input');
                var createButton = document.getElementById('create-button');
                var createGroupText = document.getElementById('create-group-text');

                // Reverte ao estado inicial
                createGroupText.style.display = 'block';
                searchIcon.style.display = 'none';
                groupNameInput.style.display = 'none';
                createButton.style.display = 'none';
                groupNameInput.value = ''; // Limpa o campo de entrada
            }
        });
        function closeSearchBox(groupId) {
    var searchBox = document.getElementById('searchBox-' + groupId);
    if (searchBox) {
        searchBox.style.display = 'none';
    }
}
            function toggleSearchBox(groupId) {
        var searchBox = document.getElementById('searchBox-' + groupId);
        if (searchBox.style.display === 'none') {
            searchBox.style.display = 'block';
        } else {
            searchBox.style.display = 'none';
        }
    }
  function selectMember(userId, userName) {
    selectedUserId = userId;
    selectedUserName = userName;
    console.log('Usuário selecionado: ' + userName);

}

function toggleSearch(groupId) {
    var searchBox = document.getElementById('searchBox-' + groupId);
    if (searchBox) {
        searchBox.style.display = searchBox.style.display === 'none' ? 'block' : 'none';
    } else {
        console.error('Caixa de pesquisa para o grupo ' + groupId + ' não encontrada!');
    }
}


function searchMember(groupId) {
    var input = document.getElementById('searchText-' + groupId).value;
    if(input.trim() === '') {
        document.getElementById('memberResults-' + groupId).innerHTML = '';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'searchMember.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById('memberResults-' + groupId).innerHTML = this.responseText;
        }
    };
    xhr.send('search=' + encodeURIComponent(input) + '&groupId=' + encodeURIComponent(groupId));
}


var selectedUserId = null;
var selectedUserName = '';


function selectMember(userId, userName) {
    selectedUserId = userId;
    selectedUserName = userName;
    console.log('Usuário selecionado: ' + userName);
}


function addMemberToGroup(groupId) {
    if(selectedUserId === null) {
        console.error('Nenhum usuário selecionado para adicionar ao grupo.');
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'addToGroup.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        console.log('Resposta do servidor:', this.responseText);
        var response = JSON.parse(this.responseText);
        if(response.success) {
            console.log('Membro adicionado ao grupo: ' + selectedUserName);

            selectedUserId = null;
            selectedUserName = '';
            toggleSearch(groupId); 
        } else {
            console.error('Erro ao adicionar membro: ' + response.error_message);
        }
    };
    xhr.onerror = function() {
        console.error('Erro na comunicação com o servidor.');
    };
    xhr.send('userId=' + encodeURIComponent(selectedUserId) + '&groupId=' + encodeURIComponent(groupId));
}


document.addEventListener('DOMContentLoaded', function() {

    var addIcons = document.querySelectorAll('.add-icon');
    addIcons.forEach(function(icon) {
        var groupId = icon.getAttribute('data-group-id');
        icon.addEventListener('click', function() { toggleSearch(groupId); });
    });
});
    </script>
</body>
</html>