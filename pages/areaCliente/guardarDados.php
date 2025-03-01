<?php
    
    include '../../includes/conexao.php';
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    $erros = [];
    $resposta = [];
    $data_atual =intval(date('Y-m-d'));
    $regex_nome = '/[A-Z][a-z]* [A-Z][a-z]*/';
    $regex_morada = '/[A-Za-z0-9., -]+/';
   

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {

            $nome = htmlspecialchars($_POST['nome']);
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $cpassword = htmlentities($_POST['cpassword']);
            $telefone = htmlspecialchars($_POST['telefone']);
            $morada = htmlspecialchars($_POST['morada']);
            $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
            $nif = htmlspecialchars($_POST['nif']);
            $data_n_format = date_create($data_nascimento);
            $data_n_format = intval(date_format($data_n_format, 'Y-m-d'));
            $imagem = $_FILES['imagem'];
            
            

            if(empty($nome) || empty($username) || empty($email) || empty($telefone) || empty($morada) || empty($data_nascimento) || empty($nif)){
                
                $erros['campos_obrigatorios'] = 'Todos os campos são obrigatórios!';
                
            }
            if(!preg_match($regex_nome, $nome)){
                
                $erros['nome'] = 'O nome introduzido não é válido!';
            }
            if(!preg_match('/^[0-9]{9}$/', $nif)){
                
                $erros['nif'] = 'O NIF introduzido não é válido!';
            }
            if(!preg_match($regex_morada, $morada)){
                
                $erros['morada'] = 'A morada introduzida não é válida!';
            }
            if(($data_atual - $data_n_format) < 18){
                
                $erros['idade'] = 'Deve ter pelo menos 18 anos!';
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

               $erros['email'] = 'O email introduzido não é válido!';
            }
            if(strlen($telefone) < 9 || !is_numeric($telefone)){

                $erros['telefone'] = 'O telefone introduzido não é válido!';
            }
            
            
            
            if(count($erros) == 0 && empty($password)){

                $verificar = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $verificar->bindParam(':username', $username, PDO::PARAM_STR);
                $verificar->bindParam(':email', $email, PDO::PARAM_STR);
                $verificar->execute();

                if($verificar->rowCount() > 1){

                    $erros['username_email'] = 'O username ou email já existem!';

                } else {

                    

                    if(isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0){
                        $imagem = $_FILES['imagem'];
                        
                        if($imagem['size'] > 5000000){

                            $erros['size'] = 'A imagem é muito grande!';
                        }

                        $pasta = "../../assets/imagens_prefil/";
                        $nomeImagem = $imagem['name'];
                        $novoNomeImagem = uniqid();
                        $extensao = strtolower(pathinfo($nomeImagem, PATHINFO_EXTENSION));
                        
                        if($extensao!= 'jpg' && $extensao!= 'jpeg' && $extensao!= 'png'){

                            $erros['extencao'] = 'A imagem não é válida! Apenas JPG, JPEG e PNG são permitidos!';
                        }

                        $path = $pasta. $novoNomeImagem. '.'. $extensao;
                        $open_path = './assets/imagens_prefil/'. $novoNomeImagem. '.'. $extensao;
                        $certo = move_uploaded_file($imagem['tmp_name'], $path);
                        
                        if($certo){
                            try {
                                $update = $conn->prepare("UPDATE users SET nome = :nome, username = :username, email = :email, telefone = :telefone, morada = :morada, data_nascimento = :data_nascimento, nif = :nif, nome_imagem = :nome_imagem, imagem_path = :imagem_path WHERE email = :email");
                                $update->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $update->bindParam(':username', $username, PDO::PARAM_STR);
                                $update->bindParam(':email', $email, PDO::PARAM_STR);
                                $update->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $update->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $update->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $update->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $update->bindParam(':nome_imagem', $nomeImagem, PDO::PARAM_STR);
                                $update->bindParam(':imagem_path', $open_path, PDO::PARAM_STR);
                                $update->execute();

                                $resposta['sucesso'] = 'Utilizador atualizado com sucesso!';

                            } catch(PDOException $e) {

                                $erros['imagem'] = 'Erro ao carregar a imagem! Devido: '. strip_tags($e->getMessage());

                            }
                        }
                    }else{

                            try {

                                $update = $conn->prepare("UPDATE users SET nome = :nome, username = :username, email = :email, telefone = :telefone, morada = :morada, data_nascimento = :data_nascimento, nif = :nif WHERE email = :email");
                                $update->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $update->bindParam(':username', $username, PDO::PARAM_STR);
                                $update->bindParam(':email', $email, PDO::PARAM_STR);
                                $update->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $update->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $update->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $update->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $update->execute();

                                $resposta['sucesso'] = 'Utilizador registado com sucesso!';

                            } catch(PDOException $e) {

                                $erros['imagem'] = 'Erro ao carregar a imagem! Devido: '. strip_tags($e->getMessage());
                            }
                    }
                };

            }else if(!empty($password) && count($erros) == 0){

                if(strlen($password) < 8 || strlen($password) > 20){

                    $erros['password'] = 'A password deve ter entre 8 e 20 caracteres!';
                 }
                 if($password !== $cpassword){
     
                     $erros['cpassword'] = 'As passwords não coincidem!';
                 }

                $verificar = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $verificar->bindParam(':username', $username, PDO::PARAM_STR);
                $verificar->bindParam(':email', $email, PDO::PARAM_STR);
                $verificar->execute();

                if($verificar->rowCount() > 1){

                    $erros['username_email'] = 'O username ou email já existem!';

                } else {

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    if(isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0){
                        $imagem = $_FILES['imagem'];
                        
                        if($imagem['size'] > 5000000){

                            $erros['size'] = 'A imagem é muito grande!';
                        }

                        $pasta = "../../assets/imagens_prefil/";
                        $nomeImagem = $imagem['name'];
                        $novoNomeImagem = uniqid();
                        $extensao = strtolower(pathinfo($nomeImagem, PATHINFO_EXTENSION));
                        
                        if($extensao!= 'jpg' && $extensao!= 'jpeg' && $extensao!= 'png'){

                            $erros['extencao'] = 'A imagem não é válida! Apenas JPG, JPEG e PNG são permitidos!';
                        }

                        $path = $pasta. $novoNomeImagem. '.'. $extensao;
                        $open_path = './assets/imagens_prefil/'. $novoNomeImagem. '.'. $extensao;
                        $certo = move_uploaded_file($imagem['tmp_name'], $path);
                        
                        if($certo){
                            try {
                                $update = $conn->prepare("UPDATE users SET nome = :nome, username = :username, email = :email, telefone = :telefone, morada = :morada, data_nascimento = :data_nascimento, nif = :nif, nome_imagem = :nome_imagem, imagem_path = :imagem_path WHERE email = :email");
                                $update->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $update->bindParam(':username', $username, PDO::PARAM_STR);
                                $update->bindParam(':email', $email, PDO::PARAM_STR);
                                $update->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $update->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $update->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $update->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $update->bindParam(':nome_imagem', $nomeImagem, PDO::PARAM_STR);
                                $update->bindParam(':imagem_path', $open_path, PDO::PARAM_STR);
                                $update->execute();

                                $resposta['sucesso'] = 'Utilizador atualizado com sucesso!';

                            } catch(PDOException $e) {

                                $erros['imagem'] = 'Erro ao carregar a imagem! Devido: '. strip_tags($e->getMessage());

                            }
                        }
                    }else{

                            try {

                                $update = $conn->prepare("UPDATE users SET nome = :nome, username = :username, email = :email, telefone = :telefone, morada = :morada, data_nascimento = :data_nascimento, nif = :nif WHERE email = :email");
                                $update->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $update->bindParam(':username', $username, PDO::PARAM_STR);
                                $update->bindParam(':email', $email, PDO::PARAM_STR);
                                $update->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $update->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $update->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $update->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $update->execute();

                                $resposta['sucesso'] = 'Utilizador registado com sucesso!';

                            } catch(PDOException $e) {

                                $erros['imagem'] = 'Erro ao carregar a imagem! Devido: '. strip_tags($e->getMessage());
                            }
                    }
                };
            }

            
           

        } catch(PDOException $e) {

            $erro['conexao'] = 'Erro ao conectar com a base de dados: '. strip_tags($e->getMessage());

        }
        $resposta['erros'] = $erros;
        echo json_encode($resposta);
        
        
        
        
    }

    
?>
