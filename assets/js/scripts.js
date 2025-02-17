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
  });



  //adicionar artigos ao carrinho

  $(document).ready(function () {

    //comprar produto

    $('body').on('click', '.cart-btn', function (e) {
      e.preventDefault();
        const id = $(this).attr('data-id');
        const url = 'assets/js/cart.php';
       
        $.ajax({
            url: url,
            type: 'POST',
            data: {id:id},
            dataType: 'JSON',
            success: function (data, textStatus, jqXHR) {
                console.log(textStatus);
                $('.result').text('');
               if (data['status'] ==='success') {
                    console.log(textStatus);
                   $('.result').append(`<div class="${data['status']}"><span class="fa fa-check-circle"></span>${data['message']}</div>`);
                   $('.result').show();
                   console.log(jqXHR);
               }else if (data['status'] === 'info') {
                   $('.result').append(`<div class="${data['status']}"><span class="fa fa-info-circle"></span>${data['message']}</div>`);	
                   $('.result').show();
                }else if (data['status'] === 'warning') {
                     $('.result').append(`<div class="${data['status']}"><span class="fa fa-exclamation-triangle"></span>${data['message']}</div>`);
                     $('.result').show();
                } else { 
                    $('.result').append(`<div class="${data['status']}"><span class="fa fa-times-circle"></span>${data['message']}</div>`);
                    $('.result').show();
                }
            

                setTimeout(function () {
                    $('.result').hide('');
                    if(data['redirect']){
                        window.location.href = data['redirect'];
                    }
                }, 3000);
            }
        });
        
  })

});




