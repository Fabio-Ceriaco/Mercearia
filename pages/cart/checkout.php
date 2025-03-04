<?php
    ini_set('display_erros', 1);
    error_reporting(E_ALL);
    include '../../includes/conexao.php';
    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION) && isset($_SESSION['id'])){
        $user_id = $_SESSION['id'];
    }
    var_dump($user_id);

    if($user_id){
        $dadosUser = $conn->prepare('SELECT * FROM users WHERE id = :id');
        $dadosUser -> bindParam(':id', $user_id);
        $dadosUser-> execute();
        $userDados = $dadosUser -> fetch(PDO::FETCH_ASSOC);

        $userCart = $conn->prepare('SELECT * FROM carrinho WHERE user_id = :id');
        $userCart -> bindParam(':id', $user_id);
        $userCart-> execute();
        $userCart = $userCart -> fetchAll(PDO::FETCH_ASSOC);
    }else{
        
        $cart = $conn->prepare('SELECT * FROM carrinho');
        $cart->execute();
        $cart = $cart->fetchAll(PDO::FETCH_ASSOC);
    
    }
    $conn = null;
?>

