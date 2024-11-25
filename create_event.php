<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Verificar se o usuário é um administrador
$is_admin = $_SESSION['is_admin'] ?? false;
if (!$is_admin) {
    echo "<div class='alert alert-danger text-center mt-3'>Acesso negado. Apenas administradores podem criar eventos.</div>";
    exit;
}

include 'includes/db.php';
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento - Uepa Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/create_event.css">
</head>
<body>
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="text-primary">Criar Novo Evento</h2>
    </div>
    <form action="process_create_event.php" method="POST">
        <div class="mb-4">
            <label for="titulo" class="form-label fw-bold">Título do Evento:</label>
            <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Digite o título do evento" required>
        </div>
        <div class="mb-4">
            <label for="descricao" class="form-label fw-bold">Descrição:</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="5" placeholder="Descreva o evento com detalhes" required></textarea>
        </div>
        <div class="mb-4">
            <label for="data_evento" class="form-label fw-bold">Data e Hora:</label>
            <input type="datetime-local" id="data_evento" name="data_evento" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5">Criar Evento</button>
        </div>
    </form>
</div>



    <!-- Modal de Mensagem -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Mensagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                    <!-- Mensagem será carregada aqui -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            <?php if (!empty($_SESSION['modal_message'])): ?>
                $('#modalMessage').text('<?php echo $_SESSION['modal_message']; ?>');
                const messageModal = new bootstrap.Modal($('#messageModal'));
                messageModal.show();
                <?php unset($_SESSION['modal_message']); ?>
            <?php endif; ?>
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
