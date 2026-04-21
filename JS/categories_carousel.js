// Selecionando os elementos do DOM necessários para o carrossel de categorias
const categorySliderContainer = document.querySelector('.container-categories-images'); // Container que contém todas as imagens das categorias
const categorySliders = document.querySelectorAll('.category-slider'); // Todas as imagens individuais das categorias
const btnPrevCategories = document.getElementById('prev-button-categories'); // Botão para voltar (seta esquerda)
const btnNextCategories = document.getElementById('next-button-categories'); // Botão para avançar (seta direita)

// Variáveis de controle do carrossel
let currentCategoryIndex = 0; // Índice atual da posição do carrossel (qual conjunto de imagens está sendo mostrado)
const sliderWidth = 160; // Largura de cada item do carrossel (150px da imagem + 20px de margem total)
const visibleSliders = 4; // Número de imagens visíveis ao mesmo tempo na tela

// Função para avançar para a próxima posição do carrossel
function nextCategorySlider() {
  // Verifica se ainda há imagens para mostrar à direita (não chegou ao final)
  if (currentCategoryIndex < categorySliders.length - visibleSliders) {
    currentCategoryIndex++; // Incrementa o índice para mover para a direita
    updateSliderPosition(); // Atualiza a posição visual do container
  }
}

// Função para voltar para a posição anterior do carrossel
function prevCategorySlider() {
  // Verifica se não está no início (índice maior que 0)
  if (currentCategoryIndex > 0) {
    currentCategoryIndex--; // Decrementa o índice para mover para a esquerda
    updateSliderPosition(); // Atualiza a posição visual do container
  }
}

// Função que calcula e aplica o movimento horizontal do container
function updateSliderPosition() {
  // Calcula o deslocamento em pixels (negativo para mover para a esquerda)
  const translateX = -currentCategoryIndex * sliderWidth;
  // Aplica a transformação CSS para mover o container
  categorySliderContainer.style.transform = `translateX(${translateX}px)`;
}

// Adicionando event listeners aos botões para chamar as funções de navegação
btnNextCategories.addEventListener('click', nextCategorySlider); // Quando clicar na seta direita, avança
btnPrevCategories.addEventListener('click', prevCategorySlider); // Quando clicar na seta esquerda, volta