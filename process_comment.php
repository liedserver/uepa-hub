<?php
session_start();
include 'includes/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $comment = trim($_POST['comment']);
    $userId = $_SESSION['user_id'];

    if (!empty($comment)) {
        $query = $conn->prepare("INSERT INTO comments (post_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        $query->bind_param("iis", $postId, $userId, $comment);

        if ($query->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "empty";
    }
}
?>
