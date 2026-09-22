# Bloco 17

Loja virtual de streetwear desenvolvida como projeto escolar por Pedro Kennedy e Joao Pedro. O site apresenta produtos, permite criar uma conta, fazer login, montar um carrinho e registrar pedidos no MySQL.

## Sobre o projeto

O projeto usa Php,html,javascrip,css A interface usa HTML, CSS e JavaScript no navegador. O PHP funciona como uma camada de backend para cadastro, login, sessao da conta e registro das vendas.

O catalogo de produtos nao e consultado no banco: ele fica definido no array `CATALOGO` de `JS/carrinho.js`. O banco armazena os usuarios e os pedidos realizados.

## Fluxo principal

1. A pagina `main.html` apresenta a loja e as categorias.
2. A pessoa acessa `camisas.html` ou `shorts.html` e escolhe um produto.
3. `JS/precompra.js` controla quantidade, tamanho e adicao ao carrinho.
4. `JS/carrinho.js` salva os itens no `localStorage` do navegador.
5. `carrinho.html` permite alterar quantidades, remover itens e aplicar o cupom `17`, que concede 20% de desconto.
6. Antes de finalizar, o JavaScript consulta `php/sessao.php` para verificar se existe login.
7. O pedido confirmado e enviado como JSON para `php/salvar_venda.php`.
8. O PHP grava a venda e seus itens no banco, retorna o numero do pedido e o carrinho e limpo.

## Paginas HTML

| Arquivo | Funcao |
| --- | --- |
| `main.html` | Pagina inicial, banners, categorias e acesso a conta. |
| `camisas.html` | Lista de produtos da categoria Camisas. |
| `shorts.html` | Lista de produtos da categoria Shorts. |
| `precompraC.html` | Detalhes e compra de uma camisa. |
| `precompraS.html` | Detalhes e compra de um shorts. |
| `carrinho.html` | Carrinho, cupom, confirmacao e pagamento. |
| `login.html` | Formulario de login. |
| `cadrastro1.html` | Primeira etapa do cadastro, com dados pessoais e endereco. |
| `cadrastro2.html` | Segunda etapa do cadastro, com email e senha. |
| `erro.html` | Pagina exibida para categorias ainda nao implementadas. |

## Organizacao dos arquivos

```text
.
├── *.html                 Paginas publicas da loja
├── JS/
│   ├── carrinho.js        Catalogo, carrinho, cupom e checkout
│   ├── precompra.js       Quantidade e acoes das paginas de produto
│   ├── apicep.js          Consulta de CEP na API ViaCEP
│   ├── carrosel.js        Carrossel de banners
│   ├── categories_carousel.js  Carrossel de categorias
│   ├── loginani.js        Animacoes das telas de login e cadastro
│   └── toast.js           Mensagens temporarias
├── php/
│   ├── conex.php          Conexao com o banco de dados
│   ├── cadastro.php       Cadastro, atualizacao e exclusao de conta
│   ├── login.php          Autenticacao do usuario
│   ├── sessao.php         Consulta da sessao usada pelo checkout
│   ├── conta.php          Consulta dos dados da conta
│   └── salvar_venda.php   Persistencia dos pedidos
├── styles/                Folhas de estilo da aplicacao
├── imgs/                  Logos, banners, categorias e produtos
├── fontes/Urbanist/       Fonte local usada no projeto
├── login/                 Arquivos .dat legados
└── vendas/                Arquivos .dat legados ou de exemplo
```

## JavaScript e carrinho

Os produtos atualmente cadastrados no `CATALOGO` sao:

- Camiseta Dri Fit: R$ 89,90
- Regata Dri Fit: R$ 189,90
- Camiseta Oversized Essential: R$ 149,90
- Short TM: R$ 50,90
- Shorts dri fit: R$ 189,90
- Shorts Oversized Essential: R$ 149,90

O carrinho e salvo no navegador com a chave `bloco17_carrinho`. Cada item possui apenas o ID do produto e a quantidade; nome, preco, categoria e imagem sao recuperados do catalogo JavaScript.

O total e calculado no navegador. O checkout exibe parcelamento em 12 vezes e atualmente apresenta o PIX como forma de pagamento. O QR Code exibido e uma imagem local; nao existe integracao com um gateway para confirmar automaticamente o pagamento.

## Banco de dados

O banco usado pela aplicacao se chama `Bloco17`. O arquivo `php/conex.php` fornece a conexao utilizada pelos demais scripts PHP.

### Tabela `usuarios`

Armazena os dados da conta:

