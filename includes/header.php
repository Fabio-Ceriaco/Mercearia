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
    <nav >
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
            <a href="#" class="cart"><i class="fa-solid fa-cart-shopping"></i><span>0</span></a>
            <div class="cart-content">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quam hic iure earum atque vero odit iste dolore tenetur, magnam excepturi unde, rem aperiam aut at perferendis. Incidunt minus dolorum hic?
                <span>Total: </span>
            </div>
        </div>

        <div class="signin" id="signin">
            <input type="submit" value="Log/Sign in" class="log-btn" >
        </div>
    </nav>
</body>
</html>