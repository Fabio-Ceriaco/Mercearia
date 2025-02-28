
<section class="registo-section">
    <h2>Registar como cliente</h2>
    <form method="post" enctype="multipart/form-data" id="registoForm">
        <div class="grupo-registo">
        <input type="text" id="nome" name="nome" >
        <label for="nome" >Nome:</label>
        </div>
        
        <div class="grupo-registo">
        <input type="text" id="username" name="username" >
        <label for="username" >Username:</label>
        </div>

        <div class="grupo-registo">
        <input type="email" id="email" name="email" >
        <label for="email" >Email:</label>
        </div>

        <div class="grupo-registo">
        <input type="password" id="password" name="password" >
        <label for="password" >Password:</label>
        </div>

        <div class="grupo-registo">
        <input type="password" id="cpassword" name="cpassword" >
        <label for="cpassword" >Confirmar Password:</label>
        </div>

        <div class="grupo-registo">
        <input type="tel" id="telefone" name="telefone" >
        <label for="telefone" >Telefone:</label>
        </div>

        <div class="grupo-registo">
        <input type="text" id="morada" name="morada" >
        <label for="morada" >Morada:</label>
        </div>

        <div class="grupo-registo">
        <input type="text" id="data_nascimento" name="data_nascimento"  >
        <label for="data_nascimento" >Data de Nascimento:</label>
        </div>

        <div class="grupo-registo">
        <input type="text" id="nif" name="nif" >
        <label for="nif" >NIF:</label>
        </div>

        <div class="grupo-registo">
        <input type="file" id="upload-img" name="imagem" >
        <label for="upload-img" id="img-registo">Imagem do Perfil:</label>
        </div>
        
        
        <label for="terms" class="terms-label"><input type="checkbox" id="terms" name="terms" > Aceitar os Termos e Condições</label><br><br>
        <input type="submit" value="Registar" id="submit-btn">
    </form>
</section>
