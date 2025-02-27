$(document).ready(function () {
  $("#submitlogin").on("click", function (e) {
    e.preventDefault();
    let email = $("#email").val();
    let password = $("#password").val();
    let formLogin = {
      email: email,
      password: password,
    };
    let url = "pages/logs/login.php";
    $.ajax({
      url: url,
      type: "POST",
      data: formLogin,
      dataType: "JSON",
      success: function (response) {
        $(".result").text("");
        if (response.status == "success") {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-check-circle"></span>${response.message}</div>`
          );
          $(".result").show();
        } else {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`
          );
          $(".result").show();
        }

        if ($(".login-section").css("display") === "flex") {
          $(".login-section").css("display", "none");
        }
        setTimeout(() => {
          $(".result").hide();
          window.location.reload();
        }, 500);
      },
    });
  });
});
