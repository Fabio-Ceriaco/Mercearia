<?php
    include '../../includes/conexao.php';
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    

    $cart_checkout = $conn->prepare("SELECT * FROM carrinho JOIN produtos ON carrinho.produto_id = produtos.id JOIN users ON carrinho.user_id = users.id");
    $cart_checkout->execute();
    $cart_checkout = $cart_checkout->fetchAll(PDO::FETCH_ASSOC);
    
    $conn = null;
?>
<html>
   

<body>
<section class="orders">
    <div class="ordersUserData">
    <h2>Dados do Utilizador</h2>

    <p>Nome: <?=$cart_checkout[0]['nome']?></p>
    <p>Morada: <?=$cart_checkout[0]['morada']?></p>
    <p>Localidade: <?=$cart_checkout[0]['localidade']?></p>
    <p>Código Postal: <?=$cart_checkout[0]['cod_postal']?></p>
    <p>Email: <?=$cart_checkout[0]['email']?></p>
    <p>Telefone: <?=$cart_checkout[0]['telefone']?></p>
    <p>Nif: <?=$cart_checkout[0]['nif']?></p>
    </div>
    <hr>
    <div class="orderProducts">

    
    <h2>Produtos</h2>


    <div class="orderProdutosHeader">
        <p>Imagem</p>
        <p>Descrição</p>
        <p>Quantidade</p>
        <p>Preço</p>
    </div>
    <?php
     $total = 0;
     foreach($cart_checkout as $carrinho):?>

    <div class="orderItem">
    <img  src="<?=$carrinho['imagem']?>" alt="<?=$carrinho['descricao']?>">
     <p><?=$carrinho['descricao']?></p>
     <p><?=$carrinho['quantidade']?></p>
     <p><?=$carrinho['preco']?>€</p>
    </div>
     
    <?php $total += $carrinho['quantidade'] * $carrinho['preco'];?>
     <?php endforeach;?>
     <p>Total: <?=$total?>€</p>
     </div>

</section>
</body>
</html>