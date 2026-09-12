<?php 
$server = "127.0.0.1";
$user   = "root";
$pass   = "";
$db     = "Bloco17";

$conn = new mysqli(
    $server, 
    $user, 
    $pass, 
    $db
);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");




?>


