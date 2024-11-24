<?php
session_start();
include 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado.");
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// Verifica se o usuário já curtiu o post
$query = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
$query->bind_param("ii", $user_id, $post_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // Remove a curtida
    $delete = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
    $delete->bind_param("ii", $user_id, $post_id);
    $delete->execute();
    echo "unliked";
} else {
    // Adiciona a curtida
    $insert = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $post_id);
    $insert->execute();
    echo "liked";
}

$conn->close();
?>
