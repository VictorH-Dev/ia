<?php
function isUserInGroup($userId, $groupId, $conn) {
    $stmt = $conn->prepare("SELECT 1 FROM group_members WHERE user_id = ? AND group_id = ?");
    $stmt->execute([$userId, $groupId]);
    return $stmt->fetchColumn() !== false;
}
?>