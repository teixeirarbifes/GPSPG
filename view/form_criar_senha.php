    <br/>
     
            <div class="container" style="max-width:100%;width:500px">
               <form id="form" class="form-horizontal" action="/?controller=usuarioscontroller&method=save_alterar_senha" method="post" >
                    <div class=row>                    
                    <div id=display_erro class="alert alert-danger display-error" style="display: none">
                    <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
                    </div>
                    </div>
                    <div class="form-group">
                    <h1>Alterar senha inicial</h1>
                    <p class="description"><b><span style="color:red">A sua senha inicial precisa ser alterada por segurança.</span></b></p>
                    <p class="description"><span>Para alterar sua senha, digite sua senha atual e informe sua nova senha nos campos abaixo:</span></p>
                    </div>
                    <!-- Input fields -->
                    <div class="form-group">
                        <label for="txt_atual">Senha Atual:</label>
                        <input type="password" class="form-control password" id="txt_atual" placeholder="Digite sua senha..." name="txt_atual">
                    </div>
                    <div class="form-group">
                        <label for="txt_senha">Digite sua senha:</label>
                        <input type="password" class="form-control password" id="txt_senha" placeholder="Digite seu usuário..." name="txt_senha">
                    </div>
                    <div class="form-group">
                        <label for="txt_senha2">Repita sua senha:</label>
                        <input type="password" class="form-control password" id="txt_senha2" placeholder="Repita a sua senha..." name="txt_senha2">
                    </div>
                    <a class="btn btn-success" onclick="validar('form','senha');"><font color=black>Alterar senha</font></a>
                    <a class="btn btn-secondary" onclick="go_link('/?controller=usuarioscontroller&method=form_perfil');"><font color=black>Voltar ao perfil</font></a>
                    <input type="hidden" name="change_pass" id="change_pass" value="1" />
                    <!-- End input fields -->
                <!-- Form end -->
                </form>
            </div>

    <script src="../ajax/ajax_submit.js"></script>

<script>
setup_check_change();
</script>
<input type=hidden id=formulario value="form"/>