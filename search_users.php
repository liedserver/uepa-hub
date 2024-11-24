<?php
session_start();
include 'includes/db.php';

$user_id = $_SESSION['user_id'];
$query = $_POST['query'] ?? '';

// Busca usuários cujo nome contém o texto digitado
$searchQuery = $conn->prepare("
    SELECT id, nome, foto_perfil 
    FROM users
    WHERE nome LIKE CONCAT('%', ?, '%') AND id != ?
    LIMIT 10
");
$searchQuery->bind_param("si", $query, $user_id);
$searchQuery->execute();
$results = $searchQuery->get_result();

while ($row = $results->fetch_assoc()) {
    echo '<li data-id="' . htmlspecialchars($row['id']) . '">';
    echo '<img src="uploads/' . htmlspecialchars($row['foto_perfil']) . '" alt="Foto">';
    echo htmlspecialchars($row['nome']);
    echo '</li>';
}
?>
