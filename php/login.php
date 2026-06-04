<?php
if(!isset($_SESSION)) session_start();
extract($_REQUEST);

// Caminho da pasta de login
$loginDir = dirname(__DIR__) . "/login";

// Login: verifica email e senha na pastd /login
if(isset($acessar)) {
    $pass      = 0;
    $cpfUser   = "";
    $loginFile = $loginDir . "/" . $email . ".dat";

    // Lê o arquivo de login se existir
    if(file_exists($loginFile)) {
        $arq   = fopen($loginFile, "r");
        $linha = trim(fgets($arq, 1000));
        fclose($arq);

        // Arquivo guarda "md5|cpf"
        $partes  = explode("|", $linha);
        $pass    = trim($partes[0]);
        $cpfUser = trim($partes[1]);
    }

    // Compara o md5 da senha digitada com o salvo
    if(md5($senha) == $pass) {
        // Login ok, salva email e cpf na sessão
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario_cpf']   = $cpfUser;
        header('Location: ../main.html');
        exit;
    } else {
        // Senha ou usuário errado, volta pro login
        header('Location: ../login.html');
        exit;
    }
}

// Se acessar não foi enviado, volta pro login
header('Location: ../login.html');
exit;
?>