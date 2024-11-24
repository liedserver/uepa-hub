<?php
session_start();
include 'includes/db.php';
$userId = $_SESSION['user_id'];

$query = $conn->prepare("SELECT id, nome FROM usuarios WHERE id != ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<li data-user-id='{$row['id']}'>{$row['nome']}</li>";
}
?>
