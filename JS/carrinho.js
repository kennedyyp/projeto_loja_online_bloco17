const CATALOGO = [
  {
    id: 1,
    nome:      "Camiseta Dri Fit",
    categoria: "Camisas",
    preco:     89.90,
    img:       "imgs/categories/camisas.jpg"
  },
  {
    id: 2,
    nome:      "Regata Dri Fit",
    categoria: "Camisas",
    preco:     189.90,
    img:       "imgs/categories/camisas.jpg"
  },
  {
    id: 3,
    nome:      "Camiseta Oversized Essential",
    categoria: "Camisas",
    preco:     149.90,
    img:       "imgs/categories/camisas.jpg"
  },
  {
    id: 4,
    nome:      "Short TM",
    categoria: "Shorts",
    preco:     50.90,
    img:       "imgs/categories/shorts/1.jpg"
  },
  {
    id: 5,
    nome:      "Shorts dri fit",
    categoria: "Shorts",
    preco:     189.90,
    img:       "imgs/categories/shorts/2.jpg"
  },
  {
    id: 6,
    nome:      "Shorts Oversized Essential",
    categoria: "Shorts",
    preco:     149.90,
    img:       "imgs/categories/camisas.jpg"
  }
  /* adicione mais produtos aqui seguindo o mesmo formato */
]

/* 
   Area dos cupons
   0.10 = 10% de desconto, 0.20 = 20%, etc. */
const CUPONS = {
  "KENNY": 0.20
}

/* 
   PARCELAS
*/
const PARCELAS = 12



let descontoAtivo = 0

/*  localStorage - vai guarda no bowser */
function lerCarrinho() {
  return JSON.parse(localStorage.getItem("bloco17_carrinho") || "[]")
}

function salvarCarrinho(carrinho) {
  localStorage.setItem("bloco17_carrinho", JSON.stringify(carrinho))
}

/*  Adicionar ao carrinho (chame nos botões das páginas de produto)  */
function adicionarAoCarrinho(idProduto) {
  const produto = CATALOGO.find(p => p.id === idProduto)

  /* Pra eu não me perde caso adiciona algo errado */
  if (!produto) {
    console.warn("Produto id=" + idProduto + " não encontrado no CATALOGO.")
    return
  }

  const carrinho  = lerCarrinho()
  const existente = carrinho.find(i => i.id === idProduto)

  if (existente) {
    existente.quantidade++
  } else {
    carrinho.push({ id: produto.id, quantidade: 1 })
  }

  salvarCarrinho(carrinho)
  mostrarFeedback(produto.nome + " adicionado ao carrinho!")
}

