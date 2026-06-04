<?php
if(!isset($_SESSION)) session_start();

// Precisa estar logado
if(!isset($_SESSION['usuario_email'])) {
    header('Location: ../login.html');
    exit;
}

// Lê o JSON enviado pelo JS
$body  = file_get_contents("php://input");
$dados = json_decode($body, true);

if(!$dados || empty($dados['itens'])) {
    header('Location: ../main.html');
    exit;
}

// Pega email e CPF da sessão
$email       = $_SESSION['usuario_email'];
$cpf         = $_SESSION['usuario_cpf'];
$usuariosDir = dirname(__DIR__) . "/usuarios";
$dadosArq    = $usuariosDir . "/" . $cpf . ".dat";
$nome        = $email;

// Lê o nome no arquivo do CPF em /usuarios
if(file_exists($dadosArq)) {
    $arq    = fopen($dadosArq, "r");
    $linha  = fgets($arq, 1000);
    fclose($arq);

    $campos = explode("|", $linha);
    $nome   = trim($campos[0]);
}

// Cria pasta /vendas se não existir
$vendasDir = dirname(__DIR__) . "/vendas";
if(!is_dir($vendasDir)) mkdir($vendasDir, 0755, true);

// Gera número único da venda
$numeroVenda = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

// Monta os itens em texto
$itensTexto = "";
foreach($dados['itens'] as $item) {
    $itensTexto .= "  - " . $item['nome'] .
                   " | Qtd: "         . $item['quantidade'] .
                   " | Unit: R$ "     . number_format($item['preco'],    2, ',', '.') .
                   " | Subtotal: R$ " . number_format($item['subtotal'], 2, ',', '.') . "\n";
}

// Monta o conteúdo do arquivo
$conteudo = "VENDA: "     . $numeroVenda                                          . "\n" .
            "DATA/HORA: " . date("d/m/Y H:i:s")                                   . "\n" .
            "USUARIO: "   . $nome                                                  . "\n" .
            "EMAIL: "     . $email                                                 . "\n" .
            "PAGAMENTO: " . $dados['pagamento']                                    . "\n" .
            "TOTAL: R$ "  . number_format($dados['total'], 2, ',', '.')            . "\n" .
            "ITENS:"                                                               . "\n" .
            rtrim($itensTexto)                                                     . "\n" .
            str_repeat("-", 40);

// Salva o arquivo da venda
$arq2 = fopen($vendasDir . "/" . $numeroVenda . ".dat", "w");
fwrite($arq2, $conteudo);
fclose($arq2);

echo "ok|" . $numeroVenda;
?>