<?php
include 'includes/db.php';

$post_id = $_POST['post_id'];

$query = $conn->prepare("SELECT comments.*, usuarios.matricula FROM comments 
                         JOIN usuarios ON comments.user_id = usuarios.id 
                         WHERE comments.post_id = ? ORDER BY comments.created_at DESC");
$query->bind_param("i", $post_id);
$query->execute();
$result = $query->get_result();

while ($comment = $result->fetch_assoc()) {
    echo '<div class="comment">';
    echo '<strong>' . htmlspecialchars($comment['matricula']) . ':</strong>';
    echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
    echo '</div>';
}
?>
