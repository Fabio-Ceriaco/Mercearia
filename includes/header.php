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
            
        
        <?php if($is_logged && $tipo == 'cliente') :?>
            <div class="user-info" id="user-info">
                <a href="#" class="user-info-btn" id="user-info-btn">
                <span class="username" id="username"><?=strtoupper($username)?></span>
                <img src="<?=$imagem?>" alt="user" id="user">
                </a>
                <!--<a href="./pages/logs/logout.php"></a>-->
            </div>
            <div class="area-cliente">
                <h2>Area Cliente</h2>
                <a href="dadosCliente" class="cliente-info">Dados Pessoais</a>
                <a href="encomendas" class="cliente-info">Encomendas</a>
                <a href="favoritos" class="cliente-info">Favoritos</a>
                <a href="./pages/logs/logout.php" class="cliente-info"  id="logoutBtn">Logout</a>
            </div>
        <?php elseif($is_logged && $tipo == 'admin'):?>
            <div class="user-info" id="user-info">
                <a href="#" class="user-info-btn" id="user-info-btn">
                <span class="username" id="username"><?=strtoupper($username)?></span>
                <img src="<?=$imagem?>" alt="user" id="user">
                </a>
                <!--<a href="./pages/logs/logout.php"></a>-->
            </div>
            <div class="area-cliente">
                <h2>Area Administrativa</h2>
                <a href="dadosCliente" class="cliente-info">Dados Administrador</a>
                <a href="produtos" class="cliente-info">Produtos</a>
                <a href="categorias" class="cliente-info">Categorias</a>
                <a href="clientes" class="cliente-info">Clientes</a>
                <a href="encomendas" class="cliente-info">Empresas fornecedoras</a>
                <a href="encomendas" class="cliente-info">Encomendas de Produtos</a>
                <a href="logs" class="cliente-info">Logs</a>
                <a href="./pages/logs/logout.php"  class="cliente-info">Logout</a>
            </div>
        <?php else:?>
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