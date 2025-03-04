// adicionar artigos ao carrinho

$(document).ready(function () {
  $("body").on("click", ".cart-btn", function (e) {
    e.preventDefault();
    const id = $(this).attr("data-id");
    const url = "pages/cart/cart.php";
    $.ajax({
      url: url,
      type: "POST",
      data: { id: id },
      dataType: "JSON",
      success: function (response, textStatus, jqXHR) {
        $(".result").text("");

        if (response.status === "success") {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-check-circle"></span>${response.message}</div>`
          ); // mostra o resultado
          $(".result").show();
          $("#count").attr("value", response.count); // atualiza o contador de itens no carrinho
          $("#total").attr("value", `${response.total} €`); // atualiza o preço total do carrinho
          let cartItem = "";
          let carrinho = JSON.parse(localStorage.getItem("cartItem")) || []; // recupera os itens do carrinho caso existam
          if (response.cart_items[0].id) {
            $(".empty").remove();
            $(`#${response.cart_items[0].id}`).remove(); // remove o item do carrinho caso ele já exista
            response.cart_items.forEach((item) => {
              let itemExiste = carrinho.find(
                (cartItem) => cartItem.id === item.id
              ); // verifica se o item já existe no carrinho
              if (itemExiste) {
                if (itemExiste.quantidade !== item.quantidade) {
                  // se a quantidade do item no carrinho for diferente da quantidade do item no pedido
                  itemExiste.quantidade = item.quantidade; // atualiza a quantidade do item no carrinho
                }
              } else {
                carrinho.push(item); // senão adiciona o item ao carrinho
              }
              //programar forma de adicionar user_id ao botão checkout do carrinho
              cartItem += `<div class="in-cart-content" id="${item.id}" >
                            <input class="hidden" type="hidden" value="${item.user_id}">
                            <img src="${item.imagemproduto}" alt="${item.nomeproduto}" class="prod-img">
                            <input class="prod-nome" type="text" value="${item.nomeproduto}" readonly>
                            <div class="cart-quantity-${item.id}">
                                <input type="button" value="-" data-id="${item.id}"class="minus">
                                <input class="quantidade" type="text" value="${item.quantidade}" readonly>
                                <input type="button" value="+" data-id="${item.id}"  class="plus">
                            </div>
                            <input class="prod-preco" type="text" value="${item.preco}" readonly>
                            <input type="button" data-id="${item.id}" value="X" class="remove">
                            </div>
                            `;
            });
            $(".in-cart").prepend(cartItem); // adiciona os itens do carrinho ao topo da lista
            localStorage.setItem("cartItem", JSON.stringify(carrinho)); // guarda os itens do carrinho no local storage
          }
        } else {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`
          );
          $(".result").show();
        }
        setTimeout(function () {
          // esconde o resultado após 2 segundos
          $(".result").hide("");
          if (response.redirect) {
            window.location.href = response.redirect;
          }
        }, 2000);
      },
    });
  });

  /*=============================================================================================================================================================================*/
  //atualizar quantidade de artigos no carrinho
  $("body").on("click", ".plus", function (e) {
    e.preventDefault();
    const id = $(this).attr("data-id");
    const url = "pages/cart/mais.php";
    $.ajax({
      url: url,
      type: "POST",
      data: { id: id },
      dataType: "JSON",
      success: function (response, textStatus, jqXHR) {
        console.log(response);
        if (response.status === "success") {
          let carrinho = JSON.parse(localStorage.getItem("cartItem")) || []; // recupera os itens do carrinho caso existam
          // atualiza a quantidade do item no carrinho
          for (let i = 0; i < carrinho.length; i++) {
            if (carrinho[i].id === response.cart_items[0].id) {
              carrinho[i].quantidade = response.cart_items[0].quantidade;
            }
          }
          $("#count").attr("value", response.count); // atualiza o contador de itens no carrinho
          $("#total").attr("value", `${response.total} €`); // atualiza o contador de itens no carrinho
          $('.in-cart-content[id="' + id + '"]')
            .find(".quantidade")
            .val(response.cart_items[0].quantidade); // atualiza a quantidade do item no carrinho
          localStorage.setItem("cartItem", JSON.stringify(carrinho)); // guarda os itens do carrinho no local storage
        } else {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`
          );
          $(".result").show();
        }
        setTimeout(function () {
          // esconde o resultado após 2 segundos
          $(".result").hide("");
          if (response.redirect) {
            window.location.href = response.redirect;
          }
        }, 2000);
      },
    });
  });

  $("body").on("click", ".minus", function (e) {
    e.preventDefault();
    const id = $(this).attr("data-id");
    const url = "pages/cart/menos.php";
    $.ajax({
      url: url,
      type: "POST",
      data: { id: id },
      dataType: "JSON",
      success: function (response, textStatus, jqXHR) {
        if (response.status === "success") {
          let carrinho = JSON.parse(localStorage.getItem("cartItem")) || []; // recupera os itens do carrinho caso existam
          // atualiza a quantidade do item no carrinho
          for (let i = 0; i < carrinho.length; i++) {
            if (carrinho[i].id === response.cart_items[0].id) {
              carrinho[i].quantidade = response.cart_items[0].quantidade;
            }
          }
          $("#count").attr("value", response.count); // atualiza o contador de itens no carrinho
          $("#total").attr("value", `${response.total} €`); // atualiza o contador de itens no carrinho
          $('.in-cart-content[id="' + id + '"]')
            .find(".quantidade")
            .val(response.cart_items[0].quantidade); // atualiza a quantidade do item no carrinho
          localStorage.setItem("cartItem", JSON.stringify(carrinho)); // guarda os itens do carrinho no local storage
        } else {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`
          );
          $(".result").show();
        }
        setTimeout(function () {
          // esconde o resultado após 2 segundos
          $(".result").hide("");
          if (response.redirect) {
            window.location.href = response.redirect;
          }
        }, 2000);
      },
    });
  });

  /*=============================================================================================================================================================================*/
  //remover artigos do carrinho

  $("body").on("click", ".remove", function (e) {
    e.preventDefault();
    const id = $(this).attr("data-id");
    const url = "pages/cart/remover.php";
    $.ajax({
      url: url,
      type: "POST",
      data: { id: id },
      dataType: "JSON",
      success: function (response, textStatus, jqXHR) {
        $(".result").text("");
        if (response.status === "success") {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-check-circle"></span>${response.message}</div>`
          );
          $(".result").show();
          $("#count").attr("value", response.count);
          $("#total").attr("value", `${response.total} €`);
          let carrinho = JSON.parse(localStorage.getItem("cartItem")) || []; // recupera os itens do carrinho caso existam
          // verifica se o item existe no carrinho
          if (response.count <= 0) {
            $("label").remove(); // remove o label com o total
            $(".checkout-btn").remove(); // remove o botão de checkout
            // adiciona o label com o total
            $(".in-cart").prepend(
              `<span class="empty">Carrinho vazio</span><label for="total" class="total" >Total: <input name="total" id="total" type="text" value="0.00 €" readonly></label><input class="checkout-btn" type="button" value="Checkout">`
            );
            $(`#${response.produto_id}`).remove(); // remove o item do carrinho
            carrinho = []; // limpa o carrinho
          } else {
            //remover item do localStorage carrinho
            carrinho = carrinho.filter(
              (item) => item.id !== response.produto_id
            );
            $(`#${response.produto_id}`).remove();
          }
          localStorage.setItem("cartItem", JSON.stringify(carrinho)); // guarda os itens do carrinho no local storage
        } else {
          $(".result").append(
            `<div class="${response.status}"><span class="fa fa-times-circle"></span>${response.message}</div>`
          );
          $(".result").show();
        }
        setTimeout(function () {
          $(".result").hide("");
          if (response.redirect) {
            window.location.href = response.redirect;
          }
        }, 2000);
      },
    });
  });
});

