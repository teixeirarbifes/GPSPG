        <div class="row justify-content-center">
            <div class=".container border p-2 " style="max-width:500px;width:100%;">
                <form id=form class="form-horizontal" action="/?controller=usuarioscontroller&method=ativar_conta" method="post" >    
                <div class=row>
                <div id=display_erro class="alert alert-danger display-error" style="display: none">
                <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
                </div>
                </div>

                    <div class="form-group">
                    <h1>Validação da sua conta</h1>
                    <p class="description" style="color:red">Sua conta precisa ser validada para gerar sua senha.</p>
                    </div>
                    <!-- Input fields -->
                    <div class="form-group">
                        <label for="txt_email">Informe o seu e-mail de cadastro:</label>
                        <input type="text" class="form-control" id="txt_email" placeholder="Digite seu email..." name="txt_email"
                        value="<?=isset($_GET["email"]) ? $_GET["email"] : ""?>" >
                    </div>
                    <div class="form-group">
                        <label for="key">Digite o seu código de validação:</label>
                        <input type="text" class="form-control" id="key" placeholder="Digite o código..." name="key"
                        value="<?=isset($_GET["key"]) ? $_GET["key"] : ""?>"></br>
                        <b>O código de validação é enviado para o e-mail de cadastro.</b></br></br>
                        <a class="btn btn-warning" style="cursor:pointer;padding-left: 6px;padding-top: 6px;padding-right: 6px;padding-bottom: 6px;cursor:pointer" onclick="go_link('?controller=usuarioscontroller&method=form_recuperar');"><font color=black>Reenviar e-mail com o código.</font></a>
                    </div>
                    <div class="form-group">
                    <h3>Criação de nova senha</h3>
                    <p class="description"><span style="color:red">Nesse ato da validação da sua conta, defina sua senha.</span></p>
                    </div>
                    <!-- Input fields -->
                    A senha deve ter de 8 a 20 caracteres com, pelo menos:</br>
                    * uma letra maiúscula</br>
                    * uma letra minúscula</br>
                    * um número de 0 a 9</br>
                    * um caractere especial @ $ ! % * ? &</br></br>

                    <div class="form-group">
                        <label for="txt_senha">Digite sua senha:</label>
                        <input type="password" class="form-control password" id="txt_senha" placeholder="Digite sua senha..." name="txt_senha">
                    </div>
                    <div class="form-group">
                        <label for="password">Repita sua senha:</label>
                        <input type="password" class="form-control password" id="txt_senha2" placeholder="Digite novamente sua senha..." name="txt_senha2">
                    </div>
                    <a class="btn btn-success" onclick="validar('form','ativar');"><font color=black>Validar conta</font></a>
                    <a class="btn btn-secondary" onclick="go_link('/?controller=usuarioscontroller&method=form_login');"><font color=black>Cancelar</font></a>

                    

                    <input type="hidden" name="validar" id="validar" value="1" />
                    <!-- End input fields -->
                <!-- Form end -->
                </form>
            </div>            
        </div>
    <script src="../ajax/ajax_submit.js"></script>