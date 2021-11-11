<script>   

    function callback(evento){
        if(evento==1){
            var excluir = document.getElementById('excluir');
            submit('excluir');
            close_modal();
        }else if(evento==2){
            close_modal();
        }else{
            close_modal();
        }
    }

    function excluir(){
        display_modal("Confirmação de exclusão","Deseja realmente excluir o usuario <b><?php echo $usuario->txt_usuario; ?></b> referente à <b><?php echo $usuario->txt_nome; ?></b>?",callback,"Sim, pode excluir!","Não, deixe como está!");
    }
</script>
<form id="excluir" action="?controller=usuarioscontroller&method=excluir&id=<?php echo $usuario->id_user; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post">
        <input type="hidden" name="excluir" value="1"/>
</form>

<form id=form class="form-horizontal" action="?controller=usuarioscontroller&<?php echo isset($usuario->id_user) ? "method=atualizar&id={$usuario->id_user}" : "method=salvar"; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post" >

<br/><br/><br/>
<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
 <div class="form-group">
 <div class="row">
   <div class="col-10 col-md-4 col-lg-4">      
      <label for="txt_nome">Nome completo</label>
      <input type="text" id="txt_nome" class="form-control" aria-describedby="erro_nome" name="txt_nome" value="<?php
                echo isset($usuario->txt_nome) ? $usuario->txt_nome : null;
                ?>"/>
      <div id="erro_txt_nome" class="form-text text-muted"></div>
  </div>
  <div class="col-10 col-md-3 col-lg-2">      
      <label for="txt_usuario">Usuário</label>
      <input type="text" id="txt_usuario" class="form-control" aria-describedby="erro_usuario" id="txt_usuario" name="txt_usuario" value="<?php
                echo isset($usuario->txt_usuario) ? $usuario->txt_usuario : null;
                ?>"/>
      <div id="erro_txt_usuario" class="msg_error form-text text-muted"></div>
  </div>  
  </div>
  </br>
  <div class="row">
  <div class="col-10 col-md-4 col-lg-4">      
      <label for="txt_email">E-mail</label>
      <input type="text" id="txt_email" class="form-control" aria-describedby="erro_email" name="txt_email" value="<?php
                echo isset($usuario->txt_email) ? $usuario->txt_email : null;
                ?>"/>
      <div id="erro_txt_email" class="msg_error form-text text-muted"></div>
  </div>
  <div class="col-10 col-md-3 col-lg-2">      
      <label for="txt_email">Senha</label>
      <input type="text" id="txt_senha" class="form-control" aria-describedby="erro_senha" name="txt_senha" value=""/>
      <div id="erro_txt_senha" class="msg_error form-text text-muted"></div>
  </div>  
  </div>
  <div class="row"> 
  <div class="col-12 col-md-6 col-lg-6">      
      <label for="txt_cpf">CPF</label>
      <input type="text" id="txt_cpf" class="form-control" aria-describedby="erro_txt_cpf" name="txt_cpf" value="<?php
                echo isset($usuario->txt_cpf) ? $usuario->txt_cpf : null;
                ?>"/>
      <div id="erro_txt_cpf" class="msg_error form-text text-muted"></div>
  </div>
  <div class="col-12 col-md-6 col-lg-6">  
      <label for="id_role">Função:</label>
      <select  style="width:300px" class="form-control" id="id_role" name="id_role" value="<?php
                echo isset($usuario->id_role) ? $usuario->id_role : null;
                ?>">
    <option value=""> Sem função </option>                    
      <?php          
    foreach($roles as $role){ ?>    
    <option <?php if($role->id_role == (isset($usuario->id_role) ? $usuario->id_role : 0)) echo "selected";?> value="<?php echo $role->id_role; ?>"> <?php echo $role->role; ?></option>    
    <?php } ?>
    </select>
  </div>
  </div>

  
    </br>

    <input type="hidden" name="id_user" id="id_user" value="<?php echo isset($usuario->id_user) ? $usuario->id_user : null; ?>" />
                <input type="hidden" name="salvar" id="salvar" value="1" />
                <a class="btn btn-success" id=bt_submit name=submit onclick="validar('form','usuario');"><font color=black>Enviar</font></a>
                <a class="btn btn-secondary" id=bt_limpar onclick="form.clear();"><font color=black>Limpar</font></a>
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=usuarioscontroller&method=listar&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');">Cancelar</a>
                <?php if(isset($usuario->id_user)){ ?>
                  <a class="btn btn-danger" id=bt_excluir  onclick="excluir();">Excluir</a>
                <?php } ?>


    </div>
    
</form>
<span id="ajaxloading" style="display:none;">Validando formulário...</span>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');

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