/*=============================================================================================================================================================================*/

// carregar itens do carrinho ao abrir a página
$("body").ready(function () {
  let cartItem = "";
  const saveCarrinho = JSON.parse(localStorage.getItem("cartItem")) || []; // recupera os itens do carrinho caso existam
  let total = 0;

  // verifica se existem itens no carrinho e preenche os elementos do carrinho
  if (saveCarrinho.length > 0) {
    $("#count").attr("value", saveCarrinho.length);
    // calcula o total dos itens no carrinho
    for (let i = 0; i < saveCarrinho.length; i++) {
      const item = saveCarrinho[i];
      total += item.quantidade * item.preco;
    }
    $("#total").attr("value", `${total.toFixed(2)} €`); // adiciona o total ao label
    $(".empty").remove(); // remove o label com o total
    //$("lebel").remove(); // remove o botão de checkout
    // preenche os elementos do carrinho com os itens do carrinho
    saveCarrinho.forEach((item) => {
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
      $(".in-cart").prepend(cartItem); // adiciona os itens do carrinho ao topo da lista
    });
  }
  // adiciona o label com o total e o botão de checkout caso existam itens no carrinho
  $(".cart-content").append(
    `<label for="total" class="total" >Total: <input name="total" id="total" type="text" value="${total.toFixed(
      2
    )}€" readonly></label><input class="checkout-btn" data-id="${
      saveCarrinho[0]?.user_id
    }" type="button" value="Checkout">`
  );
});

