<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once "conex.php";


// Não está logado
if (!isset($_SESSION['usuario_email'])) {
    echo "login";
    exit;
}


$email = $_SESSION['usuario_email'];


// Busca o nome do usuário no MySQL
$sql = "
    SELECT nome_completo
    FROM usuarios
    WHERE email = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$resultado = $stmt->get_result();


$nome = $email;


// Se encontrou o usuário, pega o nome
if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    $nome = $usuario['nome_completo'];
}


// Retorna ok|nome|email
echo "ok|" . $nome . "|" . $email;

?>