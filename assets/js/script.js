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

    $(".login-section").css("display", "none");
    $("#clients").css("display", "none");
    $("#content").load("./pages/registos/registosForm.php", function (status) {
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

  // evento para os links da area cliente

  $(".area-cliente a").click(function (e) {
    e.preventDefault();
    let page = $(this).attr("href");

    $("#content").load(page + ".php", function (status) {
      if (status === "error") {
        console.log("Erro ao carregar o conteúdo solicitado.");
      } else {
        console.log("Página carregada com sucesso.");
        $(".area-cliente").css("display", "none");
      }
    });
  });

  // evento retirar readonly do input

  $("body").on("click", "#editar", function (e) {
    e.preventDefault();
    if ($("#guardar").css("display") === "none") {
      $("#guardar").css("display", "flex");
      $("#editar").css("display", "none");
    } else {
      $("#guardar").css("display", "none");
      $("#editar").css("display", "flex");
    }
    $("#dadosForm input").prop("readonly", false); // Enable the input field
    $("input[type='file']").prop("disabled", false); // Enable the submit button
  });

  //evento close
  $("body").on("click", "#close", function (e) {
    e.preventDefault();
    window.location.href = "./index.php";
  });
});
