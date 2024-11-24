<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Verificar se o usuário é um administrador
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Acesso negado. Apenas administradores podem criar eventos.";
    exit;
}

include 'includes/db.php';
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="assets/css/create_event.css">
<div class="container">
    <h2>Criar Novo Evento</h2>
    <form action="process_create_event.php" method="POST">
        <label for="titulo">Título do Evento:</label>
        <input type="text" id="titulo" name="titulo" required>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>
        <label for="data_evento">Data e Hora:</label>
        <input type="datetime-local" id="data_evento" name="data_evento" required>
        <button type="submit">Criar Evento</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>

