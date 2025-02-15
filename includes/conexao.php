<?php 

    
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=localhost;dbname=mercearia";

    try{
        $conn = new PDO($dsn, $user, $pass);
        $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //ativar exceções em erros

        $conn -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        //configuração padrão de fetch
    } catch(PDOException $e){
        die('Erro de conexão a Base de Dados : ' . $e -> getMessage());
    }