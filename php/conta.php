<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once "conex.php";


// Se não estiver logado, manda para o cadastro
if (
    !isset($_SESSION['usuario_email']) ||
    !isset($_SESSION['usuario_cpf'])
) {
    header('Location: ../cadrastro1.html');
    exit;
}


$cpf = $_SESSION['usuario_cpf'];


/*
 Busca os dados do usuário no MySQL
*/

$sql = "
    SELECT
        nome_completo,
        endereco,
        numero,
        bairro,
        cidade,
        estado,
        cep
    FROM usuarios
    WHERE cpf = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $cpf);

$stmt->execute();

$resultado = $stmt->get_result();


/*
 Verifica se encontrou o usuário
*/

if ($resultado->num_rows == 0) {
    header('Location: conta.php?msg=naotem');
    exit;
}


/*
 Puxa os dados encontrados
*/

$usuario = $resultado->fetch_assoc();

$nome     = $usuario['nome_completo'];
$endereco = $usuario['endereco'];
$numero   = $usuario['numero'];
$bairro   = $usuario['bairro'];
$cidade   = $usuario['cidade'];
$estado   = $usuario['estado'];
$cep      = $usuario['cep'];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bloco 17 - Minha Conta</title>

    <link rel="stylesheet" href="../styles/login.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    >
</head>

<body>

    <main class="login">

        <div class="login_content">

            <div>

                <h2 class="login_title">
                    Sua Conta 🐀
                </h2>


                <form
                    action="cadastro.php"
                    method="post"
                    class="login_form"
                >

                    <div class="login_group">


                        <!-- Nome -->
                        <div class="login_box">

                            <i class="ri-user-fill login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="nome_completo"
                                value="<?php echo $nome; ?>"
                                placeholder="Nome Completo"
                                required
                            >

                            <label class="login_label">
                                Nome Completo
                            </label>

                        </div>


                        <!-- Endereço -->
                        <div class="login_box">

                            <i class="ri-roadster-fill login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="endereco"
                                value="<?php echo $endereco; ?>"
                                placeholder="Endereço"
                                required
                            >

                            <label class="login_label">
                                Endereço
                            </label>

                        </div>


                        <!-- Número -->
                        <div class="login_box">

                            <i class="ri-home-2-line login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="numero"
                                value="<?php echo $numero; ?>"
                                placeholder="Número"
                                required
                            >

                            <label class="login_label">
                                Número
                            </label>

                        </div>


                        <!-- Bairro -->
                        <div class="login_box">

                            <i class="ri-map-pin-line login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="bairro"
                                value="<?php echo $bairro; ?>"
                                placeholder="Bairro"
                                required
                            >

                            <label class="login_label">
                                Bairro
                            </label>

                        </div>


                        <!-- Cidade -->
                        <div class="login_box">

                            <i class="ri-building-4-line login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="cidade"
                                value="<?php echo $cidade; ?>"
                                placeholder="Cidade"
                                required
                            >

                            <label class="login_label">
                                Cidade
                            </label>

                        </div>


                        <!-- Estado -->
                        <div class="login_box">

                            <i class="ri-map-pin-2-fill login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="estado"
                                value="<?php echo $estado; ?>"
                                placeholder="Estado"
                                maxlength="2"
                                required
                            >

                            <label class="login_label">
                                Estado
                            </label>

                        </div>


                        <!-- CEP -->
                        <div class="login_box">

                            <i class="ri-file-copy-2-fill login_icon"></i>

                            <input
                                type="text"
                                class="login_input"
                                name="cep"
                                value="<?php echo $cep; ?>"
                                placeholder="CEP"
                                required
                            >

                            <label class="login_label">
                                CEP
                            </label>

                        </div>

                    </div>


                    <!-- Atualizar -->
                    <button
                        type="submit"
                        class="login_button"
                        name="atualizar"
                        value="1"
                    >
                        Salvar
                    </button>


                    <!-- Voltar -->
                    <a
                        href="../main.html"
                        class="login_button"
                    >
                        Sair
                    </a>


                    <!-- Excluir conta -->
                    <button
                        type="submit"
                        name="deletar"
                        value="1"
                        style="
                            width: 100%;
                            height: 10px;
                            background: none;
                            border: none;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            font-family: 'Urbanist', sans-serif;
                            font-size: .813rem;
                            font-weight: 700;
                            color: #C1121F;
                            cursor: pointer;
                        "
                    >
                        Excluir Conta
                    </button>

                </form>

            </div>


            <div class="login_img_container">

                <img
                    src="../imgs/testeconta.png"
                    alt="Imagem da sua conta"
                    class="login_img"
                >

            </div>

        </div>

    </main>


    <script src="../JS/toast.js"></script>

</body>

</html>