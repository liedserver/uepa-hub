<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    exit('Erro na solicitação.');
}

$currentUserId = $_SESSION['user_id'];
$chatUserId = $_GET['user_id'];

// Consulta para obter mensagens entre os dois usuários
$query = $conn->prepare("
    SELECT m.sender_id, m.receiver_id, m.content, m.created_at, u.foto 
    FROM messages m
    JOIN usuarios u ON u.id = m.sender_id
    WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.created_at ASC
");
$query->bind_param("iiii", $currentUserId, $chatUserId, $chatUserId, $currentUserId);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $isSender = ($row['sender_id'] == $currentUserId);
        $class = $isSender ? 'sent' : 'received';
        $foto = ($isSender) ? '' : "<img src='uploads/" . htmlspecialchars($row['foto']) . "' alt='Foto' class='user-photo'>";

        echo "<div class='message {$class}'>";
        echo $foto;
        echo "<div class='text'>" . htmlspecialchars($row['content']) . "</div>";
        echo "<span class='timestamp'>" . date("d/m, H:i", strtotime($row['created_at'])) . "</span>";
        echo "</div>";
    }
} else {
    echo "<p>Nenhuma mensagem encontrada.</p>";
}
?>
