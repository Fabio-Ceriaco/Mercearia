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
        console.log(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("Error: " + errorThrown);
      },
    });
  });
});