/* Renderiza a tabela do carrinho tlgd*/
function renderizarCarrinho() {
  const tbody = document.querySelector("tbody")
  if (!tbody) return

  const carrinho = lerCarrinho()
  tbody.innerHTML = ""

  if (carrinho.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="5" style="
          text-align:center;
          padding:48px;
          color:#6B6B6B;
          font-size:13px;
          letter-spacing:.08em;
          text-transform:uppercase;
        ">
          Seu carrinho está vazio
        </td>
      </tr>`
    atualizarTotal(0)
    return
  }

  let subtotalGeral = 0

  carrinho.forEach(item => {
    const produto = CATALOGO.find(p => p.id === item.id)
    if (!produto) return

    const subtotal = produto.preco * item.quantidade
    subtotalGeral += subtotal

    const tr = document.createElement("tr")
    tr.dataset.id = produto.id

    tr.innerHTML = `
      <td>
        <div class="produto">
          <img src="${produto.img}" alt="${produto.nome}">
          <div class="info">
            <div class="name">${produto.nome}</div>
            <div class="categoria">${produto.categoria}</div>
          </div>
        </div>
      </td>
      <td>R$ ${produto.preco.toFixed(2).replace(".", ",")}</td>
      <td>
        <div class="ajeita">
          <button class="qty-btn btn-menos">-</button>
          <span class="qty-num">${item.quantidade}</span>
          <button class="qty-btn btn-mais">+</button>
        </div>
      </td>
      <td>R$ ${subtotal.toFixed(2).replace(".", ",")}</td>
      <td>
        <button class="remove btn-remover">
          <i class="ri-close-fill"></i>
        </button>
      </td>
    `

    tbody.appendChild(tr)
  })

  atualizarTotal(subtotalGeral)
  vincularBotoes()
}

/*  Atualiza total e parcelas */
function atualizarTotal(subtotal) {
  const totalEl   = document.getElementById("totalValor")
  const parcelaEl = document.querySelector(".total-parcelado span")
  if (!totalEl) return

  const total   = subtotal * (1 - descontoAtivo)
  const parcela = total / PARCELAS

  totalEl.textContent = "R$ " + total.toFixed(2).replace(".", ",")

  if (parcelaEl) {
    parcelaEl.textContent = PARCELAS + "x de R$ " + parcela.toFixed(2).replace(".", ",")
  }
}

/*  Vincula botões + - e o de remover */
function vincularBotoes() {
  const tbody = document.querySelector("tbody")
  if (!tbody) return

  tbody.querySelectorAll(".btn-mais").forEach(btn => {
    btn.addEventListener("click", () => {
      alterarQuantidade(Number(btn.closest("tr").dataset.id), +1)
    })
  })

  tbody.querySelectorAll(".btn-menos").forEach(btn => {
    btn.addEventListener("click", () => {
      alterarQuantidade(Number(btn.closest("tr").dataset.id), -1)
    })
  })

  tbody.querySelectorAll(".btn-remover").forEach(btn => {
    btn.addEventListener("click", () => {
      removerItem(Number(btn.closest("tr").dataset.id))
    })
  })
}

/*  Alterar quantidade */
function alterarQuantidade(id, delta) {
  let carrinho = lerCarrinho()
  const item   = carrinho.find(i => i.id === id)
  if (!item) return

  item.quantidade += delta

  if (item.quantidade <= 0) {
    carrinho = carrinho.filter(i => i.id !== id)
  }

  salvarCarrinho(carrinho)
  renderizarCarrinho()
}

/*  Remover item  */
function removerItem(id) {
  salvarCarrinho(lerCarrinho().filter(i => i.id !== id))
  renderizarCarrinho()
}

/*  Cupom de desconto  */
function iniciarCupom() {
  const btn   = document.querySelector(".btn-cupom")
  const input = document.getElementById("inputCupom")
  const aviso = document.getElementById("cupomOk")
  if (!btn || !input) return

  btn.addEventListener("click", () => {
    const codigo = input.value.trim().toUpperCase()
    const desc   = CUPONS[codigo]

    if (desc !== undefined) {
      descontoAtivo = desc
      input.style.borderColor = "#5eb87a"
      if (aviso) {
        aviso.textContent  = "✓ Cupom aplicado — " + (desc * 100) + "% de desconto"
        aviso.style.color  = "#5eb87a"
        aviso.style.display = "block"
      }
      renderizarCarrinho()
    } else {
      input.style.borderColor = "#C1121F"
      if (aviso) {
        aviso.textContent  = "✗ Cupom inválido"
        aviso.style.color  = "#C1121F"
        aviso.style.display = "block"
      }
    }
  })
}



/* Inicialização */
document.addEventListener("DOMContentLoaded", () => {
  renderizarCarrinho()
  iniciarCupom()
})

/* Confirmação do pedido  */

const PARCELAS_CONF    = 12
let pagamentoSelecionado = "PIX"
let usuarioLogado        = null

// Marca a opção de pagamento selecionada mas so tem uma msm kk
function selectPayment(el) {
  document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'))
  el.classList.add('selected')
  pagamentoSelecionado = el.querySelector('input[type=radio]').value
}

// Clicou em "Finalizar compra" verifica login antes de ir pra confirmação
function irParaConfirmacao() {
  const carrinho = lerCarrinho()
  if (carrinho.length === 0) {
    mostrarFeedback("Seu carrinho está vazio!")
    return
  }

  fetch("php/sessao.php")
    .then(r => r.text())
    .then(text => {
      const partes = text.trim().split("|")

      if (partes[0] !== "ok") {
        localStorage.setItem("bloco17_redirect", "carrinho.html")
        window.location.href = "login.html"
        return
      }

      usuarioLogado = { email: partes[2], nome: partes[1] }
      document.getElementById("secaoCarrinho").style.display    = "none"
      document.getElementById("secaoConfirmacao").style.display = "block"
      renderizarConfirmacao()
      exibirUsuario(usuarioLogado)
    })
    .catch(() => {
      mostrarFeedback("Erro ao verificar login. Abra pelo XAMPP!")
    })
}

// Volta para a seção do carrinho
function voltarCarrinho() {
  document.getElementById("secaoConfirmacao").style.display = "none"
  document.getElementById("secaoCarrinho").style.display    = "block"
}

// Preenche a tabela de confirmação com os itens du carrinho
function renderizarConfirmacao() {
  const tbody     = document.getElementById("tbodyConfirmacao")
  const totalEl   = document.getElementById("totalConfirmacao")
  const parcelaEl = document.getElementById("parcelaConf")
  if (!tbody) return

  const carrinho = lerCarrinho()
  tbody.innerHTML = ""
  let total = 0

  carrinho.forEach(item => {
    const produto = CATALOGO.find(p => p.id === item.id)
    if (!produto) return

    const subtotal = produto.preco * item.quantidade
    total += subtotal

    const tr = document.createElement("tr")
    tr.innerHTML = `
      <td>
        <div class="produto">
          <img src="${produto.img}" alt="${produto.nome}">
          <div class="info">
            <div class="name">${produto.nome}</div>
            <div class="categoria">${produto.categoria}</div>
          </div>
        </div>
      </td>
      <td>R$ ${produto.preco.toFixed(2).replace(".", ",")}</td>
      <td>${item.quantidade}</td>
      <td>R$ ${subtotal.toFixed(2).replace(".", ",")}</td>
    `
    tbody.appendChild(tr)
  })

  if (totalEl)   totalEl.textContent   = "R$ " + total.toFixed(2).replace(".", ",")
  if (parcelaEl) parcelaEl.textContent = PARCELAS_CONF + "x de R$ " + (total / PARCELAS_CONF).toFixed(2).replace(".", ",")
}

// Mostra o nome e email do usuário logado na tela
function exibirUsuario(dados) {
  const wrap = document.getElementById("usuarioWrap")
  if (!wrap) return
  wrap.innerHTML = `
    <div class="usuario-info">
      <span class="total-label">Comprando como</span>
      <div class="usuario-email">
        <i class="ri-user-fill"></i> ${dados.email}
        ${dados.nome ? `<span style="color:#6B6B6B;font-weight:400">— ${dados.nome}</span>` : ""}
      </div>
    </div>
  `
}

// Envia o pedido para o PHP salvar o arquivo .dat
function confirmarPedido() {
  const carrinho = lerCarrinho()
  if (carrinho.length === 0) {
    mostrarFeedback("Carrinho vazio!")
    return
  }

  const itens = carrinho.map(item => {
    const produto = CATALOGO.find(p => p.id === item.id)
    return {
      nome:       produto ? produto.nome              : "Produto",
      preco:      produto ? produto.preco             : 0,
      quantidade: item.quantidade,
      subtotal:   produto ? produto.preco * item.quantidade : 0
    }
  })

  const totalNum = itens.reduce((acc, i) => acc + i.subtotal, 0)

  fetch("php/salvar_venda.php", {
    method:  "POST",
    headers: { "Content-Type": "application/json" },
    body:    JSON.stringify({ itens, total: totalNum, pagamento: pagamentoSelecionado })
  })
  .then(r => r.text())
  .then(text => {
    const partes = text.trim().split("|")
    if (partes[0] === "ok") {
      localStorage.removeItem("bloco17_carrinho")
      mostrarFeedback("Pedido #" + partes[1] + " confirmado!")
      setTimeout(() => { window.location.href = "main.html" }, 2500)
    } else {
      mostrarFeedback("Erro ao salvar pedido. Tente novamente.")
    }
  })
  .catch(() => {
    mostrarFeedback("Erro de conexão. Tente novamente.")
  })
}

 /*
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⢿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⠟⢿⡟⠀⠘⠋⠁⠈⠿⠿⠿⠛⠛⠉⠁⠀⣾⣿⣿⣿⣿
⣿⣇⠀⠈⠉⠉⠉⠛⠋⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⣿⣿⣿⣿⣿
⣿⣿⣆⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣠⣶⠀⠀⠀⠀⠿⠟⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⠀⠀⠠⣤⣤⣀⠀⠀⠀⠀⣴⢻⡿⠋⠀⡀⠀⠀⠀⠴⢿⣿⣿⣿⣿
⣿⣿⣿⣷⡄⠀⠀⠀⠙⠛⠘⠃⠀⠀⠀⠀⢀⣀⡀⣾⠇⠀⠀⠀⠀⠻⣿⣿⣿⣿
⣿⣿⣿⡿⠂⠀⠀⠘⣶⢠⣴⠀⣶⣶⣾⡇⣿⣿⡇⠟⠀⠀⠀⠀⠀⠀⠈⢿⣿⣿
⣿⣿⡟⠁⠀⠀⠀⠀⠈⠸⢿⠀⣿⣿⣿⡇⡿⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠈⢿⣿
⣿⣿⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢠⡀⠀⠀⢸⣿
⣿⣇⡄⣀⣰⣷⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣼⣿⣾⣾⣾⣿
⣿⣿⣿⣿⣿⣿⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠰⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⡏⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⠀⠀⠀⠀⣀⣴⣶⣄⠀⢀⣠⣶⣦⡀⠀⠀⠀⠀⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣷⣶⣶⣶⣿⣿⣿⣿⣿⣷⣿⣿⣿⣿⣿⣶⣶⣶⣶⣾⣿⣿⣿⣿⣿
Pedro Kenndy e João Pedro */
