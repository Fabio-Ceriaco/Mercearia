<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    include '../../includes/conexao.php';
    if(!isset($_SESSION)){
        session_start();
    }
    $resposta = [];
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $postFilters = array_map('strip_tags', $post);

    var_dump($postFilters['user_id']);
    

    $user_id = $postFilters['user_id'];
    
    try{

    $temp_usre = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $temp_usre->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $temp_usre->execute();
    $temp_user = $temp_usre->fetch(PDO::FETCH_ASSOC);

    

    $inserUser_id = $conn->prepare("UPDATE carrinho SET user_id = :user_id");
    $inserUser_id->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $inserUser_id->execute();


    $resposta['status'] ='success';
    } catch(PDOException $e){
        $resposta['status'] ='error';
        die('Não foi possível realizar a consulta a base de dados: '. htmlspecialchars($e->getMessage()));
    };

    echo json_encode($resposta);
    $conn = null;

?>
