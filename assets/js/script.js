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
            success: function (response, textStatus, jqXHR) {
              $('.result').text('');
               if (response.status ==='success') {
                    console.log(textStatus);
                   $('.result').append(`<div class="${response.status}"><span class="fa fa-check-circle"></span>${response.message}</div>`);
                   $('.result').show();
                   $('#count').attr('value', response.count);
                   $('#total').attr('value', `${response.total} €`);
                   let cartItem = '';
                   let carrinho = JSON.parse(localStorage.getItem('cartItem')) || [];
                    if(response.cart_items[0].id ){
                      $('.empty').remove();
                      $(`#${response.cart_items[0].id}`).remove();
                      response.cart_items.forEach(item => {
                        carrinho.push(item);
                            cartItem += `<div class="in-cart-content" id="${item.id}" >
                            <img src="${item.imagemproduto}" alt="${item.nomeproduto}" class="prod-img">
                            <input class="prod-nome" type="text" value="${item.nomeproduto}" readonly>
                            <div class="cart-quantity">
                                <input type="button" value="-" data-id="${item.id}"class="minus">
                                <input class="quantidade" type="text" value="${item.quantidade}" readonly>
                                <input type="button" value="+" data-id="${item.id}"  class="plus">
                            </div>
                            <input class="prod-preco" type="text" value="${item.preco}" readonly>
                            <input type="button" data-id="${item.id}" value="X" class="remove">
                            </div>
                            `;
                        })
                        $('.in-cart').prepend(cartItem);
                        localStorage.setItem('cartItem', JSON.stringify(carrinho));
                      };   
                } else { 
                    $('.result').append(`<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`);
                    $('.result').show();
                }
                setTimeout(function () {
                    $('.result').hide('');
                    if(response.redirect){
                        window.location.href = response.redirect;
                    }
                }, 2000);
            },
        });
  });

  //atualizar quantidade de artigos no carrinho
  $('body').on('click', '.plus', function (e) {
    e.preventDefault();
    const id = $(this).attr('data-id');
    const url = 'assets/js/mais.php';
    
    $.ajax({
      url: url,
      type: 'POST',
      data: {id:id},
      dataType: 'JSON',
      success: function (response, textStatus, jqXHR) {
        $('.result').text('');
        console.log(response);
         if (response.status ==='success') {
          console.log(textStatus);
          $('.result').append(`<div class="${response.status}"><span class="fa fa-check-circle"></span>${response.message}</div>`);
          $('.result').show();
          $('#count').attr('value', response.count);
          $('#total').attr('value', `${response.total} ���`);
          $(`#${response.produto_id}.quantidade`).val(response.quantidade);
        } else {
          $('.result').append(`<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`);
          $('.result').show();
  
      }
      setTimeout(function () {
        $('.result').hide('');
        if(response.redirect){
            window.location.href = response.redirect;
        }
    }, 2000);
  }
  
    
}
)
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
          success: function (response, textStatus, jqXHR) {
              $('.result').text('');
             if (response.status  ==='success') {
                  console.log(textStatus);
                 $('.result').append(`<div class="${response.status }"><span class="fa fa-check-circle"></span>${response.message}</div>`);
                 $('.result').show();
                 $('#count').attr('value', response.count);
                 $('#total').attr('value', `${response.total} €`);
                 let carrinho = JSON.parse(localStorage.getItem('cartItem')) || [];
                 if(response.count <= 0){
                  $('label').remove();
                  $('.checkout-btn').remove();
                  $('.in-cart').prepend('<span class="empty">Carrinho vazio</span><label for="total" class="total" >Total: <input name="total" id="total" type="text" value="0.00 €" readonly></label><input class="checkout-btn" type="button" value="Checkout">');
                  $(`#${response.produto_id}`).remove();
                  carrinho = [];
                 }else{
                  //remover item do localStorage carrinho
                  carrinho = carrinho.filter(item => item.id!== response.produto_id);
                  $(`#${response.produto_id}`).remove();
                 }
                 localStorage.setItem('cartItem', JSON.stringify(carrinho));
              } else { 
                  $('.result').append(`<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`);
                  $('.result').show();
              }
              setTimeout(function () {
                  $('.result').hide('');
                  if(response.redirect){
                      window.location.href = response.redirect;
                  }
              }, 2000);
          }
      });
      
});
});




$('body').ready(function () {
  let cartItem = '';
  const saveCarrinho = JSON.parse(localStorage.getItem('cartItem')) || [];
  let total = 0;
    if(saveCarrinho.length > 0){
      $('#count').attr('value', saveCarrinho.length);
      
      for(let i = 0; i < saveCarrinho.length; i++){
        const item = saveCarrinho[i];
        total += item.quantidade * item.preco;
      }
      $('#total').attr( 'value', `${total.toFixed(2)} €`);
      $('.empty').remove();
      $('lebel').remove();
      saveCarrinho.forEach(item => {
         cartItem = `<div class="in-cart-content" id="${item.id}" >
        <img src="${item.imagemproduto}" alt="${item.nomeproduto}" class="prod-img">
        <input class="prod-nome" type="text" value="${item.nomeproduto}" readonly>
        <div class="cart-quantity">
        <input type="button" value="-" data-id="${item.id}" class="minus">
        <input class="quantidade" type="text" value="${item.quantidade}" readonly>
        <input type="button" value="+" data-id="${item.id}" class="plus">
        </div>
        <input class="prod-preco" type="text" value="${item.preco}" readonly>
        <input type="button" data-id="${item.id}" value="X" class="remove">
        </div>`;
        $('.in-cart').prepend(cartItem);
      })};
      $('.cart-content').append(`<label for="total">Total: <input name="total" class="total" id="total" type="text" value="${total.toFixed(2)} €" readonly></span><input class="checkout-btn" type="button" value="Checkout">`);
});
$('.cart').on('click', function () { 
  
  if($('.cart-content').css('display') === 'none'){
    $('.cart-content').css('display', 'flex');
  }else{
    $('.cart-content').css('display', 'none');
  }


});
