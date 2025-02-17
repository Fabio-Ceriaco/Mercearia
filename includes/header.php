<?php
    include 'conexao.php';

    if(!isset($_SESSION)){
        session_start();
        
    } 

    try{
        $query = 'SELECT quantidade, carrinho.preco, produtos.nome AS nome_produto, user_id FROM carrinho JOIN produtos ON carrinho.produto_id = produtos.id JOIN users ON carrinho.user_id = users.id';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $count = $stmt->rowCount();
        $carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <a href="populares" class="logo" id="logo">
            <span>M</span>ercearia
        </a>
        <!--navBar-btn-->
        <input type="checkbox" class="navBar-btn" id="navBar-btn">
        <label for="navBar-btn" class="navBar-icon">
            <span class="nav-icon"></span>
        </label>
        <!--Menu de navegação-->
        <ul class="navBar" id="navBar">
            <li><a href="populares" class="active">Home</a></li>
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
                 foreach($carrinho as $c):?>
                    <div style="display: flex; flex-direction: row; justify-content: space-between; border-top: 1px solid #000; padding: 5px;">
                        <span> <?=strip_tags($c['nome_produto'])?></span>
                        <span> <?=strip_tags($c['quantidade'])?></span>
                        <span> <?=strip_tags($c['preco'])?> €</span>
                    </div>
                    <?php $total += $c['preco'] * $c['quantidade']?>
                <?php endforeach;?>
                <span id="total">Total: <?= strip_tags(number_format($total, 2, ',')) ?> €  </span>
            </div>
        </div>

        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
    </nav>
</body>
</html>