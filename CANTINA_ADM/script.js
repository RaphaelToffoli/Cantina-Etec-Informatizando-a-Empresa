

let acaoAtual = "";


function abrirTelaInserir(acao) {
  acaoAtual = acao; 
  document.getElementById("modal-estoque").style.display = "flex";
}


function fecharModal() {
  document.getElementById("modal-estoque").style.display = "none";
  document
    .querySelectorAll(".produtos")
    .forEach((div) => (div.style.display = "none"));
}


function mostrarProdutosPorCategoria(categoria) {
  document.querySelectorAll(".produtos").forEach((div) => {
    div.style.display = "none";
  });

  
  const divCategoria = document.getElementById("produtos-" + categoria);
  if (divCategoria) {
    divCategoria.style.display = "flex";

    
    divCategoria.querySelectorAll("form").forEach((form) => {
      if (form.classList.contains("form-" + acaoAtual)) {
        form.style.display = "flex";
      } else {
        form.style.display = "none";
      }
    });
  }
}
