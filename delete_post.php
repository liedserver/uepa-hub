<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $postId = $data['post_id'];
    $userId = $_SESSION['user_id'];
    $isAdmin = $_SESSION['is_admin'] ?? false;

    // Verifica se o usuário tem permissão para excluir
    $query = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $query->bind_param("i", $postId);
    $query->execute();
    $result = $query->get_result()->fetch_assoc();

    if ($result && ($result['user_id'] == $userId || $isAdmin)) {
        // Exclui likes relacionados
        $deleteLikes = $conn->prepare("DELETE FROM likes WHERE post_id = ?");
        $deleteLikes->bind_param("i", $postId);
        $deleteLikes->execute();

        // Exclui comentários relacionados
        $deleteComments = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $deleteComments->bind_param("i", $postId);
        $deleteComments->execute();

        // Exclui o post
        $deletePost = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $deletePost->bind_param("i", $postId);
        if ($deletePost->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'unauthorized';
    }
}
?>
