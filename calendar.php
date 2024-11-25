<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Eventos - Uepa Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/calendar.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Container Principal -->
    <div class="container-fluid calendar-container">
        <h2 class="text-center mb-4">Calendário de Eventos</h2>
        <div class="row g-4">
            <?php
            // Consulta para obter eventos ordenados por data
            $events_query = $conn->query("SELECT * FROM eventos ORDER BY data_evento ASC");
            while ($event = $events_query->fetch_assoc()) {
            ?>
                <div class="col-12 col-md-6 col-lg-4 d-flex">
                    <div class="event flex-fill">
                        <h3><?php echo htmlspecialchars($event['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($event['descricao']); ?></p>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($event['data_evento'])); ?></p>
                        <button class="btn btn-primary subscribe-btn" data-event-id="<?php echo $event['id']; ?>">Inscrever-se</button>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
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

    <!-- Scripts -->
    <script>
        $(document).ready(function () {
            // Manipula o clique no botão "Inscrever-se"
            $('.subscribe-btn').click(function () {
                const eventId = $(this).data('event-id');

                // Envia uma requisição AJAX para subscribe_event.php
                $.ajax({
                    url: 'subscribe_event.php',
                    method: 'POST',
                    data: { event_id: eventId },
                    success: function (response) {
                        // Insere a resposta no modal e exibe
                        $('#modalMessage').text(response);
                        const messageModal = new bootstrap.Modal($('#messageModal'));
                        messageModal.show();
                    },
                    error: function () {
                        // Exibe uma mensagem de erro genérica no modal
                        $('#modalMessage').text('Erro ao processar a solicitação.');
                        const messageModal = new bootstrap.Modal($('#messageModal'));
                        messageModal.show();
                    }
                });
            });
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
