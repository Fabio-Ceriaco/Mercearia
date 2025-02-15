<?php 
    include 'includes/conexao.php';

    if(!isset($_SESSION)){
        session_start();
        
      }
    try{
        $query = "SELECT * FROM produtos";
        $stmt = $conn -> prepare($query);
        $stmt -> execute();
        $produtos = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        
    }catch(PDOException $e){
        die('Não foi possível realizar a consulta a base de dados: ' . htmlspecialchars($e->getMessage()));
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
   
  
  </head>
<body>
<section id="produtos-populares">
        <!--heading-->
        <div class="produtos-heading">
            <h3>Produtos</h3>
            
        </div>
      
        <!--produtos-box-container-->
        <div class="produtos-container">
        <?php 
            if($produtos):
        ?>
        <?php 
            foreach($produtos as $produto):
        ?>
            <!--box-->
            <div class="produtos-box">
                <input type="hidden" name="id" value="<?=$produto['id']?>">
                <img src="./assets/img/apple.png" alt="apple">
                <strong><?=$produto['nome'] ?></strong>
                <span class="quantidade">1 Kg</span>
                <span class="preco"><?= $produto['preco']?></span>

                <!--cart-btn--->
                <a href="?adicionar=<?=$produto['id']?>" class="cart-btn" ><i class="fas fa-shopping-bag"></i>Add To Cart</a>
            </div>
        <?php 
            endforeach;        
        ?>
        <?php 
            endif;
        ?>
        </div>
    </section>
</body>
</html>