<?php
session_start();
include 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado.");
}

$comment_id = $_POST['comment_id'];
$user_id = $_SESSION['user_id'];

// Verifica se o comentário pertence ao usuário
$query = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
$query->bind_param("ii", $comment_id, $user_id);

if ($query->execute()) {
    echo "success";
} else {
    echo "Erro ao excluir comentário.";
}

$conn->close();
?>
