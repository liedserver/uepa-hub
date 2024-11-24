<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include 'includes/db.php';
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="assets/css/calendar.css">
<div class="calendar-container">
    <h2>Calendário de Eventos</h2>
    <div class="calendar">
        <?php
        $events_query = $conn->query("SELECT * FROM eventos ORDER BY data_evento ASC");
        while ($event = $events_query->fetch_assoc()) {
            echo '<div class="event">';
            echo '<h3>' . htmlspecialchars($event['titulo']) . '</h3>';
            echo '<p>' . htmlspecialchars($event['descricao']) . '</p>';
            echo '<p><strong>Data:</strong> ' . date('d/m/Y H:i', strtotime($event['data_evento'])) . '</p>';
            echo '<form action="subscribe_event.php" method="POST">';
            echo '<input type="hidden" name="event_id" value="' . $event['id'] . '">';
            echo '<button type="submit">Inscrever-se</button>';
            echo '</form>';
            echo '</div>';
        }
        ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>