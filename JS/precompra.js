// ID do produto desta página — deve bater com o CATALOGO do carrinho.js
const ID_PRODUTO_PAGINA = 1

// Contador de quantidade
let q = 1

document.getElementById('mais').onclick = function() {
    q++
    document.getElementById('qty').textContent = q
}

document.getElementById('menos').onclick = function() {
    if (q > 1) {
        q--
        document.getElementById('qty').textContent = q
    }
}

// Seletor de tamanho
document.querySelectorAll('.tam:not(.esgotado)').forEach(function(b) {
    b.onclick = function() {
        document.querySelectorAll('.tam').forEach(function(x) { x.classList.remove('sel') })
        b.classList.add('sel')
    }
})

// Adiciona a quantidade escolhida ao carrinho
function adicionarQtd() {
    for (var i = 0; i < q; i++) {
        adicionarAoCarrinho(ID_PRODUTO_PAGINA)
    }
}

// Salvar no Carrinho — adiciona e mostra toast
function salvarCarrinhoBotao() {
    adicionarQtd()
}

// Comprar Agora — adiciona e redireciona para o carrinho
function comprarAgora() {
    adicionarQtd()
    setTimeout(function() {
        window.location.href = "carrinho.html"
    }, 600)
}