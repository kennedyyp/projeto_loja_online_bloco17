<?php
// Inicia a sessão para armazenar dados entre páginas
session_start();

// puxa get e post tlgd
extract($_REQUEST);

// Caminho absoluto para a pasta de usuários
$usuariosDir = dirname(__DIR__) . "/usuarios";
if (!is_dir($usuariosDir)) {
    mkdir($usuariosDir, 0755, true);
}

// Login: Acessa usando email e senha
if(isset($acessar)) {
    $pass = "";
    $nome = $usuariosDir . "/" . $email . ".dat";

    // Lê o arquivo com a senha hash
    if(file_exists($nome)) {
        $arq = fopen($nome, "r");
        $pass = fgets($arq, 1000);
        fclose($arq);
    }

    // Verifica se a senha está correta usando password_verify
    if (password_verify($senha, $pass)) {
        // Cria sessão de usuário autenticado
        $_SESSION['usuario_email'] = $email;
        
        header('Location: ../main.html');
        exit;
    } else {
        header('Location: ../login.html');
        exit;
    }
}
?>
 