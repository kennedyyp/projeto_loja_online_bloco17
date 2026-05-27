<?php
// php/salvar_venda.php
session_start();

header('Content-Type: application/json');

// Precisa estar logado
if (!isset($_SESSION['usuario_email'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Usuário não autenticado']);
    exit;
}

// Lê o JSON enviado pelo JS
$body = file_get_contents("php://input");
$dados = json_decode($body, true);

if (!$dados || empty($dados['itens'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos']);
    exit;
}

// ── Dados do usuário ──
$email       = $_SESSION['usuario_email'];
$usuariosDir = dirname(__DIR__) . "/usuarios";
$dadosArq    = $usuariosDir . "/" . $email . "_dados.dat";
$nome        = $email; // fallback

if (file_exists($dadosArq)) {
    $campos = explode("|", file_get_contents($dadosArq));
    $nome   = $campos[0] ?? $email;
}

// ── Cria pasta /vendas se não existir ──
$vendasDir = dirname(__DIR__) . "/vendas";
if (!is_dir($vendasDir)) {
    mkdir($vendasDir, 0755, true);
}

// ── Gera número único da venda ──
$numeroVenda = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

// ── Monta os itens em texto ──
$itensTexto = "";
foreach ($dados['itens'] as $item) {
    $itensTexto .= "  - " . $item['nome'] .
                   " | Qtd: " . $item['quantidade'] .
                   " | Unit: R$ " . number_format($item['preco'], 2, ',', '.') .
                   " | Subtotal: R$ " . number_format($item['subtotal'], 2, ',', '.') . "\n";
}

// ── Conteúdo do arquivo .dat ──
$conteudo = implode("\n", [
    "VENDA: "          . $numeroVenda,
    "DATA/HORA: "      . date("d/m/Y H:i:s"),
    "USUARIO: "        . $nome,
    "EMAIL: "          . $email,
    "PAGAMENTO: "      . ($dados['pagamento'] ?? 'Não informado'),
    "TOTAL: R$ "       . number_format($dados['total'], 2, ',', '.'),
    "ITENS:",
    rtrim($itensTexto),
    str_repeat("-", 40)
]);

// ── Salva o arquivo ──
$arquivo = $vendasDir . "/" . $numeroVenda . ".dat";
file_put_contents($arquivo, $conteudo);

echo json_encode([
    'sucesso'      => true,
    'numero_venda' => $numeroVenda
]);
?>

