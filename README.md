# projeto_loja_online_bloco17

### Trabalho Escolar | Desenvolvimento Web

## 📌 Sobre o Projeto

O **Bloco 17** é um projeto de loja virtual desenvolvido como trabalho escolar, com o objetivo de aplicar conceitos de desenvolvimento web utilizando tecnologias de frontend e backend.

O sistema foi desenvolvido utilizando **HTML, CSS, JavaScript, PHP e MySQL**, sem utilização de frameworks.

A aplicação é executada em ambiente local utilizando o **XAMPP para Apache e PHP**, enquanto o **MySQL** é utilizado para armazenamento e gerenciamento dos dados.

---

## 🎯 Objetivo

O projeto tem como principais objetivos:

* Aprender conceitos básicos de backend utilizando PHP;
* Entender o funcionamento de cadastro e autenticação de usuários;
* Trabalhar com banco de dados relacional utilizando MySQL;
* Utilizar sessões PHP para controle de usuários autenticados;
* Integrar JavaScript ao backend PHP;
* Trabalhar com armazenamento local utilizando `localStorage`;
* Utilizar consultas SQL como `SELECT`, `INSERT`, `UPDATE` e `DELETE`;
* Trabalhar com relacionamentos entre tabelas;
* Consumir uma API externa para preenchimento de endereço.

---

## 🛠️ Tecnologias Utilizadas

* HTML5
* CSS3
* JavaScript
* PHP
* MySQL
* XAMPP
* Apache
* ViaCEP API
* Remix Icon
* GSAP

---

## ⚙️ Funcionamento do Sistema

### 🧾 Cadastro de Usuário

O cadastro é dividido em duas etapas.

#### Etapa 1 — Dados Pessoais

O usuário informa:

* Nome completo;
* CPF;
* CEP;
* Endereço;
* Número;
* Bairro;
* Cidade;
* Estado.

O sistema realiza a validação do **CPF** antes de continuar o cadastro.

O **CEP** é utilizado para consultar a API ViaCEP, permitindo preencher automaticamente informaçõe
