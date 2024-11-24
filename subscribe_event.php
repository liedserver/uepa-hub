<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    // Verifica se o usuário já está inscrito no evento
    $check_query = $conn->prepare("SELECT * FROM inscricoes WHERE event_id = ? AND user_id = ?");
    $check_query->bind_param("ii", $event_id, $user_id);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        echo "Você já está inscrito neste evento.";
    } else {
        // Inscreve o usuário no evento
        $insert_query = $conn->prepare("INSERT INTO inscricoes (event_id, user_id) VALUES (?, ?)");
        $insert_query->bind_param("ii", $event_id, $user_id);

        if ($insert_query->execute()) {
            echo "Inscrição realizada com sucesso!";
        } else {
            echo "Erro ao inscrever-se no evento.";
        }
    }

    $check_query->close();
    $conn->close();
}
?>
