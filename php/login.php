<?php

if (!isset($_SESSION)) {
    session_start();
}

extract($_POST);

require_once "conex.php";


/*
 Login: verifica email e senha no MySQL
*/

if (isset($acessar)) {

    /*
     Procura o usuário pelo email
    */

    $sql = "
        SELECT id, cpf, email, senha
        FROM usuarios
        WHERE email = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $resultado = $stmt->get_result();


    /*
     Verifica se encontrou o usuário
    */

    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();


        /*
         Verifica se a senha digitada corresponde
         ao hash salvo no banco
        */

        if (password_verify($senha, $usuario['senha'])) {

            /*
             Login correto:
             salva os dados principais na sessão
            */

            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_cpf']   = $usuario['cpf'];


            /*
             Redireciona para página principal
            */

            header('Location: ../main.html');
            exit;

        } else {

            /*
             Senha incorreta
            */

            header('Location: ../login.html');
            exit;
        }

    } else {

        /*
         Email não encontrado
        */

        header('Location: ../login.html');
        exit;
    }
}


/*
 Se o formulário não enviou "acessar",
 volta para o login
*/

header('Location: ../login.html');
exit;

?>