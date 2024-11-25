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
        <button id="new-chat">Novo Chat</button>
        <input type="text" id="search" placeholder="Buscar contatos...">
        <ul id="contact-list">
            <!-- Contatos aparecerão aqui -->
        </ul>
    </div>
    <div class="chat">
        <div id="chat-header">
            <h2>Selecione um contato</h2>
        </div>
        <div id="chat-messages">
            <!-- Mensagens aparecerão aqui -->
        </div>
        <form id="message-form">
            <input type="text" id="message-input" placeholder="Digite sua mensagem..." autocomplete="off">
            <button type="submit">Enviar</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        });
    }

    $('#contact-list').on('click', 'li', function () {
    const userId = $(this).data('user-id');
    const userName = $(this).text();

    // Fetch and display user photo in header with the name
    $.get('fetch_user_photo.php', { user_id: userId }, function (data) {
        $('#chat-header').html(`
            <img src="${data}" alt="Foto do Usuário" class="header-photo">
            <h2>${userName}</h2>
        `);
    });

    loadMessages(userId);
    $('#message-form').data('user-id', userId);
});



    $('#message-form').submit(function (e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        const message = $('#message-input').val();

        if (message.trim()) {
            $.post('send_message.php', { user_id: userId, message: message }, function (response) {
                if (response === 'success') {
                    $('#message-input').val('');
                    loadMessages(userId);
                } else {
                    alert(response);
                }
            });
        } else {
            alert('Digite uma mensagem.');
        }
    });

    $('#new-chat').click(function () {
        $.get('fetch_contacts.php', function (data) {
            $('#contact-list').html(data);
        });
    });

    loadContacts();
});
</script>
<?php include 'includes/footer.php'; ?>
