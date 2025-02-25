<?php 
    ini_set('default_charset', 'UTF-8');
    include 'includes/conexao.php';
    if(!isset($_SESSION)){
        session_start();
    }

    try{
        $query = "SELECT * FROM user_comments JOIN users ON user_comments.user_id = users.id";
        $stmt = $conn -> prepare($query);
        $stmt -> execute();
        $comments = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        die('Não foi possível realizar a consulta a base de dados: ' . htmlspecialchars($e->getMessage()));
    }
   
?>
<!doctype html>
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
        <?php 
            include 'includes/header.php';
        ?>
    
    <div class="result"></div>
   
    <section id="content"></section>

        <!-- Clients-->

    <section id="clients">

        <!--heading-->
        <div class="clients-heading">
            <h3>What Our Client's Say</h3>
        </div>

        <!--box-container-->

        <div class="client-box-container">
            <?php 
                if($comments > 0 ):
                    foreach($comments as $comment):
            ?>
            <!--box-->
            <div class="client-box">
                <!--profile-->
                <div class="client-profile">
                    <!--Img-->
                    <img src="./assets/img/client-1.jpg" alt="client">
                    <!--text-->
                    <div class="profile-text">
                        <strong><?=$comment['nome']?></strong>
                        <span><?= $comment['tipo']?></span>
                    </div>
                </div>

                <!--Rating-->
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>

                <!--comments-->
                <p><?=$comment['comment_id']?></p>
            </div>
            <?php 
                endforeach; 
                endif;
                ?>
            
        </div>
    </section>

    <!--Partnre-logo-->
    <section id="partner">

        <!--heading-->
        <div class="partner-heading">
            <h3>Our Trusted Partner</h3>
        </div>

        <!--logo-container-->
        <div class="logo-container">
            <img src="./assets/img/logo-1.png" alt="logo">
            <img src="./assets/img/logo-2.png" alt="logo">
            <img src="./assets/img/logo-3.png" alt="logo">
            <img src="./assets/img/logo-4.png" alt="logo">
        </div>

    </section>
    <!--footer-->

    <?php 
    include 'includes/footer.php'
     ?>
    
    <!--script-->
    <script type="text/javascript" src="./assets/js/script.js"></script>
    <script type="text/javascript" src="./pages/cart/cart.js"></script>
    <script type="text/javascript" src="./pages/registos/userRegistos.js"></script>
  </body>
</html>