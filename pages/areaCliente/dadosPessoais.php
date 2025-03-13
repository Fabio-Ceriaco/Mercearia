<?php 
    include '../../includes/conexao.php';
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION) || empty($_SESSION['user_id'])){

        header('Location:../../home.php');
        exit();
    }
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn -> prepare($query);
        $stmt -> bindParam(':id', $user_id);
        $stmt -> execute();
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);
        
    
?>

<section class="dados-section">
    <h2>Os meus dados</h2>

    <img src="<?=$user['imagem_path']?>" alt="Imagem de Perfil" class="imagem-perfil">
    
    <form method="post" enctype="multipart/form-data" id="dadosForm" class="dados-form">
        <div class="grupo-dados">
        <input type="text" id="nome" name="nome" value="<?=$user['nome']?>"readonly>
        <label for="nome" >Nome:</label><br>
        <span class="errorDados" id="nomeError"></span>
        </div>
        
        
        <div class="grupo-dados">
        <input type="text" id="username" name="username" value="<?=$user['username']?>" readonly>
        <label for="username" >Username:</label>
        </div>

        <div class="grupo-dados">
        <input type="email" id="email" name="email" value="<?=$user['email']?>" readonly>
        <label for="email" >Email:</label><br>
        <span class="errorDados" id="emailError"></span>
        </div>

        <div class="grupo-dados">
            <input type="password" id="password" name="password"  readonly>
            <label for="password" >Password:</label><br>
            <span class="errorDados" id="passwordError"></span>
        </div>

        <div class="grupo-dados">
            <input type="password" id="cpassword" name="cpassword"  readonly>
            <label for="cpassword" >Confirmar Password:</label><br>
            <span class="errorDados" id="cpasswordError"></span>
        </div>

       
        
        <div class="grupo-dados">
        <input type="tel" id="telefone" name="telefone" value="<?=$user['telefone']?>" readonly>
        <label for="telefone" >Telefone:</label><br>
        <span class="errorDados" id="telefoneError"></span>
        </div>
        

        <div class="grupo-dados">
        <input type="text" id="morada" name="morada" value="<?=$user['morada']?>"readonly>
        <label for="morada" >Morada:</label>
        <span class="errorDados" id="moradaError"></span>
        </div>

        <div class="grupo-dados">
        <input type="text" id="data_nascimento" name="data_nascimento" value="<?=$user['data_nascimento']?>" readonly>
        <label for="data_nascimento" >Data de Nascimento:</label>
        <span class="errorDados" id="data_nascimentoError"></span>
        </div>
        

        <div class="grupo-dados">
        <input type="text" id="nif" name="nif" value="<?=$user['nif']?>" readonly>
        <label for="nif" >NIF:</label>
        <span class="errorDados" id="nifError"></span>
        </div>

        <div class="grupo-dados" id="grupopass">
            <input type="file" id="upload-img" name="imagem" value="<?=$user['nome_imagem']?>" disabled>
            <label for="imagem" >Imagem do Perfil:</label>
            <span class="errorDados" id="imagemError"></span>
        </div>
        

        <input type="submit" value="Editar Dados" class="editarDadosBtn" id="editar">
        <input type="submit" value="Guardar" class="guardarDadosBtn" id="guardar">

    </form>
</section>