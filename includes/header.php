<?php
    include 'conexao.php';
    if(!isset($_SESSION)){
        session_start();
    }

    if(isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['email']) && isset($_SESSION['tipo'])){
        $is_logged = $_SESSION['id'];
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $tipo = $_SESSION['tipo'];
        $imagem = $_SESSION['imagem'];
    }
    

?>




<html>
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
            <?php if($tipo == 'cliente') :?>
            <li><a href="#">Area Cliente</a></li>
            <?php elseif($tipo == 'admin') :?>
            <li><a href="#">Administração</a></li>
            <?php endif;?>
        </ul>

         <!--cart-->
        <div class="cart" >
            <a href="#" class="cart" id="cart"><i class="fa-solid fa-cart-shopping"></i><input id="count" type="text" value="0" readonly></a>
            <div class="cart-content" id="cart-content">
                <div class="in-cart">
                    <span class='empty'>Carrinho Vazio</span>
                </div>
            </div>
        </div>
            
        
        <?php if($is_logged) :?>
            <div class="user-info" id="user-info">
                <span class="username" id="username"><?=strtoupper($username)?></span>
                <img src="<?=$imagem?>" alt="user" id="user">
            </div>
            <div class="logout" id="logout">
                <a href="./pages/logs/logout.php">Logout</a>
            </div>
        <?php else :?>
        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
        <?php endif;?>
      
    </nav>
    
    <section class="login-section">
        <div class="login" id="login">
        <i class="fas fa-times" id="close"></i>
        <br>
        <h2>Login</h2>
        <br>
        <form method="post" action="">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" id="submitlogin" value="Entrar">
        </form>
        <p>Ainda não possui uma conta? <span class="registar" id="registar"><a href="registosForm" >Crie uma agora</a></span></p>
        </div>
    </section>
    
</body>
</html>