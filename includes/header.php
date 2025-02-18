<?php
    include 'conexao.php';


    if(!isset($_SESSION)){

        session_start();
        
    } 

    try{
        $carts = $conn->prepare('SELECT carrinho.id, produtos.nome As nomeproduto, quantidade, carrinho.preco  FROM carrinho join produtos ON carrinho.produto_id = produtos.id');
        $carts ->execute();
        $count = $carts->rowCount();
    }catch(PDOException $e){
        die('Não foi possível realizar a consulta a base de dados: '. htmlspecialchars($e->getMessage()));
    }

?>





    <!--Barra de navegação-->
    <nav  >
        <!--Logo-->
        <a href="home" class="logo" id="logo">
            <span>M</span>ercearia
        </a>
        <!--navBar-btn-->
        <input type="checkbox" class="navBar-btn" id="navBar-btn">
        <label for="navBar-btn" class="navBar-icon">
            <span class="nav-icon"></span>
        </label>
        <!--Menu de navegação-->
        <ul class="navBar" id="navBar">
            <li><a href="home" class="active">Home</a></li>
            <li><a href="produtos">Produtos</a></li>
            <li><a href="#">Folhetos</a></li>
            <li><a href="#">Area Cliente</a></li>
            <li><a href="#">Administração</a></li>
        </ul>

        <!--cart-->
        <div class="cart" >
            <a href="#" class="cart" id="cart"><i class="fa-solid fa-cart-shopping"></i><span><?= $count ?></span></a>
        </div>
        <div class="cart-content" id="cart-content">
                <?php
                $total = 0;
                if($carts->rowCount() == 0):?>
                    <span>Carrinho Vazio</span>
                <?php else:?>
                <?php
                 foreach($carts as $cart):?>
                    <div class="in-cart-content">
                        <span> <?=strip_tags($cart['nomeproduto'])?></span>
                        <div class="cart-quantity">
                            <input type="button" value="-" class="minus">
                            <span> <?=strip_tags($cart['quantidade'])?></span>
                            <input type="button" value="+" class="plus">
                        </div>
                        <span> <?=strip_tags($cart['preco'])?> €</span>
                        <input type="button" data-id="<?=$cart['id']?>" value="X" class="remove">
                    </div>
                
                    <?php $total += $cart['preco']?>
                <?php endforeach;?>
                <?php endif;?>
                <span id="total">Total: <?= strip_tags(number_format($total, 2, ',')) ?> €  </span>
                <input class="checkout-btn" type="button" value="Checkout">
            </div>

        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
    </nav>
