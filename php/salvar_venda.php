<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once "conex.php";


// Se não estiver logado, vai para login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit;
}


// Lê o JSON enviado pelo JavaScript
$body = file_get_contents("php://input");

$dados = json_decode($body, true);


// Verifica se recebeu itens
if (!$dados || empty($dados['itens'])) {
    header('Location: ../main.html');
    exit;
}


// Pega o id do usuário logado
$usuarioId = $_SESSION['usuario_id'];


// Gera número único da venda
$numeroVenda = strtoupper(
    substr(
        md5(uniqid(rand(), true)),
        0,
        8
    )
);


// Dados da venda
$pagamento = $dados['pagamento'];
$total     = $dados['total'];


/*
 Salva a venda principal
*/

$sql = "
    INSERT INTO vendas
    (
        numero_venda,
        usuario_id,
        pagamento,
        total
    )
    VALUES (?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sisd",
    $numeroVenda,
    $usuarioId,
    $pagamento,
    $total
);


if (!$stmt->execute()) {

    echo "erro";
    exit;
}


/*
 Pega o ID da venda criada
*/

$vendaId = $conn->insert_id;


/*
 Salva cada produto da venda
*/

$sqlItem = "
    INSERT INTO itens_venda
    (
        venda_id,
        nome_produto,
        quantidade,
        preco_unitario,
        subtotal
    )
    VALUES (?, ?, ?, ?, ?)
";

$stmtItem = $conn->prepare($sqlItem);


foreach ($dados['itens'] as $item) {

    $nomeProduto  = $item['nome'];
    $quantidade   = $item['quantidade'];
    $preco        = $item['preco'];
    $subtotal     = $item['subtotal'];


    $stmtItem->bind_param(
        "isidd",
        $vendaId,
        $nomeProduto,
        $quantidade,
        $preco,
        $subtotal
    );


    if (!$stmtItem->execute()) {

        echo "erro";
        exit;
    }
}


// Retorna sucesso para o JavaScript
echo "ok|" . $numeroVenda;

?>