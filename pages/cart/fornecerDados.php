<?php
    include '../../includes/conexao.php';
    
    ini_set('display_erros', 1);
    error_reporting(E_ALL);

    $erros = [];
    $resposta = [];
    $data_atual =intval(date('Y-m-d'));
    $regex_nome = '/[A-Z][a-z]* [A-Z][a-z]*/';
    $regex_localidade = '/[\w\W]/';
    $regex_morada = '/[A-Za-z0-9., -]+/';
    $regex_postal_cod = '/[0-9]{4}-[0-9]{3}/';
    $regex_telefone_nif = '/^[0-9]{9}$/';
    

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $nome = htmlspecialchars($_POST['nome']);
        $morada = htmlspecialchars($_POST['morada']);
        $localidade = htmlspecialchars($_POST['localidade']);
        $codPostal = htmlspecialchars($_POST['cod-postal']);
        $email = htmlspecialchars($_POST['email']);
        $telefone = htmlspecialchars($_POST['telefone']);
        $nif = htmlspecialchars($_POST['nif']);
        $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
        $data_n_format = date_create($data_nascimento);
        $data_n_format = intval(date_format($data_n_format, 'Y-m-d'));

        if(empty($nome) || empty($morada) || empty($localidade) || empty($codPostal) || empty($email) || empty($telefone) || empty($nif) || empty($data_nascimento)){
            $erros['campos_obrigatorios'] = 'Todos os campos são obrigatórios!';
        }
        if(!preg_match($regex_nome, $nome)){
            $erros['nome'] = 'Nome inválido!';
        }
        if(!preg_match($regex_localidade, $localidade)){
            $erros['localidade'] = 'Localidade inválida!';
        }
        if(!preg_match($regex_morada, $morada)){
            $erros['morada'] = 'Morada inválida!';
        }
        if(!preg_match($regex_postal_cod, $codPostal)){
            $erros['codpostal'] = 'Código postal inválido!';
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erros['email'] = 'Email inválido!';
        }
        if(!preg_match($regex_telefone_nif, $telefone)){
            $erros['telefone'] = 'Telefone inválido!';
        }
        if(!preg_match($regex_telefone_nif, $nif)){
            $erros['nif'] = 'NIF inválido!';
        }
        if(($data_atual - $data_n_format) < 18){
                
            $erros['idade'] = 'Deve ter pelo menos 18 anos!';
        }

        if(count($erros) === 0){
            try{

                $insert = $conn ->prepare("INSERT INTO users (nome,  email, telefone, morada, data_nascimento, nif, localidade, cod_postal ) VALUES (:nome, :email, :telefone, :morada, :data_nascimento, :nif, :localidade, :cod_postal)");
            $insert -> bindParam(':nome', $nome, PDO::PARAM_STR);
            $insert -> bindParam(':morada', $morada, PDO::PARAM_STR);
            $insert -> bindParam(':localidade', $localidade, PDO::PARAM_STR);
            $insert -> bindParam(':cod_postal', $codPostal, PDO::PARAM_STR);
            $insert -> bindParam(':email', $email, PDO::PARAM_STR);
            $insert -> bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $insert -> bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
            $insert ->bindParam(':nif', $nif, PDO::PARAM_STR);
            $insert -> execute();

            $resposta['sucesso'] = 'Utilizador registado com sucesso!';


            }catch(PDOException $e){
                $erros['bd'] = 'Erro ao registar utilizador! Devido: '. strip_tags($e->getMessage());
            }
            
        }
        $resposta['erros'] = $erros;
        echo json_encode($resposta);


            
        
    }
    $conn = null;