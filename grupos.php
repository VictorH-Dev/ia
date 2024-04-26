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
    $groupId = criarGrupo($nomeDoGrupo, $userId, $conn);
    adicionarMembroAoGrupo($groupId, $userId, $conn);
    header('Location: chat_group.php?group_id=' . $groupId);
    exit;
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
<html lang="pt">
<head>
    <title>Seus Grupos</title>
    <link rel="icon" href="img/logo.jpg">
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
}

.container {
    width: 80%;
    margin: auto;
    overflow: hidden;
    padding: 20px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
}

input[type="text"] {
    width: 94%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #5cb85c;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #4cae4c;
}

.grupo {
    background: #fff;
    padding: 20px;
    margin-bottom: 10px;
    border-radius: 8px;
}

.grupo h2 {
    margin-bottom: 10px;
}

.grupo a {
    text-decoration: none;
    color: #333;
    background: #d9edf7;
    padding: 5px 10px;
    border-radius: 4px;
}

.grupo a:hover {
    background: #c4e3f3;
}
.member-result {
    cursor: pointer;
    color: blue; 
    text-decoration: underline; 
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Seus Grupos</h1>
        <form action="grupos.php" method="post">
            <input type="text" name="nomeDoGrupo" placeholder="Nome do Grupo" required>
            <button type="submit">Criar Grupo</button>
        </form>
        <?php foreach ($grupos as $grupo): ?>
            <div class="grupo">
                <h2><?= $grupo['group_name'] ?></h2>
                <a href="chat_group.php?group_id=<?= $grupo['group_id'] ?>">Entrar no Chat do Grupo</a>
<a href="?sair_do_grupo=<?= $grupo['group_id'] ?>" class="sair-do-grupo" title="Sair do Grupo">🚪 Sair</a>
<a href="javascript:void(0);" class="add-icon" data-group-id="<?= $grupo['group_id'] ?>">➕</a>
<div id="searchBox-<?= $grupo['group_id'] ?>" style="display:none;">
    <input type="text" id="searchText-<?= $grupo['group_id'] ?>" placeholder="Pesquisar membro..." onkeyup="searchMember(<?= $grupo['group_id'] ?>)">
    <div id="memberResults-<?= $grupo['group_id'] ?>"></div>
    <button type="button" onclick="addMemberToGroup(<?= $grupo['group_id'] ?>)">Adicionar</button>
</div>
<?php endforeach; ?>

            <a href="home.php" class="button">Inicio</a>
    
</div>

<script>
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