| Campo | Uso |
| --- | --- |
| `id` | Identificador do usuario, usado como chave primaria. |
| `nome_completo` | Nome informado no cadastro. |
| `cpf` | CPF do usuario; e validado antes do cadastro. |
| `endereco`, `numero`, `bairro`, `cidade`, `estado`, `cep` | Dados de endereco. |
| `email` | Email usado no login. |
| `senha` | Hash da senha gerado por `password_hash()`. |

### Tabela `vendas`

Armazena o pedido principal:

| Campo | Uso |
| --- | --- |
| `id` | Identificador interno da venda. |
| `numero_venda` | Codigo exibido ao cliente, gerado pelo PHP. |
| `usuario_id` | Relacionamento com `usuarios.id`. |
| `pagamento` | Forma de pagamento enviada pelo checkout. |
| `total` | Valor total registrado para a venda. |

### Tabela `itens_venda`

Armazena cada produto de uma venda:

| Campo | Uso |
| --- | --- |
| `id` | Identificador do item. |
| `venda_id` | Relacionamento com `vendas.id`. |
| `nome_produto` | Nome do produto no momento da compra. |
| `quantidade` | Quantidade comprada. |
| `preco_unitario` | Preco de uma unidade. |
| `subtotal` | Preco unitario multiplicado pela quantidade. |

Recomenda-se configurar `usuarios.id`, `vendas.id` e `itens_venda.id` como chaves primarias auto incrementais. `usuarios.email` e `usuarios.cpf` devem ser unicos. Tambem e recomendavel usar chaves estrangeiras de `vendas.usuario_id` para `usuarios.id` e de `itens_venda.venda_id` para `vendas.id`.

## Conexao atual: MySQLi

 este projeto utiliza **MySQLi** em `php/conex.php`. A conexao cria um objeto `mysqli`, verifica erro e define o charset `utf8mb4`:

```php
$conn = new mysqli($server, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexao: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
```

Os scripts usam `prepare()`, `bind_param()` e `execute()` para enviar valores separados da instrucao SQL. Esse modelo, chamado de prepared statement, ajuda a evitar injecao de SQL e tambem deixa claro quais valores serao usados na consulta.


## Cadastro, login e sessao

- `php/cadastro.php` valida o CPF, verifica duplicidade, guarda a primeira etapa na sessao e cria o usuario na segunda etapa.
- A senha nao e salva em texto puro: `password_hash()` gera o hash e `password_verify()` confere a senha no login.
- `php/login.php` grava o ID, email e CPF do usuario nas variaveis de sessao.
- `php/sessao.php` responde `ok|nome|email` quando ha login e `login` quando nao ha usuario autenticado.
- `php/conta.php` consulta os dados da conta para permitir sua visualizacao e alteracao.

## Requisitos

- XAMPP com Apache e MySQL ou MariaDB;
- PHP com a extensao `mysqli` habilitada;
- Banco de dados `Bloco17` criado e com as tabelas descritas acima;
- Navegador com JavaScript e `localStorage` habilitados;
- Acesso a internet para a API ViaCEP e bibliotecas carregadas por CDN.

## Como executar

1. Mantenha o projeto em `/opt/lampp/htdocs/paranhosphp/projetodupla` ou em uma pasta equivalente dentro do `htdocs`.
2. Inicie Apache e MySQL no XAMPP.
3. Crie o banco `Bloco17` e importe o arquivo SQL do banco, caso ele esteja disponivel no repositorio.
4. Confira em `php/conex.php` o host, usuario, senha e nome do banco do seu ambiente. Nao publique credenciais reais no repositorio.
5. Acesse `http://localhost/paranhosphp/projetodupla/main.html`.

As paginas devem ser acessadas pelo Apache, e nao diretamente com `file://`, porque o projeto depende de PHP, sessoes e requisicoes `fetch()`.

## Dependencias externas

- ViaCEP: preenchimento de endereco pelo CEP;
- Remix Icon: icones da interface;
- GSAP: animacoes de login e cadastro;
- Google Fonts: fontes usadas em algumas paginas;
- MySQL ou MariaDB: persistencia de usuarios e vendas.

## Limitacoes conhecidas

- As categorias Conjuntos, Bones, Tenis e Calcas ainda direcionam para `erro.html`.
- A pesquisa exibida na pagina inicial ainda nao possui rotina de busca.
- O link de recuperacao de senha ainda aponta para `#`.
- O total e o desconto sao enviados pelo navegador; em um ambiente real, o servidor deveria recalcular os precos usando um catalogo confiavel.
- Os arquivos `.dat` em `login/` e `vendas/` sao legados ou exemplos e nao substituem o banco MySQL.
- Nao ha testes automatizados nem migrations versionadas no projeto.
- 

## Autoria

Projeto escolar desenvolvido por Pedro Kennedy e Joao Pedro.
