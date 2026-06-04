<?php
if(!isset($_SESSION)) session_start();

// Se não estiver logado,verrificando as pastw, manda pro cadastro 
if (!isset($_SESSION['usuario_email']) || !isset($_SESSION['usuario_cpf'])) {
    header('Location: ../cadrastro1.html');
    exit;
}

$cpf         = $_SESSION['usuario_cpf'];
$usuariosDir = dirname(__DIR__) . "/usuarios";
$dadosArq    = $usuariosDir . "/" . $cpf . ".dat";

if (!file_exists($dadosArq)) {
    header('Location: conta.php?msg=naotem');
    exit;
}

// Lê os dados do usuário
$nome     = "";
$endereco = "";
$bairro   = "";
$cidade   = "";
$estado   = "";
$cep      = "";

// puxa as prd se exisitir certinho
if(file_exists($dadosArq)) {
    $arq    = fopen($dadosArq, "r");
    $linha  = trim(fgets($arq, 1000));
    fclose($arq);

    $campos   = explode("|", $linha);
    $nome     = $campos[0];
    $endereco = $campos[2];
    $bairro   = $campos[3];
    $cidade   = $campos[4];
    $estado   = $campos[5];
    $cep      = $campos[6];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloco 17 - Minha Conta</title>
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
</head>
<body>
    <main class="login">
        <div class="login_content">
            <div>
                <h2 class="login_title">Sua Conta 🐀</h2>
                <form action="cadastro.php" method="post" class="login_form">

                    <div class="login_group">
                        <div class="login_box">
                            <i class="ri-user-fill login_icon"></i>
                            <input type="text" class="login_input" name="nome_completo" value="<?php echo $nome; ?>" placeholder="Nome Completo">
                            <label class="login_label">Nome Completo</label>
                        </div>

                        <div class="login_box">
                            <i class="ri-roadster-fill login_icon"></i>
                            <input type="text" class="login_input" name="endereco" value="<?php echo $endereco; ?>" placeholder="Endereço">
                            <label class="login_label">Endereço</label>
                        </div>

                        <div class="login_box">
                            <i class="ri-map-pin-line login_icon"></i>
                            <input type="text" class="login_input" name="bairro" value="<?php echo $bairro; ?>" placeholder="Bairro">
                            <label class="login_label">Bairro</label>
                        </div>

                        <div class="login_box">
                            <i class="ri-building-4-line login_icon"></i>
                            <input type="text" class="login_input" name="cidade" value="<?php echo $cidade; ?>" placeholder="Cidade">
                            <label class="login_label">Cidade</label>
                        </div>

                        <div class="login_box">
                            <i class="ri-map-pin-2-fill login_icon"></i>
                            <input type="text" class="login_input" name="estado" value="<?php echo $estado; ?>" placeholder="Estado">
                            <label class="login_label">Estado</label>
                        </div>

                        <div class="login_box">
                            <i class="ri-file-copy-2-fill login_icon"></i>
                            <input type="text" class="login_input" name="cep" value="<?php echo $cep; ?>" placeholder="CEP">
                            <label class="login_label">CEP</label>
                        </div>

                        

                    </div>

                      <button type="submit" class="login_button" name="atualizar" value="1">
                        Salvar 
                    </button>

                    <a href="../main.html" class="login_button" >
                        Sair 
                    </a>

                    <button type="submit" style=" width: 100%;
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
                                                cursor: pointer;"

                                                name="deletar"
                                                value="1">
                        Excluir Conta
                    </button>

                </form>
            </div>

                        <div class="login_img_container">
                            <img src="../imgs/testeconta.png" alt="Imagem da sua conta" class="login_img" >
                        </div>
        </div>


        
    </main>

<script src="../JS/toast.js"></script>
</body>
</html>
