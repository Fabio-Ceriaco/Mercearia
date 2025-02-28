<?php
    
    include '../../includes/conexao.php';
    /*ini_set('display_errors', 1);
    error_reporting(E_ALL);*/
    
    $resposta = [];
    $data_atual =intval(date('Y-m-d'));
    
   

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
            
            

            if(empty($nome) || empty($username) || empty($email) || empty($password) || empty($telefone) || empty($morada) || empty($data_nascimento) || empty($nif)){
                $resposta = [
                    'status' => 'error',
                    'mensagem' => 'Por favor preencha todos os campos!'
                ];
                echo json_encode($resposta);
                
            }
            if(($data_atual - $data_n_format) < 18){
                
                $resposta = [
                    'status' => 'error',
                    'mensagem' => 'Deve ter mais de 18 anos!'
                ];
                echo json_encode($resposta);
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

               $resposta = [
                    'status' => 'error',
                    'mensagem' => 'O email introduzido não é válido!'
                ];
                echo json_encode($resposta);
            }
            if(strlen($telefone) < 9 || !is_numeric($telefone)){

                $resposta = [
                    'status' => 'error',
                    'mensagem' => 'O telefone introduzido não é válido!'
                ];
                echo json_encode($resposta);
            }
            if(strlen($password) < 8 || strlen($password) > 20){

                $resposta = [
                    'status' => 'error',
                    'mensagem' => 'A password precisa ter entre 8 e 20 caracteres!'
                ];
                echo json_encode($resposta);
            }
            if($password !== $cpassword){

                $resposta = [
                    'status' => 'error',
                    'mensagem' => 'As passwords não coincidem!'
                ];
                echo json_encode($resposta);
            }

            if($resposta == null){

                $verificar = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $verificar->bindParam(':username', $username, PDO::PARAM_STR);
                $verificar->bindParam(':email', $email, PDO::PARAM_STR);
                $verificar->execute();

                if($verificar->rowCount() > 0){

                    $resposta = [
                        'status' => 'error',
                        'mensagem' => 'Este username ou email já existem!'
                    ];
                    echo json_encode($resposta);

                } else {

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    if(isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0){
                        $imagem = $_FILES['imagem'];
                        
                        if($imagem['size'] > 5000000){

                            $resposta = [
                                'status' => 'error',
                                'mensagem' => 'A imagem não pode ser carregada. Tente de novo!'
                            ];
                            echo json_encode($resposta);
                        }

                        $pasta = "../../assets/imagens_prefil/";
                        $nomeImagem = $imagem['name'];
                        $novoNomeImagem = uniqid();
                        $extensao = strtolower(pathinfo($nomeImagem, PATHINFO_EXTENSION));
                        
                        if($extensao!= 'jpg' && $extensao!= 'jpeg' && $extensao!= 'png'){

                            $resposta = [
                                'status' => 'error',
                                'mensagem' => 'A imagem precisa ser em formato JPG, JPEG ou PNG!'
                            ];
                            echo json_encode($resposta);
                        }

                        $path = $pasta. $novoNomeImagem. '.'. $extensao;
                        $open_path = './assets/imagens_prefil/'. $novoNomeImagem. '.'. $extensao;
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
                                $inserir->bindParam(':imagem_path', $open_path, PDO::PARAM_STR);
                                $inserir->execute();

                                $reposta = [
                                     'status' =>'sucesso',
                                     'mensagem' => 'Utilizador registado com sucesso!'
                                ];
                                echo json_encode($resposta);

                            } catch(PDOException $e) {

                                $resposta = [
                                     'status' => 'error',
                                     'mensagem' => 'Erro ao registar utilizador! Devido: '. strip_tags($e->getMessage())
                                ];
                                echo json_encode($resposta);

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

                                $resposta = [
                                     'status' =>'sucesso',
                                     'mensagem' => 'Utilizador registado com sucesso!'
                                ];
                                echo json_encode($resposta);

                            } catch(PDOException $e) {

                                $resposta = [
                                     'status' => 'error',
                                     'mensagem' => 'Erro ao registar utilizador! Devido: '. strip_tags($e->getMessage())
                                ];
                                echo json_encode($resposta);
                            }
                    }
                };

            }else {

                $resposta = [
                    'status' => 'error',
                   'mensagem' => 'Erro ao validar os dados!'
                ];
                echo json_encode($resposta);
                
            }
            
           

        } catch(PDOException $e) {

            $resposta = [
                'status' => 'error',
                'mensagem' => 'Erro ao conectar ao banco de dados! Devido: '. strip_tags($e->getMessage())
            ];
            echo json_encode($resposta);

        }
        
        
        
        
        
        
    }

    
?>
