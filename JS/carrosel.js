const slider = document.querySelectorAll('.slider');
const btnPrev = document.getElementById('prev-button');
const btnNext = document.getElementById('next-button');

let currentSlide = 0;

function hideSlider() {
  slider.forEach(item => item.classList.remove('on'))
}

function showSlider() {
  slider[currentSlide].classList.add('on')
}

// Função para avançar para a próxima posição do carrossel
function nextSlider() {
  hideSlider()
  if(currentSlide === slider.length -1) {
    currentSlide = 0
  } else {
    currentSlide++
  }
  showSlider()
}

// Função para voltar para a posição anterior do carrossel
function prevSlider() {
  hideSlider()
  if(currentSlide === 0) {
    currentSlide = slider.length -1
  } else {
    currentSlide--
  }
  showSlider()
}

// Chamando os botao  de avanca e volta
btnNext.addEventListener('click', nextSlider)
btnPrev.addEventListener('click', prevSlider)

// Auto-play a cada 10 segundos 
setInterval(nextSlider, 10000)