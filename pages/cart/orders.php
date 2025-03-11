<?php
    include '../../includes/conexao.php';
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    session_start();

    $cart_checkout = $conn->prepare("SELECT * FROM carrinho JOIN produtos ON carrinho.produto_id = produtos.id JOIN users ON carrinho.user_id = users.id");
    $cart_checkout->execute();
    $cart_checkout = $cart_checkout->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach($cart_checkout as $item){
        $total += $item['preco'] * $item['quantidade'];

    }
    $checkOrders = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id");
    $checkOrders->bindParam(":user_id", $cart_checkout[0]['user_id'], PDO::PARAM_INT);
    $checkOrders->execute();

    if($checkOrders->rowCount() > 0){
        $clearOrderUser = $conn->prepare("DELETE FROM orders WHERE user_id = :user_id");
        $clearOrderUser->bindParam(":user_id", $cart_checkout[0]['user_id'], PDO::PARAM_INT);
        $clearOrderUser->execute();
    }else{
        // inserir dados na tabela orders
        $order_items = $conn -> prepare ("INSERT INTO orders (user_id, total) VALUES (:user_id, :total)");
        $order_items->bindParam(":user_id", $cart_checkout[0]['user_id'], PDO::PARAM_INT);
        $order_items->bindParam(":total", $total, PDO::PARAM_STR);
        $order_items->execute();
        
    }
    

    $order_items_id = $conn->lastInsertId();
    
    

    //inserir dados na tabela order_items
    foreach($cart_checkout as $items){
        $order_items = $conn -> prepare ("INSERT INTO orders_itens (order_id, produto_id, quantidade, preco) VALUES (:order_id, :produto_id, :quantidade, :preco)");
        $order_items->bindParam(":order_id", $order_items_id, PDO::PARAM_INT);
        $order_items->bindParam(":produto_id", $items['produto_id'], PDO::PARAM_INT);
        $order_items->bindParam(":quantidade", $items['quantidade'], PDO::PARAM_INT);
        $order_items->bindParam(":preco", $items['preco'], PDO::PARAM_STR);
        $order_items->execute();
    }
    
    $_SESSION['user_id'] = $cart_checkout[0]['user_id'];

    $conn = null;
?>
<html>
 
<body>
<section class="orders">
    
    <div class="ordersLogo">
        <span>M</span>ercearia
    </div>
    
    <div class="ordersUserData">
    <h2>Dados do Utilizador</h2>

    <p>Nome: <?=$cart_checkout[0]['nome']?></p>
    <p>Morada: <?=$cart_checkout[0]['morada']?></p>
    <p>Localidade: <?=$cart_checkout[0]['localidade']?></p>
    <p>Código Postal: <?=$cart_checkout[0]['cod_postal']?></p>
    <p>Email: <?=$cart_checkout[0]['email']?></p>
    <p>Telefone: <?=$cart_checkout[0]['telefone']?></p>
    <p>Nif: <?=$cart_checkout[0]['nif']?></p>
    <hr>
    </div>
    
    <div class="orderProdutos">

    
    <h2>Produtos</h2>


    <div class="orderProdutosHeader">
        <p>Imagem</p>
        <p>Produto</p>
        <p>Quantidade</p>
        <p>Preço</p>
    </div>
    <hr>
    <?php
     
     foreach($cart_checkout as $carrinho):?>

    <div class="orderItem">
    <img  src="<?=$carrinho['imagem']?>" alt="<?=$carrinho['nome_produto']?>">
     <p><?=$carrinho['nome_produto']?></p>
     <p><?=$carrinho['quantidade']?></p>
     <p><?=$carrinho['preco']?>€</p>
    </div>
     
    
     <?php endforeach;?>
     <hr>
     <span>Total: <?=number_format($total, 2, '.', ',') ?>€</span>
     </div>
     <input type="submit" class="order-btn" value="Confirmar Pedido">

</section>
</body>
</html>