
<section class="registo-section">
    <h2>Registar como cliente</h2>
    
    <form method="post" enctype="multipart/form-data" id="registoForm" class="registo-form ">
        <div class="grupo-registo">
        <input type="text" id="nome" name="nome" require>
        <label for="nome" >Nome:</label><br>
        <span class="errorRegisto" id="nomeError"></span>
        </div>
        
        
        <div class="grupo-registo">
        <input type="text" id="username" name="username" require>
        <label for="username" >Username:</label>
        </div>

        <div class="grupo-registo">
        <input type="email" id="email" name="email" require>
        <label for="email" >Email:</label><br>
        <span class="errorRegisto" id="emailError"></span>
        </div>
        

        <div class="grupo-registo">
        <input type="password" id="password" name="password" require>
        <label for="password" >Password:</label><br>
        <span class="errorRegisto" id="passwordError"></span>
        </div>
        

        <div class="grupo-registo">
        <input type="password" id="cpassword" name="cpassword" require>
        <label for="cpassword" >Confirmar Password:</label><br>
        <span class="errorRegisto" id="cpasswordError"></span>
        </div>
        

        <div class="grupo-registo">
        <input type="tel" id="telefone" name="telefone" require>
        <label for="telefone" >Telefone:</label><br>
        <span class="errorRegisto" id="telefoneError"></span>
        </div>
        

        <div class="grupo-registo">
        <input type="text" id="morada" name="morada" require>
        <label for="morada" >Morada:</label><br>
        <span class="errorRegisto" id="moradaError"></span>
        </div>

        <div class="grupo-registo">
        <input type="text" id="data_nascimento" name="data_nascimento"  require>
        <label for="data_nascimento" >Data de Nascimento:</label><br>
        <span class="errorRegisto" id="data_nascimentoError"></span>
        </div>
        

        <div class="grupo-registo">
        <input type="text" id="nif" name="nif" require>
        <label for="nif" >NIF:</label><br>
        <span class="errorRegisto" id="nifError"></span>
        </div>
        

        <div class="grupo-registo">
        <input type="file" id="upload-img" name="imagem" >
        <label for="imagem" >Imagem do Perfil:</label><br>
        <span class="errorRegisto" id="imgError"></span>
        </div>
        
        
        <label for="terms" class="terms-label"><input type="checkbox" id="terms" name="terms" require> Aceitar os Termos e Condições</label><br><br>
        <input type="submit" value="Registar" class="registo-btn">
    </form>
</section>
