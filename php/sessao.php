<?php
if(!isset($_SESSION)) session_start();

// Não está logado  manda pro login
if(!isset($_SESSION['usuario_email'])) {
    echo "login";
    exit;
}

$email       = $_SESSION['usuario_email'];
$cpf         = $_SESSION['usuario_cpf'];
$usuariosDir = dirname(__DIR__) . '/usuarios';
$dadosArq    = $usuariosDir . '/' . $cpf . '.dat';
$nome        = $email;

// Lê o nome no arquivo  em /usuarios
if(file_exists($dadosArq)) {
    $arq    = fopen($dadosArq, "r");
    $linha  = fgets($arq, 1000);
    fclose($arq);

    $campos = explode('|', $linha);
    $nome   = trim($campos[0]);
}

// Retorna ok|nome|email — lido pelo JS com split("|")
echo "ok|" . $nome . "|" . $email;
?>