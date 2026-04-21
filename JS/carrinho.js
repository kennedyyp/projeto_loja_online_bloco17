/* ============================================================
   carrinho.js — Bloco 17
   Coloque em: JS/carrinho.js
   Adicione antes de </body> em TODAS as páginas:
   <script src="JS/carrinho.js"></script>
============================================================ */

/* ============================================================
   BLOCO 1 — CATÁLOGO DE PRODUTOS
   Para adicionar produto novo: copie um bloco { } e cole abaixo.
   No botão da página de produtos use:
   onclick="adicionarAoCarrinho(ID_DO_PRODUTO)"
============================================================ */
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
  }
  /* adicione mais produtos aqui seguindo o mesmo formato */
]

/* ============================================================
   BLOCO 2 — CUPONS VÁLIDOS
   0.10 = 10% de desconto, 0.20 = 20%, etc.
============================================================ */
const CUPONS = {
  "BLOCO10": 0.10,
  "BLOCO20": 0.20
}

/* ============================================================
   BLOCO 3 — PARCELAS
============================================================ */
const PARCELAS = 12

/* ────────────────────────────────────────────────────────────
   NÃO PRECISA MEXER ABAIXO DAQUI
──────────────────────────────────────────────────────────── */

let descontoAtivo = 0

/* ── localStorage ── */
function lerCarrinho() {
  return JSON.parse(localStorage.getItem("bloco17_carrinho") || "[]")
}

function salvarCarrinho(carrinho) {
  localStorage.setItem("bloco17_carrinho", JSON.stringify(carrinho))
}

/* ── Adicionar ao carrinho (chame nos botões das páginas de produto) ── */
function adicionarAoCarrinho(idProduto) {
  const produto = CATALOGO.find(p => p.id === idProduto)

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

/* ── Renderiza a tabela do carrinho ── */
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

/* ── Atualiza total e parcelas ── */
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

/* ── Vincula botões + - e remover ── */
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

/* ── Alterar quantidade ── */
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

/* ── Remover item ── */
function removerItem(id) {
  salvarCarrinho(lerCarrinho().filter(i => i.id !== id))
  renderizarCarrinho()
}

/* ── Cupom de desconto ── */
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

/* ── Toast de feedback ── */
function mostrarFeedback(msg) {
  const anterior = document.getElementById("_toast_bloco17")
  if (anterior) anterior.remove()

  const toast = document.createElement("div")
  toast.id = "_toast_bloco17"
  toast.style.cssText = `
    position: fixed;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%) translateY(60px);
    background: #C1121F;
    color: #fff;
    font-family: 'Urbanist', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 12px 24px;
    border-radius: 8px;
    z-index: 99999;
    opacity: 0;
    transition: all .3s ease;
    pointer-events: none;
  `
  toast.textContent = msg
  document.body.appendChild(toast)

  requestAnimationFrame(() => {
    toast.style.opacity   = "1"
    toast.style.transform = "translateX(-50%) translateY(0)"
  })

  setTimeout(() => {
    toast.style.opacity   = "0"
    toast.style.transform = "translateX(-50%) translateY(60px)"
    setTimeout(() => toast.remove(), 400)
  }, 2400)
}

/* ── Inicialização ── */
document.addEventListener("DOMContentLoaded", () => {
  renderizarCarrinho()
  iniciarCupom()
})