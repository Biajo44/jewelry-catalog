//* botão para cima *//
const btnTop = document.getElementById("btnTop");

//* exibir quando rolar para baixo *//
window.addEventListener("scroll", () => {
  if (window.scrollY > 200) {
    btnTop.style.display = "block";
  } else {
    btnTop.style.display = "none";
  }
});

//* ação do clique *//
btnTop.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});

