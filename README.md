# Bloco 17

Loja virtual de streetwear desenvolvida como trabalho escolar por Pedro Kennedy e Joao Pedro. O projeto apresenta o catalogo da marca Bloco 17, permite criar uma conta, entrar, montar um carrinho e registrar pedidos no MySQL.

## Visao geral

O site e uma aplicacao sem framework, formada por paginas HTML, estilos CSS, JavaScript no navegador e endpoints PHP. O catalogo e mantido em `JS/carrinho.js`; os produtos nao sao carregados do banco.

O fluxo principal e:

1. A pessoa acessa `main.html` e escolhe uma categoria.
2. Em `camisas.html` ou `shorts.html`, abre uma pagina de produto.
3. A quantidade escolhida e salva no carrinho do navegador.
4. Em `carrinho.html`, a pessoa altera quantidades, aplica um cupom e inicia a finalizacao.
5. O sistema consulta `php/sessao.php`. Sem login, redireciona para `login.html`.
6. Com login, mostra a confirmacao, o usuario e o QR Code PIX.
7. `php/salvar_venda.php` grava a venda e seus itens no MySQL, limpa o carrinho e retorna o numero do pedido.

## Paginas

| Arquivo | Funcao |
| --- | --- |
| `main.html` | Pagina inicial, banner, carrossel de categorias e acesso ao login. |
| `camisas.html` | Lista tres produtos da categoria Camisas. |
| `shorts.html` | Lista tres produtos da categoria Shorts. |
| `precompraC.html` | Detalhes e escolha de quantidade/tamanho de uma camisa. |
| `precompraS.html` | Detalhes e escolha de quantidade/tamanho de um shorts. |
| `carrinho.html` | Itens, quantidades, cupom, confirmacao e pagamento PIX. |
| `login.html` | Entrada por email e senha. |
| `cadrastro1.html` | Primeira etapa do cadastro: dados pessoais e endereco. |
| `cadrastro2.html` | Segunda etapa do cadastro: email e senha. |
| `php/conta.php` | Consulta e edicao dos dados da conta autenticada. |
| `erro.html` | Pagina usada pelas categorias ainda nao implementadas. |

## Funcionalidades

### Catalogo e carrinho

Os seis produtos ficam no array `CATALOGO` de `JS/carrinho.js`:

- Camiseta Dri Fit: R$ 89,90
- Regata Dri Fit: R$ 189,90
- Camiseta Oversized Essential: R$ 149,90
- Short TM: R$ 50,90
- Shorts dri fit: R$ 189,90
- Shorts Oversized Essential: R$ 149,90

O carrinho e salvo em `localStorage` com a chave `bloco17_carrinho`. Cada registro guarda o ID do produto e a quantidade. O JavaScript calcula subtotais, total e parcelamento em 12 vezes sem juros. O cupom disponivel no codigo e `17`, que aplica 20% de desconto.

As paginas de produto usam `JS/precompra.js` para controlar quantidade, selecionar tamanho e executar as acoes de adicionar ao carrinho ou comprar imediatamente.

### Cadastro e endereco

O cadastro ocorre em duas etapas. Na primeira, `php/cadastro.php` valida o CPF, verifica se ele ja existe e guarda os dados pessoais temporariamente na sessao PHP. Na segunda, verifica a disponibilidade do email, aplica `password_hash()` na senha e insere o usuario na tabela `usuarios`.

`JS/apicep.js` consulta a API ViaCEP quando o CEP perde o foco e preenche endereco, bairro, cidade e estado.

### Login e sessao

`php/login.php` procura o email na tabela `usuarios`, valida a senha com `password_verify()` e salva `usuario_id`, `usuario_email` e `usuario_cpf` na sessao. `php/sessao.php` devolve `ok|nome|email` para o JavaScript do checkout ou apenas `login` quando nao ha usuario autenticado.

`php/conta.php` exige uma sessao valida, mostra os dados do usuario e envia alteracoes para `php/cadastro.php`, que tambem contem os tratamentos de atualizar e excluir conta.

### Pedido

Ao confirmar a compra, o navegador envia JSON para `php/salvar_venda.php` com pagamento, total e itens. O PHP:

1. exige `usuario_id` na sessao;
2. insere a venda em `vendas`;
3. insere cada item em `itens_venda`;
4. gera um codigo de oito caracteres para o pedido;
5. responde no formato `ok|CODIGO`.

