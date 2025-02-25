<?php
    include 'conexao.php';
    
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
            <li><a href="../pages/registos/registosForm.php">Folhetos</a></li>
            <li><a href="#">Area Cliente</a></li>
            <li><a href="#">Administração</a></li>
        </ul>
        
        <!--cart-->
        <div class="cart" >
            <a href="#" class="cart" id="cart"><i class="fa-solid fa-cart-shopping"></i><input id="count" type="text" value="0" readonly></a>
        </div>
        <div class="cart-content" id="cart-content">
        <div class="in-cart">
            <span class='empty'>Carrinho Vazio</span>
        </div>
        </div>
        

        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
    </nav>
    <section class="login-section">
        <div class="login" id="login">
        <i class="fas fa-times" id="close"></i>
        <br>
        <h2>Login</h2>
        <br>
        <form method="post" action="login.php">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Entrar">
        </form>
        <p>Ainda não possui uma conta? <span class="registar" id="registar"><a href="registosForm" >Crie uma agora</a></span></p>
        </div>
    </section>
    
</body>
</html>