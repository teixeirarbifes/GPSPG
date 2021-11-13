<form id=form class="form-horizontal" action="/?controller=usuarioscontroller&method=alterar_perfil" method="post" >
    
<br/>        <div class="container" style="max-width:100%;width:500px">
                    <div class="form-group">
                    <h1>Perfil usuário</h1>
                    <p class="description">Este é o formulário para alterar o perfil de usuário.</p>
                    <a style="cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer"  class="btn btn-warning"  onclick="go_link('/?controller=usuarioscontroller&method=alterar_senha');"><i class="icon-head"></i>Para alterar a senha, clique aqui.</span></a> 
                    <!-- Input fields -->
                    </div>
                    <div class=row>                    
                    <div id=display_erro class="alert alert-danger display-error" style="display: none">
                    <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="txt_nome">Nome Completo:</label>
                        <input type="text" class="form-control" id="txt_nome" placeholder="Digite seu nome completo..." name="txt_nome" value="<?php echo $usuario->txt_nome?>" >
                        <div id="erro_txt_nome" class="msg_error form-text text-muted"></div>
                    </div>
                    <div class="form-group">
                        <label for="txt_email">Email:</label>
                        <input type="text" class="form-control" id="txt_email" placeholder="Digite seu email..." name="txt_email" value="<?php echo ($usuario->txt_email2==null ? $usuario->txt_email : $usuario->txt_email2)?>">
                        <div id="erro_txt_email" class="msg_error form-text text-muted"></div>
                        <?php if($usuario->txt_email2 !=null && $usuario->txt_email2!=""){ ?>
                        <b>ATENÇÃO</b></br>
                        <div id="" class="form-text text-muted" style="color:red">A alteração do <b>'<?php echo $usuario->txt_email2?>'</b> ainda NÃO VALIDADA.</br>
                        Enquanto não houver validação, emails ainda serão enviados para '<?php echo $usuario->txt_email?>'</br>Para receber novo e-mail de confirmação, salve novamente as alterações.</br></br></div>
                        <div class="form-group">
                        <label for="txt_chave2">Informe a chave e clique em salvar para efetivar a mudança para o e-mail '<?php echo $usuario->txt_email2;?>'</label>
                        <input type="text" class="form-control" name="txt_chave2">
                        </div>
                        <div>
                        <?php } ?>
                    </div>
                    <a class="btn btn-success" onclick="validar('form','perfil');"><font color=black>Salvar alterações</font></a>
                    </br>
                    <hr><b><font size=2>Abaixo, seguem dados do perfil de usuário que não podem ser alterados.</b>. Caso haja alguma inconsistência nas informações abaixo, entre em contato com o suporte pelo e-mail suporte@gpspg.gemad.net.</font>
                    </br></br>
                    <div class="form-group">
                        <label for="txt_cpf">CPF:</label>
                        <input type="text" class="form-control" disabled value="<?php echo $usuario->txt_cpf?>">
                    </div>                    
                    <div class="form-group">
                        <label for="txt_usuario">Usuário:</label>
                        <input type="text" class="form-control" disabled value="<?php echo $usuario->txt_usuario?>">
                    </div>
                    <input type="hidden" name="id_user" id="id_user" value="<?php echo $usuario->id_user; ?>" />
                    </br>                
                    <!-- End input fields -->
                <!-- Form end -->
        </div>
    
    </form>
    <script src="../ajax/ajax_submit.js"></script>
<script>
setup_check_change();
</script>
<input type=hidden id=formulario value="form"/>




