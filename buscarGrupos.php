<?php
session_start();
include 'app/db.conn.php';
header('Content-Type: application/json');

function buscarGrupos($userId, $conn) {
    try {
        $stmt = $conn->prepare("SELECT group_id, group_name FROM groups WHERE created_by = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; 
    }
}


echo json_encode(buscarGrupos($_SESSION['user_id'], $conn));
?>