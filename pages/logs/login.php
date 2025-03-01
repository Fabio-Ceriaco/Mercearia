<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    if(!isset($_SESSION)){
        session_start();
    }
    include '../../includes/conexao.php'; //ligar ao banco de dados
    $mensagem = null; //mensagem de retorno
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT); //filtrar inputs para evitar ataques de SQL Injection
    $postFilters = array_map('strip_tags', $post); //remover tags HTML do input
 
    if($_SERVER['REQUEST_METHOD'] == 'POST') { //verificar se o método da requisição é POST
       
        $max_time = 60 * 60 * 24; 
        if(!hash_equals($_POST['token'], $postFilters['token'])) { //verificar se o token de segurança é válido
            $mensagem = [
                'message' => 'Token inválido!',
                'status' => 'error',
                'redirect' => ''
            ];
            echo json_encode($mensagem);
            exit();
        }elseif(time() - $_SESSION['csrf_token_time'] > $max_time) { //verificar se o token de segurança expirou
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
            
            $mensagem = [
                'message' => 'Token expirado!',
                'status' => 'error',
                'redirect' => ''
            ];
            echo json_encode($mensagem);
            
        }else{
               //sanitizar os dados do username, email e password
        $email = htmlspecialchars($_POST['email']); 
        $password = htmlspecialchars($_POST['password']); 
        
        try {   

        //query para verificar se o email existe na base de dados e se a password é válida

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        //se o email existir, verificar se a password é válida
        //se a password for válida, iniciar sessão com os dados do utilizador e redirecionar para a página inicial do site
        //se a password for inválida, mostrar uma mensagem de erro e não iniciar sessão
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['tipo'] = $user['tipo'];
                $_SESSION['imagem'] = $user['imagem_path'];
                $mensagem = [
                    'message' => 'Login efectuado com sucesso!',
                    'status' => 'success',
                    'redirect' => ''
                ];
                echo json_encode($mensagem);
                exit();
            } else {
                $mensagem = [
                    'message' => 'A password introduzida é inválida!',
                    'status' => 'error',
                    'redirect' => ''
                ];
                echo json_encode($mensagem);
                exit();
            }
        } else {
            $mensagem = [
                'message' => 'O email introduzido não existe!',
                'status' => 'error',
                'redirect' => ''
            ];
            echo json_encode($mensagem);
            exit();
        }
    } catch(PDOException $e) {
        die('Não foi possível realizar a consulta a base de dados: ' . htmlspecialchars($e->getMessage()));
    }
        }
        
        

     
    $conn = null;
    }