<?php
    include 'conexao.php';
    
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
            <a href="#" class="cart" id="cart"><i class="fa-solid fa-cart-shopping"></i><input id="count" type="text" value="0" readonly></a>
        </div>
        <div class="cart-content" id="cart-content">
            <?php include './assets/js/cartContent.php'?>
        </div>
        

        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
    </nav>
