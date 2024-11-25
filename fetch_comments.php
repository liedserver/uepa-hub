<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];

    // Consulta para buscar os comentários junto com o nome de usuário e foto
    $query = $conn->prepare("
        SELECT comments.comment, comments.created_at, usuarios.nome, usuarios.foto
        FROM comments
        JOIN usuarios ON comments.user_id = usuarios.id
        WHERE comments.post_id = ?
        ORDER BY comments.created_at DESC
    ");
    $query->bind_param("i", $postId);
    $query->execute();
    $result = $query->get_result();

    // Exibir os comentários
    while ($comment = $result->fetch_assoc()) {
        echo '<div class="comment">';
        echo '<img src="uploads/' . htmlspecialchars($comment['foto']) . '" alt="Foto de Perfil" class="comment-profile-pic">';
        echo '<strong>' . htmlspecialchars($comment['nome']) . ':</strong>'; // Mostrando o nome do usuário
        echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
        echo '</div>';
    }
}
?>