/*=============================================================================================================================================================================*/

// abrir o carrinho ao clicar no icone do carrinho
$(".cart").on("click", function () {
  if ($(".cart-content").css("display") === "none") {
    $(".cart-content").css("display", "flex");
  } else {
    $(".cart-content").css("display", "none");
  }
});

$("body").on("click", ".checkout-btn", function (e) {
  e.preventDefault();
  const notEmpty = $("#count").attr("value");
  const user_logged = $(this).attr("data-id");
  console.log(user_logged);
  console.log(notEmpty);
  const urlCheckoutInfo = "pages/cart/checkoutinfo.php";
  const urlCheckout = "pages/cart/checkout.php";
  if (notEmpty > 0) {
    if (user_logged !== undefined) {
      console.log("Você precisa estar logado para efetuar o checkout!");
      $("#content").load(urlCheckoutInfo, function (status) {
        if (status === "error") {
          console.log("Error: " + xhr.status + ": " + xhr.statusText);
        } else {
          console.log("Página carregada com sucesso!");
        }
      });
    } else {
      console.log("Você está logado! Efetuando checkout...");
      $("#content").load(urlCheckout, function (status) {
        if (status === "error") {
          console.log("Error: " + xhr.status + ": " + xhr.statusText);
        } else {
          console.log("Página carregada com sucesso!");
        }
      });
    }
  }
});

/*$("body").on("click", ".checkout-btn", function (e) {
  e.preventDefault();
  const user_id = $(this).attr("data-id");
  const url = "pages/cart/checkout.php";

  if (user_id) {
    $("#content").load(url, { user_id: user_id }, function (status) {
      if (status === "error") {
        console.log("Error: " + xhr.status + ": " + xhr.statusText);
      } else {
        $(".cart-content").css("display", "none");
        $(".in-cart").empty(); // limpa o carrinho
        localStorage.removeItem("cartItem"); // remove os itens do carrinho do localStorage
        $("#count").attr("value", 0); // repor contador de itens a zero
        $("#total").attr("value", "0.00 €"); // repor total a zero
        $(".in-cart").prepend(
          `<span class="empty">Carrinho vazio</span><label for="total" class="total" >Total: <input name="total" id="total" type="text" value="0.00 €" readonly></label><input class="checkout-btn" type="button" value="Checkout">`
        );
        $(".result").append(
          `<div class="success"><span class="fa fa-check-circle"></span>Pedido efetuado com sucesso!</div>`
        );
        $(".result").show();
        setTimeout(function () {
          $(".result").hide("");
        }, 2000);
      }
    });
  }
});*/
