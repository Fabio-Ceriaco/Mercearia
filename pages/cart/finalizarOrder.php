<?php 
    
    include '../../includes/conexao.php';
    ini_set('display_erros', 1);
    error_reporting(E_ALL);
    if(!isset($_SESSION)){
        session_start();
    }
    /*if(isset($_SESSION) && isset($_SESSION['user_id'])){
        $user_id = $_SESSION['id'];
    }*/
    $user_id = $_SESSION['user_id'];
    var_dump($user_id);
    // Selecionar pedidos do utilizador para obter o id da ordem
    $orders = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id");
    $orders->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $orders->execute();
    $orders = $orders->fetchAll(PDO::FETCH_ASSOC);
     
    
    // obter os itens da order
    $order_items = $conn->prepare("SELECT * FROM orders_itens JOIN orders ON orders_itens.order_id = orders.id JOIN produtos ON orders_itens.produto_id = produtos.id WHERE orders_itens.order_id = :order_id");
    $order_items->bindParam(":order_id", $orders[0]['id'], PDO::PARAM_INT);
    $order_items->execute();
    $order_items = $order_items->fetchAll(PDO::FETCH_ASSOC);
   
    var_dump($orders);
    $conn = null;
    
    


?>


<html>
    <body>
    <section class="orders">
        <div class="ordersLogo">
            <span>M</span>ercearia
        </div>
        <div class="orderStatus">
            <span>Pedido Nº: <?=$orders[0]['id']?></span>
            <span>Data: <?=substr($orders[0]['criado_em'], 0, 10)?></span>
            <span>Status: <?=$orders[0]['status']?></span>
        </div>
        <div class="orderProdutos">

    
        <h2>Produtos</h2>


        <div class="orderProdutosHeader">
            <p>Produto</p>
            <p>Descrição</p>
            <p>Quantidade</p>
            <p>Preço</p>
        </div>
        <hr style="width: 100%">
        <?php
        
        foreach($order_items as $item):?>

        <div class="orderItem">
        <p><?=$item['nome_produto']?></p>
        <p><?=$item['descricao']?></p>
        <p><?=$item['quantidade']?></p>
        <p><?=$item['preco']?>€</p>
        </div>
        <hr>
        
        
        <?php endforeach;?>
        
        
        <span>Total: <?= $order_items[0]['total']?>€</span>
        <div class="orderOpcoesPagamento">
            <h2>Opções de Pagamento</h2>
            <div class="opcoes">
            <a href="./pages/formasPagamento/paypal"><img src="./assets/img/paypal.png" alt="paypal"></a>
            <a href="./pages/formasPagamento/cartaoCredito"><img src="./assets/img/cartao_credito.png" alt="cartão de crédito"></a>
            <a href="#"><img src="./assets/img/mbway.png" alt="mbway"></a>
            <a href="#"><img src="./assets/img/transferencia_bancaria.webp" alt="transferencia"></a>
            </div>
        </div>
        </div>
        
    </section>
    </body>
</html>


