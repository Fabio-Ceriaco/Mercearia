<?php 
    ini_set('default_charset', 'UTF-8');
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
                <input type="hidden" name="id" value="<?=strip_tags($produto['id'])?>">
                <img src="<?=$produto['imagem']?>" alt="apple">
                <strong><?=strip_tags($produto['nome']) ?></strong>
                <span class="quantidade">1 Kg</span>
                <span class="preco"><?= strip_tags(number_format($produto['preco'], 2, ','))?></span>

                <!--cart-btn--->
                <a data-id="<?= strip_tags($produto['id'])?>" class="cart-btn" ><i class="fas fa-shopping-bag"></i>Add To Cart</a>
            </div>
        <?php 
            endforeach;        
        ?>
        <?php 
            endif;
        ?>
        </div>
    </section>
