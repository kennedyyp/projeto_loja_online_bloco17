<?php
// php/sessao.php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['logado' => false, 'email' => null, 'nome' => null]);
    exit;
}

$email = $_SESSION['usuario_email'];

// Lê o nome do arquivo _dados.dat gerado no cadastro
$usuariosDir = dirname(__DIR__) . "/usuarios";
$dadosArq    = $usuariosDir . "/" . $email . "_dados.dat";
$nome        = null;

if (file_exists($dadosArq)) {
    $linha  = file_get_contents($dadosArq);
    $campos = explode("|", $linha);
    // Formato: nome_completo|cpf|endereco|bairro|cidade|estado|cep|email
    $nome = $campos[0] ?? null;
}

echo json_encode([
    'logado' => true,
    'email'  => $email,
    'nome'   => $nome
]);
?>