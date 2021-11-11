<h1>Processo Seletivo</h1>
<hr>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link btn btn-primary" id=bt_cronograma  onclick="go_link('?controller=cronogramacontroller&method=listar&id_processo=<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');"><font color=black>Cronograma</font></a>
      </li>
      <li class="nav-item">
        <a class="nav-link btn btn-primary" id=bt_cancelar  onclick="go_link('?controller=processoscontroller&method=editar&id_processo=<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');"><font color=black>Anexar Documento</font></a>
      </li>
      <li class="nav-item">
      <a class="nav-link btn btn-primary" id=bt_cancelar  onclick="go_link('?controller=processoscontroller&method=editar&id_processo=<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');"><font color=black>Inscrições</font></a>
      </li>
      <li class="nav-item">
      <a class="nav-link btn btn-primary disabled" aria-disabled="true" id=bt_cancelar  onclick="go_link('?controller=processoscontroller&method=editar&id_processo=<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');"><font color=black>Anexos</font></a>
      </li>
    </ul>
  </div>
</nav>
<br/><br/>
<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
 <div class="form-group">
 <div class="row">
   <div class="col-sm-12 col-md-6">      
      <label for="txt_processo">Título do Processo Seletivo</label>
      <div class="border"><?php
                echo isset($processo->txt_processo) ? $processo->txt_processo : null;
                ?>"/>
      </div>
  </div>
  </div>
  </br>
  <div class="row">
  <div class="col-sm-12 col-md-6">      
  <label for="txt_descricao">Descrição:</label>
    <div class="border" style="min-height:300px">
      <?php
                echo isset($processo->txt_descricao) ? $processo->txt_descricao : null;
                ?>
    </div>
  </div>  

</div>
  <div class="row"> 
  <div class="col-10 col-md-3 col-lg-2">  
      <label for="id_status">Função:</label>
      <select class="combo" id="id_status" disabled  name="id_status" value="<?php
                echo isset($processo->id_status) ? $processo->id_status : null;
                ?>">
    <option value=""> Sem função </option>                    
      <?php          
    foreach($status as $status){ ?>    
    <option <?php if($status->id_status == (isset($processo->id_status) ? $processo->id_status : 0)) echo "selected";?> value="<?php echo $status->id_status; ?>"> <?php echo $status->txt_status; ?></option>    
    <?php } ?>
    </select>
  </div>
  </div>

  
    </br>
    <input type="hidden" name="id_processo" id="id_processo" value="<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>" />
                <input type="hidden" name="salvar" id="salvar" value="1" />
                <a class="btn btn-primary" id=bt_cancelar  onclick="go_link('?controller=processoscontroller&method=editar&id_processo=<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');"><font color=black>Editar</font></a>
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=processoscontroller&method=listar&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');"><font color=black>Voltar à Lista</font></a>
                <?php if(isset($processo->id_processo)){ ?>
                  <!--a class="btn btn-danger" id=bt_excluir  onclick="excluir();"><font color=black>Excluir</font></a-->
                <?php } ?>


    </div>
<span id="ajaxloading" style="display:none;">Validando formulário...</span>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>
