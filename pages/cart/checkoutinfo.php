<section class="registo-section">
    <h2>Dados para Envio</h2>
    
    <form method="post" enctype="multipart/form-data" id="registoForm" class="registo-form ">
        <div class="grupo-registo">
        <input type="text" id="nome" name="nome" require>
        <label for="nome" >Nome:</label><br>
        <span class="errorRegisto" id="nomeError"></span>
        </div>

        <div class="grupo-registo">
        <input type="text" id="morada" name="morada" require>
        <label for="morada" >Morada:</label><br>
        <span class="errorRegisto" id="moradaError"></span>
        </div>

        <div class="grupo-registo">
        <input type="text" id="localidade" name="localidade" require>
        <label for="localidade" >Localidade:</label><br>
        <span class="errorRegisto" id="moradaError"></span>
        </div>

        <div class="grupo-registo">
        <input type="text" id="cod-postal" name="cod-postal" require>
        <label for="cod-postal" >Codigo Postal:</label><br>
        <span class="errorRegisto" id="moradaError"></span>
        </div>

        <div class="grupo-registo">
        <input type="email" id="email" name="email" require>
        <label for="email" >Email:</label><br>
        <span class="errorRegisto" id="emailError"></span>
        </div>
        
        <div class="grupo-registo">
        <input type="tel" id="telefone" name="telefone" require>
        <label for="telefone" >Telefone:</label><br>
        <span class="errorRegisto" id="telefoneError"></span>
        </div>
        
        <div class="grupo-registo">
        <input type="text" id="nif" name="nif" require>
        <label for="nif" >NIF:</label><br>
        <span class="errorRegisto" id="nifError"></span>
        </div>
        
        
        <label for="terms" class="terms-label"><input type="checkbox" id="terms" name="terms" require> Aceitar os Termos e Condições</label><br><br>
        <input type="submit" value="Fornecer dados" class="registo-btn">
    </form>
</section>