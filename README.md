# projeto_loja_online_bloco17
### Trabalho Escolar | Desenvolvimento Web

## 📌 Sobre o Projeto
Este projeto é uma loja virtual simples, desenvolvida como trabalho escolar, com o objetivo de praticar conceitos básicos de desenvolvimento web.  
O sistema foi criado utilizando PHP,HTML,CSS e JS, sem frameworks e sem banco de dados, rodando em ambiente local com XAMPP.

## 🎯 Objetivo
- Aprender conceitos básicos de backend com PHP  
- Entender como funciona cadastro e login de usuários  
- Trabalhar com manipulação de arquivos  
- Utilizar sessões  
- Integrar JavaScript ao frontend  

## 🛠️ Tecnologias Utilizadas
- PHP  
- HTML5  
- CSS3  
- JavaScript  
- XAMPP (Apache + PHP)  

## ⚙️ Funcionamento do Sistema

### 🧾 Cadastro de Usuário (2 Etapas)

#### Etapa 1 — Dados Pessoais
O usuário deve preencher todos os campos:
- Nome completo  
- CPF  
- Endereço  
- Bairro  
- Cidade  
- Estado  
- CEP  

Esses dados são salvos em um arquivo `.dat` com o nome do usuário.

#### Etapa 2 — Dados de Acesso
O usuário informa:
- E-mail  
- Senha  

Essas informações são salvas em outro arquivo `.dat`, nomeado com o e-mail cadastrado.

> Não há validação de formato dos dados.  
> O sistema apenas verifica se todos os campos foram preenchidos.

### 🔐 Login
- O sistema verifica o e-mail e a senha salvos no arquivo `.dat`  
- Se os dados estiverem corretos, o usuário é redirecionado para a página principal  
- A sessão é criada apenas no momento do login  

### 🛒 Carrinho de Compras
- Desenvolvido em JavaScript  
- Funciona no frontend  
- Permite adicionar, remover produtos e alterar quantidades  
- Ainda não está integrado ao backend  

## 💾 Armazenamento de Dados
Os dados são armazenados em arquivos `.dat`:
- 1 arquivo para dados pessoais  
- 1 arquivo para e-mail e senha  

Cada usuário possui seus próprios arquivos.

## ⚠️ Limitações do Projeto
- Projeto educacional  
- Não utiliza banco de dados  
- Não possui validações avançadas  
- Não indicado para uso em produção  

## ✅ Conclusão
Este projeto permitiu aplicar, na prática, conceitos básicos de desenvolvimento web, como cadastro, login, sessão, manipulação de arquivos e uso de JavaScript.

## 🚧 Status do Projeto
Em desenvolvimento — Trabalho escolar