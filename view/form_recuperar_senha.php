
<form id=form class="form-horizontal" action="/?controller=usuarioscontroller&method=reenviar_codigo" method="post" >
<br><br>

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class=".container" style="">
                    <h1>Recuperação de Acesso</h1>
                    <p class="description">Digite seu usuário para recuperar a sua senha.</p>
                    <p>Pode informar CPF ou e-mail como usuário.</p>
                    <!-- Input fields -->
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" class="form-control username" id="txt_usuario" placeholder="Digite seu usuário..." name="txt_usuario">
                    </div>
                    <input type="hidden" name="logando" id="logando" value="1" />
                    <a class="btn btn-success" onclick="submit('form');"><font color=black>Recuperar</font></a>
                    <a class="btn btn-secondary" onclick="$('#form').clear();"><font color=black>Limpar</font></a>
                    <a class="btn btn-danger" onclick="go_link('?controller=homecontroller&method=home');">Cancelar</a>
                    <!-- End input fields -->
                <!-- Form end -->
            </div>
        </div>
</div>

</form>
<script src="../ajax/ajax_submit.js"></script>