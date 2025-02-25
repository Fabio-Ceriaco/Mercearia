<?php
    
    include '../../includes/conexao.php';
    
    $erro = [];
    $resposta = [];

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

            if(count($erro) == 0){

                $verificar = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $verificar->bindParam(':username', $username, PDO::PARAM_STR);
                $verificar->bindParam(':email', $email, PDO::PARAM_STR);
                $verificar->execute();

                if($verificar->rowCount() > 0){

                    $erro['existe'] = "O username ou o endereço de email introduzido já existe!";

                } else {

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){

                        $imagem = $_FILES['img-registo'];

                        if($image['size'] > 5000000){

                            $erro['size'] = 'A imagem excedeu o limite de 5MB!';
                        }

                        $pasta = "../../assets/imagens_prefil/";
                        $nomeImagem = $imagem['name'];
                        $novoNomeImagem = uniqid();
                        $extensao = strtolower(pathinfo($nomeImagem, PATHINFO_EXTENSION));
                        
                        if($extensao!= 'jpg' && $extensao!= 'jpeg' && $extensao!= 'png'){

                            $erro['formato'] = 'Formato de imagem inválido! Apenas são permitidos ficheiros.jpg,.jpeg e.png!';
                        }

                        $path = $pasta. $novoNomeImagem. '.'. $extensao;
                        $certo = move_uploaded_file($imagem['tmp_name'], $path);

                        if($certo){
                            try {
                                $inserir = $conn->prepare("INSERT INTO users (nome, username, email, password, telefone, morada, data_nascimento, nif, nome_imagem, imagem_path) VALUES (:nome, :username, :email, :password, :telefone, :morada, :data_nascimento, :nif, :nome_imagem, :imagem_path)");
                                $inserir->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $inserir->bindParam(':username', $username, PDO::PARAM_STR);
                                $inserir->bindParam(':email', $email, PDO::PARAM_STR);
                                $inserir->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                                $inserir->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $inserir->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $inserir->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $inserir->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $inserir->bindParam(':nome_imagem', $nomeImagem, PDO::PARAM_STR);
                                $inserir->bindParam(':imagem_path', $path, PDO::PARAM_STR);
                                $inserir->execute();

                                $resposta['sucesso'] = 'Utilizador registado com sucesso!';

                            } catch(PDOException $e) {

                                $erro['bd'] = 'Erro ao registar utilizador! Devido :'. strip_tags($e->getMessage());

                            }
                        }
                    }else{

                            try {

                                $inserir = $conn->prepare('INSERT INTO users (nome, username, email, password, telefone, morada, data_nascimento, nif) VALUES (:nome, :username, :email, :password, :telefone, :morada, :data_nascimento, :nif)');
                                $inserir->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $inserir->bindParam(':username', $username, PDO::PARAM_STR);
                                $inserir->bindParam(':email', $email, PDO::PARAM_STR);
                                $inserir->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                                $inserir->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $inserir->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $inserir->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $inserir->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $inserir->execute();

                                $resposta['sucesso'] = 'Utilizador registado com sucesso!';

                            } catch(PDOException $e) {

                                $erro['bd'] = 'Erro ao registar utilizador! Devido :'. strip_tags($e->getMessage());
                            }
                    }
                };

            }else {

                $resposta['erro'] = $erro;
                exit(json_encode($resposta));
                exit();
            }
            
           

        } catch(PDOException $e) {

            $erro['bd'] = 'Erro ao registar utilizador! Devido :'. strip_tags($e->getMessage());

        }
        
        $resposta['erro'] = $erro;
        echo json_encode($resposta); 
        exit();
        
    }

    $conn = null;