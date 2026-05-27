<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();

// Diz pro navegador que a resposta é JSON
header('Content-Type: application/json');

// Se não tiver ninguém logado, retorna logado = false
if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['logado' => false, 'email' => null, 'nome' => null]);
    exit;
}

$email = $_SESSION['usuario_email'];

// Pasta de usuários fica dentro do projeto (htdocs/seuprojeto/usuarios/)
// __DIR__ = pasta php/   dirname(__DIR__) = pasta raiz do projeto
$usuariosDir = dirname(__DIR__) . '/usuarios';
$dadosArq    = $usuariosDir . '/' . $email . '_dados.dat';
$nome        = null;

// Lê o nome do arquivo de dados do usuário
if (file_exists($dadosArq)) {
    $linha  = file_get_contents($dadosArq);
    $campos = explode('|', $linha);
    // Formato do arquivo: nome|cpf|endereco|bairro|cidade|estado|cep|email
    $nome = trim($campos[0] ?? null);
}

// Retorna os dados do usuário logado
echo json_encode([
    'logado' => true,
    'email'  => $email,
    'nome'   => $nome
]);