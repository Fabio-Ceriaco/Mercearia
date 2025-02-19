<?php

include '../../includes/conexao.php';
    

if(!isset($_SESSION)){

    session_start();
    
} 

try{
    $carts = $conn->prepare('SELECT carrinho.id, produtos.nome As nomeproduto, quantidade, carrinho.preco, produtos.imagem As imagemproduto  FROM carrinho join produtos ON carrinho.produto_id = produtos.id');
    $carts ->execute();
    $count = $carts->rowCount();
}catch(PDOException $e){
    die('Não foi possível realizar a consulta a base de dados: '. htmlspecialchars($e->getMessage()));
}

?>

        <div class="in-cart">
                <?php
                $tota = 0;
                if($carts->rowCount() == 0):?>
                    <span>Carrinho Vazio</span>
                <?php else:?>
                <?php
                 foreach($carts as $cart):?>
                    <div class="in-cart-content" id="<?=$cart['id']?>">
                        <img src="" alt="" class="prod-img">
                        <input class="prod-nome" type="text" value="<?=$cart['nomeproduto']?>" readonly></input>
                        <div class="cart-quantity">
                            <input type="button" value="-" class="minus">
                            <input class="quantidade" type="text" value="" readonly><?=$cart['quantidade']?></input>
                            <input type="button" value="+" class="plus">
                        </div>
                        <input class="prod-preco" type="text" value="<?=$cart['preco']?>" readonly></input>
                        <input type="button" data-id="<?=$cart['id']?>" value="X" class="remove">
                    </div>
                
                    
                <?php endforeach;?>
                <?php endif;?>
            
                <span>Total: <input id="total" type="text" value="0.00 €" readonly></input></span>
                <input class="checkout-btn" type="button" value="Checkout">
        </div>