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
        display_modal("Confirmação de exclusão","Deseja realmente excluir o status <b><?php echo $status->id_status; ?></b>?",callback,"Sim, pode excluir!","Não, deixe como está!");
    }
</script>
<form id="excluir" action="?controller=statuscontroller&method=excluir&id=<?php echo $status->id_status; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post">
        <input type="hidden" name="excluir" value="1"/>
</form>
<h1>Cadastro de Status</h1>
<hr>

<form id=form class="form-horizontal" action="?controller=statuscontroller&<?php echo isset($status->id_status) ? "method=atualizar&id={$status->id_status}" : "method=salvar"; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post" >

<br/><br/><br/>
<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
 
<div class="form-group">

<div class="row">
  <div class="col-12 col-md-2 col-lg-2">      
      <label for="id_status_field">Ordem</label>
      <input type="text" id="id_status_field" class="form-control" aria-describedby="erro_id_status_field" name="id_status_field" value="<?php
                echo isset($status->id_status) ? $status->id_status : null;
                ?>"/>
      <div id="erro_id_status_field" class="form-text text-muted"></div>
  </div>
</div>
 <div class="row">
   <div class="col-10 col-md-4 col-lg-4">      
      <label for="txt_status">Status</label>
      <input type="text" id="txt_status" class="form-control" aria-describedby="erro_status" name="txt_status" value="<?php
                echo isset($status->txt_status) ? $status->txt_status : null;
                ?>"/>
      <div id="erro_txt_status" class="form-text text-muted"></div>
  </div>
  </div>
  <div class="row">
  <div class="col-12 col-md-2 col-lg-2">      
      <label for="num_ordem">Ordem</label>
      <input type="text" id="num_ordem" class="form-control" aria-describedby="erro_num_ordem" name="num_ordem" value="<?php
                echo isset($status->num_ordem) ? $status->num_ordem : null;
                ?>"/>
      <div id="erro_num_ordem" class="form-text text-muted"></div>
  </div>
</div>
</br>
</br>
 <div class="row">
  <div class="col-12 col-md-12 col-lg-12">
  <input class="form-check-input" type="checkbox" value="1" id="bl_publicado" name="bl_publicado" <?php echo (isset($status->bl_publicado) ? ($status->bl_publicado == 1 ? "checked" : "") : ""); ?>>
  <label class="form-check-label" for="bl_publicado">
    Processo seletivo publicado?
  </label>
  </div>
    </br></br>
  <div  class="col-12 col-md-12 col-lg-12">
  <input class="form-check-input" type="checkbox" value="1" id="bl_aberto" name="bl_aberto" <?php echo (isset($status->bl_aberto) ? ($status->bl_aberto == 1 ? "checked" : "") : ""); ?>>
  <label class="form-check-label" for="bl_aberto">
    Permitir inscrições?
  </label>
  </div>
  </br></br>
  <div  class="col-12 col-md-12  col-lg-12">
  <input class="form-check-input" type="checkbox" value="1" id="bl_recurso" name="bl_recurso" <?php echo (isset($status->bl_recurso) ? ($status->bl_recurso == 1 ? "checked" : "") : ""); ?>>
  <label class="form-check-label" for="bl_recurso">
    Permitir novos recursos?
  </label>
  </div>
 </div>
  
 
    </br>

    <input type="hidden" name="id_status" id="id_status" value="<?php echo isset($status->id_status) ? $status->id_status : null; ?>" />
                <input type="hidden" name="salvar" id="salvar" value="1" />
                <a class="btn btn-success" id=bt_submit name=submit onclick="validar('form','status');"><font color=black>Enviar</font></a>
                <a class="btn btn-secondary" id=bt_limpar onclick="form.clear();"><font color=black>Limpar</font></a>
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=statuscontroller&method=listar&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');">Cancelar</a>
                <?php if(isset($status->id_status)){ ?>
                  <a class="btn btn-danger" id=bt_excluir  onclick="excluir();">Excluir</a>
                <?php } ?>


    </div>
    
</form>
<span id="ajaxloading" style="display:none;">Validando formulário...</span>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>
