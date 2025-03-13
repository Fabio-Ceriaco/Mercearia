$(document).ready(function () {
  $("#submitlogin").on("click", function (e) {
    e.preventDefault();
    let email = $("#email").val();
    let password = $("#password").val();
    let token = $("#csrf_token").val();
    let formLogin = {
      email: email,
      password: password,
      token: token,
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
          console.log(response.loged_user);
          sessionStorage.setItem("user_id", response.loged_user);
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-check-circle"></span>${response.message}</div>`
          );
          $(".result").show();
          setTimeout(() => {
            $(".result").hide();
            window.location.href = "./index.php";
          }, 500);
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
        }, 500);
      },
    });
  });
});
