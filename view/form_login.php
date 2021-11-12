
<form id=form autocomplete="off" class="form-horizontal" action="/?controller=usuarioscontroller&method=login" method="post" >
<br><br>

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class=".container border p-3"  style="width:400px">
                    <h1>Acesso ao Sistema</h1>
                    <hr>
                    <p class="description"><font size=2 color=darkblue><b>Digite as suas credenciais de acesso.</b></font></p></br>
                    <!-- Input fields -->
                    <div class="form-group">
                        <label for="username">Usuário, CPF ou e-mail cadastrado:</label>
                        <input type="text" class="form-control username" id="txt_usuario" placeholder="Digite seu usuário..." name="txt_usuario">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha de acesso:</label>
                        <input type="password" class="form-control password" id="txt_senha" placeholder="Digite sua senha..." name="txt_senha">
                    </div>
                    <input type="hidden" name="logando" id="logando" value="1" />
                    <a class="btn btn-primary" onclick="submit('form');"><font color=black>Entrar no Sistema</font></a></br>
                    </br></br>    
                    <p class="description"><font size=2 color=darkred>               
                    Caso não tenha credenciais, faça o cadastro.</br>
                    Caso não lembre a senha, solicite recuperação.</br>
                    </font></p>
                    <a class="btn btn-success" style="cursor:pointer;padding-left: 6px;padding-top: 6px;padding-right: 6px;padding-bottom: 6px;cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=registrar1');"><font color=black>Registrar-se</font></a>
                    <a class="btn btn-warning" style="cursor:pointer;padding-left: 6px;padding-top: 6px;padding-right: 6px;padding-bottom: 6px;cursor:pointer" onclick="go_link('?controller=usuarioscontroller&method=form_recuperar');"><font color=black>Recuperar acesso</font></a>
                    
                    <!-- End input fields -->
                <!-- Form end -->
            </div>
        </div>
</div>

</form>
<script src="../ajax/ajax_submit.js"></script>