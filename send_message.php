<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['user_id'];
$message = $_POST['message'];

if (empty($message)) {
    echo "empty_message";
    exit;
}

$sql = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
} else {
    echo "error_preparing_statement";
}

$conn->close();
?>
