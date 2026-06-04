<?php
if (!isset($_SESSION)) session_start();
extract($_REQUEST);

// Cria as pastas separadas como o professor pediu
$usuariosDir = dirname(__DIR__) . "/usuarios";
if (!is_dir($usuariosDir)) mkdir($usuariosDir, 0755, true);

$loginDir = dirname(__DIR__) . "/login";
if (!is_dir($loginDir)) mkdir($loginDir, 0755, true);

// Etapa 1: salva dados pessoais na sessão e em /usuarios pelo CPF
if (isset($salvar1)) {

    // Validação do CPF
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) == 11) {

        $cont = 10;
        $soma = 0;

        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * $cont;
            $cont--;
        }

        $resto = $soma % 11;
        $digito_10 = ($resto < 2) ? 0 : 11 - $resto;

        $cont_2 = 11;
        $soma_2 = 0;

        for ($i = 0; $i < 10; $i++) {
            $soma_2 += $cpf[$i] * $cont_2;
            $cont_2--;
        }

        $resto_2 = $soma_2 % 11;
        $digito_11 = ($resto_2 < 2) ? 0 : 11 - $resto_2;

        if ($cpf[9] != $digito_10 || $cpf[10] != $digito_11) {
            echo "CPF incorreto.<br/>";
            echo "<a href='../cadrastro1.html'>voltar</a>";
            exit;
        }

    } else {
        echo "CPF incorreto.<br/>";
        echo "<a href='../cadrastro1.html'>voltar</a>";
        exit;
    }

    // CPF válido, salva na sessão
    $_SESSION['cad_nome']     = $nome_completo;
    $_SESSION['cad_cpf']      = $cpf;
    $_SESSION['cad_endereco'] = $endereco;
    $_SESSION['cad_bairro']   = $bairro;
    $_SESSION['cad_cidade']   = $cidade;
    $_SESSION['cad_estado']   = $estado;
    $_SESSION['cad_cep']      = $cep;

    // Salva dados pessoais em /usuarios pelo CPF
    $arq = fopen($usuariosDir . "/" . $cpf . ".dat", "w");
    fwrite($arq, $nome_completo . "|" . $cpf . "|" . $endereco . "|" . $bairro . "|" . $cidade . "|" . $estado . "|" . $cep);
    fclose($arq);

    header('Location: ../cadrastro2.html');
    exit;
}

// Etapa 2: salva email e senha em /login
if (isset($salvar2)) {

    $loginFile = $loginDir . "/" . $email . ".dat";

    if (file_exists($loginFile)) {
        echo "O usuário já existe.<br/>";
        echo "<a href='../cadrastro2.html'>voltar</a>";
        exit;
    }

    $arq = fopen($loginFile, "w");
    fwrite($arq, md5($senha) . "|" . $_SESSION['cad_cpf']);
    fclose($arq);

    unset(
        $_SESSION['cad_nome'],
        $_SESSION['cad_cpf'],
        $_SESSION['cad_endereco'],
        $_SESSION['cad_bairro'],
        $_SESSION['cad_cidade'],
        $_SESSION['cad_estado'],
        $_SESSION['cad_cep']
    );

    header('Location: ../login.html');
    exit;
}

// Login
if (isset($acessar)) {

    $loginFile = $loginDir . "/" . $email . ".dat";
    $pass = "";
    $cpfUser = "";

    if (file_exists($loginFile)) {
        $arq = fopen($loginFile, "r");
        $linha = fgets($arq, 1000);
        fclose($arq);

        $partes = explode("|", $linha);
        $pass = trim($partes[0]);
        $cpfUser = trim($partes[1]);
    }

    if (md5($senha) == $pass) {
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario_cpf']   = $cpfUser;
        header('Location: ../main.html');
    } else {
        header('Location: ../login.html');
    }
    exit;
}

// Atualizar dados do usuário
if(isset($atualizar)) {

    if (!isset($_SESSION['usuario_email']) || !isset($_SESSION['usuario_cpf'])) {
        header('Location: ../cadrastro1.html');
        exit;
    }

    $cpf      = $_SESSION['usuario_cpf'];
    $dadosArq = $usuariosDir . "/" . $cpf . ".dat";

    // Lê o nome atual pra manter o CPF intacto
    $arq    = fopen($dadosArq, "r");
    $linha  = trim(fgets($arq, 1000));
    fclose($arq);

    $campos = explode("|", $linha);
    $cpf_formatado = $campos[1];

    // Salva os dados atualizados
    $arq = fopen($dadosArq, "w");
    fwrite(
     $arq,
     $nome_completo."|".
     $cpf_formatado."|".
     $endereco."|".
     $bairro."|".
     $cidade."|".
     $estado."|".
     $cep
     );

    fclose($arq);

    header('Location: conta.php?msg=atualizadu');
    exit;
}

// Deletar usuário
if (isset($deletar)) {

        if (!isset($_SESSION['usuario_email']) || !isset($_SESSION['usuario_cpf'])) {
        header('Location: ../login.html');
        exit;
    }

    $email = $_SESSION['usuario_email'];
    $cpf   = $_SESSION['usuario_cpf'];

    $loginFile   = $loginDir . "/" . $email . ".dat";
    $usuarioFile = $usuariosDir . "/" . $cpf . ".dat";

    // Remove arquivo de login
    if (file_exists($loginFile)) {
        unlink($loginFile);
    }

    // Remove dados do usuário
    if (file_exists($usuarioFile)) {
        unlink($usuarioFile);
    }

    // Destroi a sessão
    session_unset();
    session_destroy();

    header('Location: ../login.html');
    exit;
}
?>