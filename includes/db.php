<?php
$host = 'localhost';
$user = 'liedson';
$password = 'liedson21';
$dbname = 'rede_social';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
