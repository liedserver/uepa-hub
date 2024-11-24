<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Usuário não autorizado.";
    exit;
}

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo "ID do usuário não especificado.";
    exit;
}

$currentUserId = $_SESSION['user_id'];
$chatUserId = $_GET['user_id'];

// Consulta as mensagens entre os dois usuários
$query = $conn->prepare("
    SELECT 
        messages.*, 
        sender.id AS sender_id, 
        sender.foto AS sender_foto, 
        receiver.id AS receiver_id, 
        receiver.foto AS receiver_foto 
    FROM messages
    JOIN usuarios AS sender ON messages.sender_id = sender.id
    JOIN usuarios AS receiver ON messages.receiver_id = receiver.id
    WHERE 
        (messages.sender_id = ? AND messages.receiver_id = ?)
        OR (messages.sender_id = ? AND messages.receiver_id = ?)
    ORDER BY messages.created_at ASC
");
$query->bind_param("iiii", $currentUserId, $chatUserId, $chatUserId, $currentUserId);
$query->execute();
$result = $query->get_result();

$output = '';

// Gera o HTML das mensagens
while ($message = $result->fetch_assoc()) {
    $isSender = $message['sender_id'] == $currentUserId; // Verifica se é o usuário logado
    $messageClass = $isSender ? 'sent' : 'received';

    // Determina a foto correta com base no remetente (eu) ou destinatário (outro usuário)
    $photoPath = $isSender ? $message['sender_foto'] : $message['receiver_foto'];
    $photoUrl = (!empty($photoPath) && file_exists("uploads/" . $photoPath))
        ? "uploads/" . htmlspecialchars($photoPath)
        : "assets/img/default.png";

    // Gera a estrutura HTML da mensagem
    $output .= '<div class="message ' . $messageClass . '">';
    if (!$isSender) {
        // Exibe a foto do outro usuário para mensagens recebidas
        $output .= '<img src="' . $photoUrl . '" alt="Foto de Perfil" class="profile-pic">';
    }
    $output .= '<div class="text">' . htmlspecialchars($message['content']) . '</div>';
    if ($isSender) {
        // Exibe a sua própria foto para mensagens enviadas
        $output .= '<img src="' . $photoUrl . '" alt="Foto de Perfil" class="profile-pic">';
    }
    $output .= '</div>';
}

echo $output;
?>
