<?php

    include '../../includes/conexao.php';

    $erro = [];
    $resposta = [];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

          
        $nome = htmlspecialchars($_POST['nome']);
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $cpassword = htmlentities($_POST['cpassword']);
        $telefone = htmlspecialchars($_POST['telefone']);
        $morada = htmlspecialchars($_POST['morada']);
        $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
        $nif = htmlspecialchars($_POST['nif']);

        if(empty($nome) || empty($username) || empty($email) || empty($password) || empty($telefone) || empty($morada) || empty($data_nascimento) || empty($nif)){
            $erro['campos'] = 'Por favor preencha todos os campos!';
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro['email'] = 'O endereço de email introduzido não é válido!';
        }
        if(strlen($telefone) < 9 || !is_numeric($telefone)){
            $erro['telefone'] = 'O número de telefone introduzido não é válido!';
        }
        if(strlen($password) < 8 || strlen($password) > 20){
            $erro['password'] = 'A password deve ter entre 8 e 20 caracteres!';
        }
        if($password !== $cpassword){
            $erro['passwords'] = 'As passwords não coincidem!';
        }

        if(isset($_FILES)){
            $imagem = $_FILES['img-registo'];

           
            if(count($erro) == 0){
                 //verificar se o username ou e-mail  já existe
                $query = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $query->bindParam(":username", $username, PDO::PARAM_STR);
                $query->bindParam(":email", $email, PDO::PARAM_STR);
                $query->execute();

                if($query->rowCount() > 0){
                    $erro['existe'] = 'O username ou e-mail introduzido já existe!';
                }else{
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                if($imagem['error']){
                    echo '<script>alert("Erro ao fazer upload da imagem!"); location.href= "index.php";</script>';
                    exit();
                }
                if($imagem['size'] > 2097152){
                    echo '<script>alert("Imagem muito grande! Tamanho máximo permitido: 2MB"); location.href= "index.php";</script>';
                    exit();
                }
                $pasta = "../../assets/imagens_prefil/";
                $nomeImagem = $imagem['name'];
                $novoNomeImagem = uniqid();
                $extensao = strtolower(pathinfo($nomeImagem, PATHINFO_EXTENSION));
    
                if($extensao!= 'jpg' && $extensao!= 'jpeg' && $extensao!= 'png'){
                    echo '<script>alert("Formato de imagem inválido! Apenas são permitidos ficheiros .jpg, .jpeg e .png"); location.href= "index.php";</script>';
                    exit();
                }
    
                $path = $pasta . $novoNomeImagem. '.'. $extensao;
                $certo = move_uploaded_file($imagem['tmp_name'], $path);
    
                if($certo) {
                    try {
                        $query = $conn->prepare("INSERT INTO users (nome, username, email, password, telefone, morada, data_nascimento, nif, nome_imagem, imagem_path) VALUES (:nome, :username, :email, :password, :telefone, :morada, :data_nascimento, :nif, :nome_imagem, :imagem_path)");
                        $query->bindParam(':nome', $nome, PDO::PARAM_STR);
                        $query->bindParam(':username', $username, PDO::PARAM_STR);
                        $query->bindParam(':email', $email, PDO::PARAM_STR);
                        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                        $query->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                        $query->bindParam(':morada', $morada, PDO::PARAM_STR);
                        $query->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                        $query->bindParam(':nif', $nif, PDO::PARAM_STR);
                        $query->bindParam(':nome_imagem', $nomeImagem, PDO::PARAM_STR);
                        $query->bindParam(':imagem_path', $path, PDO::PARAM_STR);
                        $query->execute();
                        
                        $resposta['sucesso'] = 'Registo efetuado com sucesso!';
                    } catch (PDOException $e) {
                        $erro['error'] = strip_tags($e->getMessage());
                        
                    }
                } else {
                    echo '<script>alert("Erro ao fazer upload da imagem!"); location.href= "index.php";</script>';
                    exit();
                }
                }
                

            } else {
                $resposta['erros'] = $erro;
                echo json_encode($resposta);
                exit();
            }

        }else{
            if(count($erro) == 0){
                //verificar se o username ou e-mail  já existe
               $query = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
               $query->bindParam(":username", $username, PDO::PARAM_STR);
               $query->bindParam(":email", $email, PDO::PARAM_STR);
               $query->execute();

               if($query->rowCount() > 0){
                   $erro['existe'] = 'O username ou e-mail introduzido já existe!';
               }else{
                   $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                   $query = $conn->prepare("INSERT INTO users (nome, username, email, password, telefone, morada, data_nascimento, nif) VALUES (:nome, :username, :email, :password, :telefone, :morada, :data_nascimento, :nif)");
                        $query->bindParam(':nome', $nome, PDO::PARAM_STR);
                        $query->bindParam(':username', $username, PDO::PARAM_STR);
                        $query->bindParam(':email', $email, PDO::PARAM_STR);
                        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                        $query->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                        $query->bindParam(':morada', $morada, PDO::PARAM_STR);
                        $query->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                        $query->bindParam(':nif', $nif, PDO::PARAM_STR);
                        $query->execute();
                        
                        $resposta['sucesso'] = 'Registo efetuado com sucesso!';
               }


            

            }else{
            
                $resposta['erros'] = $erro;
                echo json_encode($resposta);
                exit();
            }
        };
        
    }
    $conn = null;