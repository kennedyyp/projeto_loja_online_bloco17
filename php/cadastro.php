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

// Caminho absoluto para a pasta de endereço
$enderecoDir = dirname(__DIR__) . "/endereco";
if (!is_dir($enderecoDir)) {
    mkdir($enderecoDir, 0755, true);
}

// Etapa 1: Cadastro de dados pessoais
if(isset($salvar1)) {
    // Salva os dados da etapa 1 na sessão
    $_SESSION['cadastro'] = [
        'nome_completo' => $nome_completo,
        'cpf' => $cpf,
        'endereco' => $endereco,
        'bairro' => $bairro,
        'cidade' => $cidade,
        'estado' => $estado,
        'cep' => $cep
    ];

    // Salva os dados pessoais na pasta endereco
    $arquivoEndereco = $enderecoDir . "/" . $cpf . ".dat";
    $arq = fopen($arquivoEndereco, "w");
    fwrite($arq, implode("|", [
        $nome_completo,
        $cpf,
        $endereco,
        $bairro,
        $cidade,
        $estado,
        $cep
    ]));
    fclose($arq);

    // Redireciona para a etapa 2
    header('Location: ../cadrastro2.html');
    exit;
}

// Etapa 2: Cadastro de email e senha
if(isset($salvar2)) {
    // Define o arquivo usando o email como identificador
    $nome = $usuariosDir . "/" . $email . ".dat";

    // Verifica se o email já existe
    if (file_exists($nome)) {
        echo "O usuário já existe.<br/>";
        echo "<a href='../cadrastro2.html'>Voltar</a>";
    } else {
        // Recupera os dados da sessão
        $dados = $_SESSION['cadastro'];
        
        // Criptografa a senha com password_hash (bcrypt)
        $pass = password_hash($senha, PASSWORD_DEFAULT);
        
        // Salva a senha no arquivo
        $arq = fopen($nome, "w");
        fwrite($arq, $pass);
        fclose($arq);
        
        // Salva os dados completos em outro arquivo (sem senha)
        $dados_arquivo = $usuariosDir . "/" . $email . "_dados.dat";
        $dados['email'] = $email;
        $arq = fopen($dados_arquivo, "w");
        fwrite($arq, implode("|", $dados));
        fclose($arq);
        
        // Limpa a sessão de cadastro
        unset($_SESSION['cadastro']);

        // Redireciona para a página de login
        header('Location: ../login.html');
        exit;
    }
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

// Deletar usuário
if(isset($deletar)) {
    $nome = $usuariosDir . "/" . $email . ".dat";
    $dados_arquivo = $usuariosDir . "/" . $email . "_dados.dat";

    if (file_exists($nome)) {
        if (unlink($nome)) {
            if (file_exists($dados_arquivo)) {
                unlink($dados_arquivo);
            }
            echo "Usuário excluído com sucesso!<br/>";
        } else {
            echo "Erro ao excluir o usuário.<br/>";
        }
    } else {
        echo "O Usuário não existe.<br/>";
    }

    echo "<a href='../login.html'>Voltar</a>";
}
?>

