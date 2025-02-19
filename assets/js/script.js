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


    $('#cart').click(function (e) { 
      e.preventDefault();
      if($('#cart-content').css('display') === 'none'){
      $('#cart-content').css('display', 'flex');
    }else{
      $('#cart-content').css('display', 'none');
    }
    });

    
  
  });

/*=================================================================CARRINHO===================================================================================================== */

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
              $('.result').text('');
               if (data['status'] ==='success') {
                    console.log(textStatus);
                    console.log(data);
                   $('.result').append(`<div class="${data['status']}"><span class="fa fa-check-circle"></span>${data['message']}</div>`);
                   $('.result').show();
                   $('#count').attr('value', data['count']);
                   $('#total').attr('value', `${data['total']} €`);
                   $('.prod-img').attr('src', data['imagem']);
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
        
  });




  //remover artigos do carrinho

  $('body').on('click', '.remove', function (e) {
    e.preventDefault();
      const id = $(this).attr('data-id');
      const url = 'assets/js/remover.php';
     console.log(id);
      $.ajax({
          url: url,
          type: 'POST',
          data: {id:id},
          dataType: 'JSON',
          success: function (data, textStatus, jqXHR) {
              $('.result').text('');
             if (data['status'] ==='success') {
                  console.log(textStatus);
                  console.log(data);
                 $('.result').append(`<div class="${data['status']}"><span class="fa fa-check-circle"></span>${data['message']}</div>`);
                 $('.result').show();
                 $('#count').attr('value', data['count']);
                 $('#total').attr('value', `${data['total']} €`);
                 if(data['count'] <= 0){
                  $('.cart-content').prepend('<span>Carrinho vazio</span>');
                  $(`#${data['produto_id']}`).remove();
                 
                 }else{
                  $(`#${data['produto_id']}`).remove();
                 }
                 
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

  $('.cart').on('click', function (e) { 
    e.preventDefault();
    $('#cart-content').load('assets/js/cartContent.php');
  });

});




