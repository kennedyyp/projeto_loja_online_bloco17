<?php

if (!isset($_SESSION)) {
    session_start();
}

extract($_POST);

require_once "conex.php";


/*
 Etapa 1: salva dados pessoais na sessão e verifica o CPF
*/

if (isset($salvar1)) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
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


    /*
     Verifica se o CPF já está cadastrado no MySQL
    */

    $sql = "SELECT id FROM usuarios WHERE cpf = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        echo "O CPF já está cadastrado.<br/>";
        echo "<a href='../cadrastro1.html'>voltar</a>";
        exit;
    }


    /*
    Salva os dados da primeira etapa na sessão
    */

    $_SESSION['cad_nome']     = $nome_completo;
    $_SESSION['cad_cpf']      = $cpf;
    $_SESSION['cad_endereco'] = $endereco;
    $_SESSION['cad_numero']   = $numero;
    $_SESSION['cad_bairro']   = $bairro;
    $_SESSION['cad_cidade']   = $cidade;
    $_SESSION['cad_estado']   = $estado;
    $_SESSION['cad_cep']      = $cep;


    header('Location: ../cadrastro2.html');
    exit;
}


/* 
Etapa 2: salva email e senha no MySQL
*/

if (isset($salvar2)) {

    if (!isset($_SESSION['cad_cpf'])) {

        echo "A primeira etapa do cadastro não foi concluída.<br/>";
        echo "<a href='../cadrastro1.html'>voltar</a>";
        exit;
    }


    /*
     Verifica se o email já existe
    */

    $sql = "SELECT id FROM usuarios WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        echo "O usuário já existe.<br/>";
        echo "<a href='../cadrastro2.html'>voltar</a>";
        exit;
    }


    /*
    Criptografa a senha
    */

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);


    /*
     Insere o usuário no banco
    */

    $sql = "
        INSERT INTO usuarios
        (
            nome_completo,
            cpf,
            endereco,
            numero,
            bairro,
            cidade,
            estado,
            cep,
            email,
            senha
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssssssss",
        $_SESSION['cad_nome'],
        $_SESSION['cad_cpf'],
        $_SESSION['cad_endereco'],
        $_SESSION['cad_numero'],
        $_SESSION['cad_bairro'],
        $_SESSION['cad_cidade'],
        $_SESSION['cad_estado'],
        $_SESSION['cad_cep'],
        $email,
        $senhaHash
    );


    if ($stmt->execute()) {

        unset(
            $_SESSION['cad_nome'],
            $_SESSION['cad_cpf'],
            $_SESSION['cad_endereco'],
            $_SESSION['cad_numero'],
            $_SESSION['cad_bairro'],
            $_SESSION['cad_cidade'],
            $_SESSION['cad_estado'],
            $_SESSION['cad_cep']
        );

        header('Location: ../login.html');
        exit;

    } else {

        echo "Erro ao cadastrar usuário.<br/>";
        echo "<a href='../cadrastro2.html'>voltar</a>";
        exit;
    }
}


/*
| Login
*/

if (isset($acessar)) {

    $sql = "
        SELECT id, cpf, email, senha
        FROM usuarios
        WHERE email = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();


    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();


        if (password_verify($senha, $usuario['senha'])) {

            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_cpf']   = $usuario['cpf'];

            header('Location: ../main.html');
            exit;

        } else {

            header('Location: ../login.html');
            exit;
        }

    } else {

        header('Location: ../login.html');
        exit;
    }
}


/*
| Atualizar dados do usuário
*/

if (isset($atualizar)) {

    if (
        !isset($_SESSION['usuario_email']) ||
        !isset($_SESSION['usuario_cpf'])
    ) {
        header('Location: ../cadrastro1.html');
        exit;
    }


    $cpf = $_SESSION['usuario_cpf'];


    $sql = "
        UPDATE usuarios
        SET
            nome_completo = ?,
            endereco = ?,
            numero = ?,
            bairro = ?,
            cidade = ?,
            estado = ?,
            cep = ?
        WHERE cpf = ?
    ";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssssss",
        $nome_completo,
        $endereco,
        $numero,
        $bairro,
        $cidade,
        $estado,
        $cep,
        $cpf
    );


    if ($stmt->execute()) {

        header('Location: conta.php?msg=atualizadu');
        exit;

    } else {

        echo "Erro ao atualizar os dados.<br/>";
        echo "<a href='conta.php'>voltar</a>";
        exit;
    }
}


/*
| Deletar usuário
*/

if (isset($deletar)) {

    if (
        !isset($_SESSION['usuario_email']) ||
        !isset($_SESSION['usuario_cpf'])
    ) {
        header('Location: ../login.html');
        exit;
    }


    $cpf = $_SESSION['usuario_cpf'];


    $sql = "DELETE FROM usuarios WHERE cpf = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);


    if ($stmt->execute()) {

        session_unset();
        session_destroy();

        header('Location: ../login.html');
        exit;

    } else {

        echo "Erro ao deletar usuário.<br/>";
        echo "<a href='conta.php'>voltar</a>";
        exit;
    }
}

?>