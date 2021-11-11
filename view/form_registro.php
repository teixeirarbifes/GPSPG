<form id=form class="form-horizontal" action="/?controller=usuarioscontroller&method=salvar_registro1" method="post" >
    
<br/><br/></br>
        <div class="row justify-content-center">
            <div class=".container" style="">
                    <div class="form-group">
                    <h1>Registro de novo usuário</h1>
                    <p class="description">Para registrar um novo usuário, inicialmente informe o seu nome completo, um usuário novo e seu e-email.</p>
                    <!-- Input fields -->
                    </div>
                    <div class=row>                    
                    <div id=display_erro class="alert alert-danger display-error" style="display: none">
                    <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="txt_nome">Nome Completo:</label>
                        <input type="text" class="form-control" id="txt_nome" placeholder="Digite seu nome completo..." name="txt_nome">
                        <div id="erro_txt_nome" class="msg_error form-text text-muted"></div>
                    </div>
                    <div class="form-group">
                        <label for="txt_cpf">CPF:</label>
                        <input type="text" class="form-control" id="txt_cpf" placeholder="Digite o seu CPF..." name="txt_cpf">
                        <div id="erro_txt_cpf" class="msg_error form-text text-muted"></div>
                    </div>                         
                    <div class="form-group">
                        <label for="txt_email">Email:</label>
                        <input type="text" class="form-control" id="txt_email" placeholder="Digite seu email..." name="txt_email">
                        <div id="erro_txt_email" class="msg_error form-text text-muted"></div>
                    </div>       
                    <div class="form-group">
                        <label for="txt_usuario">Usuário:</label>
                        <input type="text" class="form-control" id="txt_usuario" placeholder="Digite seu usuário..." name="txt_usuario">
                        <div id="erro_txt_usuario" class="msg_error form-text text-muted"></div>
                    </div>

                    <input type="hidden" name="logando" id="logando" value="1" />
                    <a class="btn btn-success" onclick="validar('form','registro1');"><font color=black>Entrar</font></a>
                    <a class="btn btn-secondary" onclick="$('#form').clear();"><font color=black>Limpar</font></a>
                    <a class="btn btn-danger" onclick="go_link('?controller=homecontroller&method=home');">Cancelar</a>
                    <!-- End input fields -->
                <!-- Form end -->
        </div>
    </div>
    
    </form>
    <script src="../ajax/ajax_submit.js"></script>


    <script>

$(document).ready(function(){
  $.jMaskGlobals = {
    maskElements: 'input,td,span,div',
    dataMaskAttr: '*[data-mask]',
    dataMask: true,
    clearIfNotMatch : true,
    watchInterval: 300,
    watchInputs: true,
    watchDataMask: false,
    byPassKeys: [9, 16, 17, 18, 36, 37, 38, 39, 40, 91],
    translation: {
      '0': {pattern: /\d/},
      '9': {pattern: /\d/, optional: true},
      '#': {pattern: /\d/, recursive: true},
      'A': {pattern: /[a-zA-Z0-9]/},
      'S': {pattern: /[a-zA-Z]/}
    }
  };
  $('#txt_cpf').mask('000.000.000-00');
});

</script>




