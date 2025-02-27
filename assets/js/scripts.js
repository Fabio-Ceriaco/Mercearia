$(document).ready(function () {
  //carregar o conteúdo principal
  $("#content").load("home.php");

  //evento para o link do logo
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

  //evento para o link do carrinho
  $("#cart").click(function (e) {
    e.preventDefault();
    if ($("#cart-content").css("display") === "none") {
      $("#cart-content").css("display", "flex");
    } else {
      $("#cart-content").css("display", "none");
    }
  });

  //evento para o botão de login
  $(".log-btn").click(function (e) {
    e.preventDefault();
    if ($(".login-section").css("display") === "none") {
      $(".login-section").css("display", "flex");
    } else {
      $(".login-section").css("display", "none");
    }
  });
  //evento botão close login
  $("#close").click(function (e) {
    e.preventDefault();
    $(".login-section").css("display", "none");
  });

  //evento para o botão de criar conta
  $(".registar a").click(function (e) {
    e.preventDefault();
    let page = $(this).attr("href");
    console.log(page);
    $(".login-section").css("display", "none");
    $("#clients").css("display", "none");
    $("#content").load(page + ".php", function (status) {
      if (status === "error") {
        console.log("Erro ao carregar o conteúdo solicitado.");
      } else {
        console.log("Página carregada com sucesso.");
      }
    });
  });

  // eventos para area cliente
  $(".user-info-btn").click(function (e) {
    e.preventDefault();
    if ($(".area-cliente").css("display") === "none") {
      $(".area-cliente").css("display", "flex");
    } else {
      $(".area-cliente").css("display", "none");
    }
  });
});
