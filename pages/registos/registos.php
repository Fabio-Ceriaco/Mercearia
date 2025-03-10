<?php
    
    include '../../includes/conexao.php';
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    $erros = [];
    $resposta = [];
    $data_atual =intval(date('Y-m-d'));
    $regex_nome = '/[A-Z][a-z]* [A-Z][a-z]*/';
    $regex_morada = '/[A-Za-z0-9., -]+/';
    $regex_localidade = '/[\w\W]/';
    $regex_postal_cod = '/[0-9]{4}-[0-9]{3}/';
    $regex_telefone_nif = '/^[0-9]{9}$/';
    $tipo = 'cliente';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {

            $nome = htmlspecialchars($_POST['nome']);
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $cpassword = htmlentities($_POST['cpassword']);
            $telefone = htmlspecialchars($_POST['telefone']);
            $morada = htmlspecialchars($_POST['morada']);
            $localidade = htmlspecialchars($_POST['localidade']);
            $codPostal = htmlspecialchars($_POST['cod-postal']);
            $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
            $nif = htmlspecialchars($_POST['nif']);
            $data_n_format = date_create($data_nascimento);
            $data_n_format = intval(date_format($data_n_format, 'Y-m-d'));
            $imagem = $_FILES['imagem'];
            
            

            if(empty($nome) || empty($username) || empty($email) || empty($password) || empty($telefone) || empty($morada) || empty($data_nascimento) || empty($nif) || empty($localidade) || empty($codPostal)){
                
                $erros['campos_obrigatorios'] = 'Todos os campos são obrigatórios!';
                
            }
            if(!preg_match($regex_nome, $nome)){
                
                $erros['nome'] = 'O nome introduzido não é válido!';
            }
            if(!preg_match($regex_telefone_nif, $nif)){
                
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
            if(!preg_match($regex_telefone_nif, $telefone)){
                $erros['telefone'] = 'Telefone inválido!';
            }
            
            if(strlen($password) < 8 || strlen($password) > 20){

               $erros['password'] = 'A password deve ter entre 8 e 20 caracteres!';
            }
            if($password !== $cpassword){

                $erros['cpassword'] = 'As passwords não coincidem!';
            }
            
            if(count($erros) == 0){

                $verificar = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                $verificar->bindParam(':username', $username, PDO::PARAM_STR);
                $verificar->bindParam(':email', $email, PDO::PARAM_STR);
                $verificar->execute();

                if($verificar->rowCount() > 0){

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
                                $inserir = $conn->prepare("INSERT INTO users (nome, username, email, password, telefone, morada, localidade, cod_postal,  data_nascimento, nif, nome_imagem, imagem_path, tipo) VALUES (:nome, :username, :email, :password, :telefone, :morada, :localidade, :cod_postal :data_nascimento, :nif, :nome_imagem, :imagem_path, :tipo)");
                                $inserir->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $inserir->bindParam(':username', $username, PDO::PARAM_STR);
                                $inserir->bindParam(':email', $email, PDO::PARAM_STR);
                                $inserir->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                                $inserir->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $inserir->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $inserir->bindParam(':localidade', $localidade, PDO::PARAM_STR);
                                $inserir->bindParam(':cod_postal', $cod_postal, PDO::PARAM_STR);
                                $inserir->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $inserir->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $inserir->bindParam(':nome_imagem', $nomeImagem, PDO::PARAM_STR);
                                $inserir->bindParam(':imagem_path', $open_path, PDO::PARAM_STR);
                                $inserir->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                                $inserir->execute();

                                $resposta['sucesso'] = 'Utilizador registado com sucesso!';

                            } catch(PDOException $e) {

                                $erros['imagem'] = 'Erro ao carregar a imagem! Devido: '. strip_tags($e->getMessage());

                            }
                        }
                    }else{

                            try {

                                $inserir = $conn->prepare('INSERT INTO users (nome, username, email, password, telefone, morada, localidade, cod_postal data_nascimento, nif, tipo) VALUES (:nome, :username, :email, :password, :telefone, :morada, :localidade, :cod_postal :data_nascimento, :nif, :tipo)');
                                $inserir->bindParam(':nome', $nome, PDO::PARAM_STR);
                                $inserir->bindParam(':username', $username, PDO::PARAM_STR);
                                $inserir->bindParam(':email', $email, PDO::PARAM_STR);
                                $inserir->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                                $inserir->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                                $inserir->bindParam(':morada', $morada, PDO::PARAM_STR);
                                $insert->bindParam(':localidade', $localidade, PDO::PARAM_STR);
                                $inserir->bindParam(':cod_postal', $cod_postal, PDO::PARAM_STR);
                                $inserir->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
                                $inserir->bindParam(':nif', $nif, PDO::PARAM_STR);
                                $inserir->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                                $inserir->execute();

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
