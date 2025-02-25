$(document).ready(function () {

    /*evento de erros para registo de utilizador*/

    $('.registo-form').on('submit', function(e) {
        e.preventDefault();

        let formRegisto = $(this).serialize();
        let url = "pages/registo/registo.php";
        $.ajax({
            url: url,
            type: "POST",
            data: formRegisto,
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                if (response.error) {
                    if (response.nome_error != '') {
                        $('#nome_error').text(response.nome_error);
                    } else {
                        $('#nome_error').text('');
                    }
                    if (response.email_error != '') {
                        $('#email_error').text(response.email_error);
                    } else {
                        $('#email_error').text('');
                    }
                    if (response.password_error != '') {
                        $('#password_error').text(response.password_error);
                    } else {
                        $('#password_error').text('');
                    }
                } else {
                    $('#nome_error').text('');
                    $('#email_error').text('');
                    $('#password_error').text('');
                    $('#success_message').text(response.message);
                    $('#registo-form')[0].reset();
                }
            }
      });

})});