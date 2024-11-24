<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include 'includes/db.php';
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="assets/css/messages.css">

<div class="messages-container">
    <div class="sidebar">
        <h2>Contatos</h2>
        <input type="text" id="search" placeholder="Buscar contatos...">
        <ul id="contact-list">
            <!-- Lista de contatos será carregada aqui -->
        </ul>
    </div>
    <div class="chat">
        <div id="chat-header">
            <h2>Selecione um contato</h2>
        </div>
        <div id="chat-messages">
            <!-- Mensagens serão carregadas aqui -->
        </div>
        <form id="message-form">
            <input type="text" id="message-input" placeholder="Digite sua mensagem..." autocomplete="off">
            <button type="submit">Enviar</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    function loadContacts() {
        $.get('fetch_contacts.php', function (data) {
            $('#contact-list').html(data);
        });
    }

    function loadMessages(userId) {
        $.get('fetch_messages.php', { user_id: userId }, function (data) {
            $('#chat-messages').html(data);
        });
    }

    $('#contact-list').on('click', 'li', function () {
        const userId = $(this).data('user-id');
        $('#chat-header h2').text($(this).text());
        loadMessages(userId);
        $('#message-form').data('user-id', userId);
    });

    $('#message-form').submit(function (e) {
        e.preventDefault();

        const userId = $(this).data('user-id');
        const message = $('#message-input').val();

        if (message.trim() !== '') {
            $.post('send_message.php', { user_id: userId, message: message }, function (response) {
                if (response === "success") {
                    $('#message-input').val('');
                    loadMessages(userId); // Atualiza a lista de mensagens
                } else if (response === "empty_message") {
                    alert("A mensagem não pode ser vazia.");
                } else {
                    alert("Erro ao enviar a mensagem.");
                }
            });
        } else {
            alert("Digite uma mensagem.");
        }
    });

    loadContacts();
});
</script>
<?php include 'includes/footer.php'; ?>