<?php
include 'includes/db.php';

$post_id = $_POST['post_id'];

$query = $conn->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = ?");
$query->bind_param("i", $post_id);
$query->execute();
$result = $query->get_result()->fetch_assoc();

echo $result['total_likes'];
?>