O pagamento implementado na tela e PIX. O arquivo `imgs/qrcode.png` e exibido para o pagamento, mas nao existe integracao com um gateway para confirmar automaticamente a transacao.

## Organizacao dos arquivos

```text
.
├── *.html                 Paginas publicas da loja
├── JS/
│   ├── carrinho.js        Catalogo, carrinho, cupom e checkout
│   ├── precompra.js       Quantidade e acoes das paginas de produto
│   ├── apicep.js          Consulta de CEP na ViaCEP
│   ├── carrosel.js        Carrossel de banners
│   ├── categories_carousel.js
│   ├── loginani.js        Animacoes GSAP dos formularios
│   └── toast.js            Mensagens temporarias
├── php/
│   ├── conex.php          Conexao com o banco Bloco17
│   ├── cadastro.php       Cadastro, atualizacao e exclusao de conta
│   ├── login.php          Autenticacao
│   ├── sessao.php         Consulta da sessao para o frontend
│   ├── conta.php          Tela da conta
│   └── salvar_venda.php   Persistencia dos pedidos
├── styles/                Folhas de estilo por area do site
├── imgs/                  Logos, banners, categorias e produtos
├── fontes/Urbanist/       Fonte local do projeto
├── login/                 Arquivos .dat legados, nao usados pelo PHP atual
└── vendas/                Exemplos de pedidos em .dat, separados do MySQL
```

## Requisitos

- XAMPP com Apache, PHP e MySQL;
- PHP com a extensao `mysqli` habilitada;
- Navegador com `localStorage`, JavaScript e acesso a internet para ViaCEP/CDNs;
- Banco MySQL chamado `Bloco17`.

O repositorio nao possui um arquivo SQL de criacao das tabelas. Antes de testar cadastro e pedidos, o banco precisa conter pelo menos:

- `usuarios`: `id`, `nome_completo`, `cpf`, `endereco`, `numero`, `bairro`, `cidade`, `estado`, `cep`, `email`, `senha`;
- `vendas`: `id`, `numero_venda`, `usuario_id`, `pagamento`, `total`;
- `itens_venda`: `id`, `venda_id`, `nome_produto`, `quantidade`, `preco_unitario`, `subtotal`.

As colunas usadas como relacionamento devem aceitar os tipos correspondentes aos valores enviados pelos scripts PHP. Recomenda-se criar `id` como chave primaria auto_increment, `usuarios.email` e `usuarios.cpf` como unicos e `vendas.usuario_id`/`itens_venda.venda_id` como chaves estrangeiras.

## Como executar no XAMPP

1. Copie ou mantenha este projeto em `/opt/lampp/htdocs/paranhosphp/projetodupla`.
2. Inicie Apache e MySQL no painel do XAMPP.
3. Crie o banco `Bloco17` e as tres tabelas descritas acima no phpMyAdmin.
4. Confira as credenciais em `php/conex.php`. A configuracao atual usa host `127.0.0.1`, usuario `root`, senha vazia e banco `Bloco17`.
5. Abra no navegador:

   `http://localhost/paranhosphp/projetodupla/main.html`

Nao abra as paginas diretamente com `file://`: os endpoints PHP, sessoes, `fetch()` e a validacao do checkout dependem do Apache.

## Dependencias externas

- ViaCEP: preenchimento de endereco por CEP;
- Remix Icon: icones da interface;
- GSAP: animacoes das telas de login e cadastro;
- Google Fonts: fontes usadas nas paginas de produtos;
- MySQL/MariaDB: usuarios e vendas.

As bibliotecas frontend sao carregadas por CDN nas paginas HTML. O catalogo, precos e desconto continuam sendo definidos localmente no JavaScript.

## Observacoes atuais

- As categorias Conjuntos, Bones, Tenis e Calcas apontam para `erro.html`.
- O campo de pesquisa da home e apenas visual; nao ha rotina de busca implementada.
- O link "Esqueceu a senha?" ainda aponta para `#`.
- O checkout exibe apenas PIX, apesar de o codigo manter uma estrutura generica de pagamento.
- O desconto e calculado no navegador e enviado no total do pedido; para um ambiente real, o servidor deveria recalcular o valor com base nos IDs e precos oficiais.
- Os arquivos `.dat` em `login/` e `vendas/` sao registros legados/exemplos e nao substituem o banco configurado em `php/conex.php`.
- Nao ha testes automatizados, arquivo `.env`, migration ou script SQL no repositorio.

## Autoria

Projeto escolar desenvolvido por Pedro Kennedy e Joao Pedro.
