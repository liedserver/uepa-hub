<?php
session_start();
include 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado.");
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];

// Insere o comentário
$query = $conn->prepare("INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)");
$query->bind_param("iis", $user_id, $post_id, $comment);

if ($query->execute()) {
    echo "success";
} else {
    echo "Erro ao adicionar comentário.";
}

$conn->close();
?>
