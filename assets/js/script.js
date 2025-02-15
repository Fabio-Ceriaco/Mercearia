$(document).ready(function () {
  //carregar o conteúdo principal
  $("#content").load("populares.php");
  $("#logo").click(function (e) {
    e.preventDefault();
    let page = $(this).attr("href");
    $("#content").load(page + ".php", function (status) {
      if (status === "error") {
        console.log("Erro ao carregar o conteúdo solicitado.");
      } else {
        console.log("Página carregada com sucesso");
      }
    });
  });
  //evento para os linkes da barra de navegação
  $("#navBar li a").click(function (e) {
    e.preventDefault();
    $("#navBar li a").removeClass("active"); //Remover a class 'active' de todos
    $(this).addClass("active"); //adicionar a class 'active' no link clicado

    let page = $(this).attr("href"); //obter o valor href
    $("#content").load(page + ".php", function (status) {
      if (status === "error") {
        console.log("Erro ao carregar o conteúdo solicitado.");
      } else {
        console.log("Página carregada com sucesso.");
      }
    });
  });
});
