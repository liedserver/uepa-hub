<?php
include 'includes/db.php';

try {
    // Tabela de mensagens
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES usuarios(id),
        FOREIGN KEY (receiver_id) REFERENCES usuarios(id)
    )";
    $conn->query($sql);
    echo "Tabela de mensagens verificada/criada com sucesso.<br>";
} catch (mysqli_sql_exception $e) {
    echo "Erro ao criar/verificar tabela de mensagens: " . $e->getMessage();
}
$conn->close();
?>
