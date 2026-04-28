const categorySliderContainer = document.querySelector('.container-categories-images'); 
const categorySliders = document.querySelectorAll('.category-slider');
const btnPrevCategories = document.getElementById('prev-button-categories');
const btnNextCategories = document.getElementById('next-button-categories'); 

// Variáveis de controle do carrossel 
let currentCategoryIndex = 0; 
const sliderWidth = 160; 
const visibleSliders = 5; 

// Função para avançar para a próxima posição do carrossel
function nextCategorySlider() {
  // Verifica se ainda há imagens para mostrar à direita (não chegou ao final)
  if (currentCategoryIndex < categorySliders.length - visibleSliders) {
    currentCategoryIndex++; 
    updateSliderPosition(); 
  }
}

// Função para voltar para a posição anterior do carrossel
function prevCategorySlider() {
  // Verifica se não está no início (índice maior que 0)
  if (currentCategoryIndex > 0) {
    currentCategoryIndex--; 
    updateSliderPosition(); 
  }
}

// Função que calcula e aplica o movimento horizontal do container
function updateSliderPosition() {
  // Calcula o deslocamento em pixels (negativo para mover para a esquerda)
  const translateX = -currentCategoryIndex * sliderWidth;
 
  categorySliderContainer.style.transform = `translateX(${translateX}px)`;
}

/* Chamando os botao  de avanca e volta*/
btnNextCategories.addEventListener('click', nextCategorySlider); 
btnPrevCategories.addEventListener('click', prevCategorySlider); 