/*  Toast de Aviso  */

document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search)
  const msg = params.get("msg")

  if (!msg) return

  if (msg === "atualizadu") {
    mostrarFeedback("Conta atualizada com sucesso")
  }

  if (msg === "naotem") {
    mostrarFeedback("Conta não encontrada")
  }
})




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