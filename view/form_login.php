
<form id=form autocomplete="off" class="form-horizontal" action="/?controller=usuarioscontroller&method=login" method="post" >
<br><br>

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class=".container" style="">
                    <h1>Acesso ao Sistema</h1>
                    <p class="description">Digite seu usuário e senha para acesso ao sistema.</p>
                    <p>Pode informar CPF ou e-mail como usuário.</p>
                    <!-- Input fields -->
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" class="form-control username" id="txt_usuario" placeholder="Digite seu usuário..." name="txt_usuario">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control password" id="txt_senha" placeholder="Digite sua senha..." name="txt_senha">
                    </div>
                    <input type="hidden" name="logando" id="logando" value="1" />
                    <a class="btn btn-success" onclick="submit('form');"><font color=black>Entrar</font></a>
                    <a class="btn btn-secondary" onclick="$('#form').clear();"><font color=black>Limpar</font></a>
                    <a class="btn btn-danger" onclick="go_link('?controller=homecontroller&method=home');">Cancelar</a>
                    </br></br>
                    <a class="btn btn-success" onclick="go_link('?controller=usuarioscontroller&method=form_recuperar');"><font color=black>Recuperar acesso/Ativação</font></a>
                    <!-- End input fields -->
                <!-- Form end -->
            </div>
        </div>
</div>

</form>
<script src="../ajax/ajax_submit.js"></script>