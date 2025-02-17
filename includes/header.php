<?php
    include 'conexao.php';


    if(!isset($_SESSION)){

        session_start();
        
    } 

    try{
        $carts = $conn->prepare('SELECT produtos.nome As nomeproduto, quantidade, carrinho.preco  FROM carrinho join produtos ON carrinho.produto_id = produtos.id');
        $carts ->execute();
        $count = $carts->rowCount();
    }catch(PDOException $e){
        die('Não foi possível realizar a consulta a base de dados: '. htmlspecialchars($e->getMessage()));
    }

?>




<!DOCTYPE html>
<html lang="pt-pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="site Mercearia">
    <meta name="author" content="Fábio Ceriaco">
    <title>Mercearia</title>
    <script src="https://kit.fontawesome.com/f98569bb37.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./assets/style/style.css">
  </head>
<body>
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
        <div class="cart">
            <a href="#" class="cart"><i class="fa-solid fa-cart-shopping"></i><span><?= $count ?></span></a>
            <div class="cart-content">
                <?php
                $total = 0;
                if($carts->rowCount() == 0):?>
                    <span>Carrinho Vazio</span>
                <?php else:?>
                <?php
                 foreach($carts as $cart):?>
                    <div style="display: flex; flex-direction: row; justify-content: space-between; border-top: 1px solid #000; padding: 5px;">
                        <span> <?=strip_tags($cart['nomeproduto'])?></span>
                        <span> <?=strip_tags($cart['quantidade'])?></span>
                        <span> <?=strip_tags($cart['preco'])?> €</span>
                    </div>
                
                    <?php $total += $cart['preco']?>
                <?php endforeach;?>
                <?php endif;?>
                <span id="total">Total: <?= strip_tags(number_format($total, 2, ',')) ?> €  </span>
                <a href="#" class="">Checkout</a>
            </div>
        </div>

        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
    </nav>
</body>
</html>