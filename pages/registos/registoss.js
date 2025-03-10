$(document).ready(function () {
  $("body").on("submit", "#registoForm", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    let url = "./pages/registos/registos.php";
    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      dataType: "JSON",
      contentType: false,
      processData: false,
      success: function (response, textStatus, jqXHR) {
        console.log(response.erros);
        $(".result").text("");
        $("#emailError").text("");
        $("#idadeError").text("");
        $("#telefoneError").text("");
        $("#passwordError").text("");
        if (response.erros.campos_obrigatorios) {
          $(".result").append(
            `<div class="error"><span class="fa fa-check-circle"></span>${response.erros.campos_obrigatorios}</div>`
          ); // mostra o resultado
          $(".result").show();
          setTimeout(() => {
            $(".result").hide();
          }, 2000);
        } else if (response.erros.username_email) {
          $(".result").append(
            `<div class="error"><span class="fa fa-times-circle"></span>${response.erros.username_email}</div>`
          );
          $(".result").show();
          setTimeout(() => {
            $(".result").hide();
          }, 2000);
        } else if (response.sucesso) {
          $(".result").append(
            `<div class="success"><span class="fa fa-check-circle"></span>${response.sucesso}</div>`
          );
          $(".result").show();
          setTimeout(() => {
            $(".result").hide();
            window.location.href = "./index.php";
          }, 2000);
        } else {
          if (response.erros.email) {
            $("#emailError").text(response.erros.email);
          }
          if (response.erros.idade) {
            $("#data_nascimentoError").text(response.erros.idade);
          }
          if (response.erros.password) {
            $("#passwordError").text(response.erros.password);
          }
          if (response.erros.cpassword) {
            $("#cpasswordError").text(response.erros.cpassword);
          }
          if (response.erros.telefone) {
            $("#telefoneError").text(response.erros.telefone);
          }
          if (response.erros.nome) {
            $("#nomeError").text("");
            $("#nomeError").text(response.erros.nome);
          }
          if (response.erros.morada) {
            $("#moradaError").text("");
            $("#moradaError").text(response.erros.morada);
          }
          if (response.erros.nif) {
            $("#nifError").text("");
            $("#nifError").text(response.erros.nif);
          }
          if (response.erros.codpostal) {
            $("#codPostalError").text(response.erros.codpostal);
          }
          if (response.erros.localidade) {
            $("#localidadeError").text(response.erros.localidade);
          }

          if (response.erros.size) {
            $("#imgError").text("");
            $("#imgError").text(response.erros.size);
          }
          if (response.erros.extencao) {
            $("#imgError").text("");
            $("#imgError").text(response.erros.extencao);
          }
          if (response.erros.imagem) {
            $("#imgError").text("");
            $("#imgError").text(response.erros.imagem);
          }
        }
      },
    });
  });
});
