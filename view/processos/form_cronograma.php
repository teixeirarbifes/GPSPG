<script>   
delete excluir;
delete callback;

    callback = function(evento){
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

    excluir = function(){
        display_modal("Confirmação de exclusão","Deseja realmente excluir o cronograma <b><?php echo $cronograma->id_cronograma; ?></b>?",callback,"Sim, pode excluir!","Não, deixe como está!");
    }
</script>
<form id="excluir" action="?controller=cronogramacontroller&excluir=1&method=excluir&id_processo=<?php echo isset($cronograma->id_processo) ? $cronograma->id_processo : $processo->id_processo; ?>&id=<?php echo $cronograma->id_cronograma; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post">
        <input type="hidden" name="excluir" value="1"/>
</form>
<form id=form class="form-horizontal" action="?controller=cronogramacontroller&id_processo=<?php echo isset($cronograma->id_processo) ? $cronograma->id_processo : $processo->id_processo; ?>&<?php echo isset($cronograma->id_cronograma) ? "method=atualizar&id={$cronograma->id_cronograma}" : "method=salvar"; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post" >
 <div class="form-group">

 <div><h1>Cronograma</h1></div>
<div><h4>Edital: <?php echo $processo->txt_processo; ?></h4></div>
<div><hr></div>

<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>

<div class="row"> 
  <div class="col-12 col-md-6 col-lg-6">  
    <label for="txt_descricao">Descrição:</label>
    <input class="form-control"  type="text" name="txt_descricao" value="<?php echo isset($cronograma->txt_descricao) ? $cronograma->txt_descricao : ""; ?>">
 </div>
 </div>
  <div class="row"> 
  <div class="col-10 col-md-3 col-lg-2">  
      <label for="id_status">Status:</label>
      <select class="combo" id="id_status" name="id_status" value="<?php
                echo isset($cronograma->id_status) ? $cronograma->id_status : null;
                ?>">
      <?php          
    foreach($status as $status){ ?>    
    <option <?php if($status->id_status == (isset($cronograma->id_status) ? $cronograma->id_status : 0)) echo "selected";?> value="<?php echo $status->id_status; ?>"> <?php echo $status->txt_status; ?></option>    
    <?php } ?>
    </select>
  </div>
  </div>
  </br>
  <div class="row"> 
  <div class="col-12 col-md-10 col-lg-10">  
    <label for="id_status">Data e Hora de Inicio:</label>
    <div class="row"> 
      <div class="col-12 col-md-2">  
        <input class="form-control"  type="date" name="dt_inicio" placeholder="select Birth date" >
      </div>
      <div class="col-12 col-md-2">  
        <input class="form-control"  type="time" name="hr_inicio" placeholder="select Birth date" >
      </div>
      </div>
  </div>
  </div>
  </br>
  <div class="row"> 
  <div class="col-12 col-md-10 col-lg-10">  
    <label for="id_status">Data e Hora de Fim:</label>
    <div class="row"> 
      <div class="col-12 col-md-2">  
        <input class="form-control"  type="date" name="dt_fim" placeholder="select Birth date" >
      </div>
      <div class="col-12 col-md-2">  
        <input class="form-control"  type="time" name="hr_fim" placeholder="select Birth date" >
      </div>
      </div>
  </div>
  </div>


    </br>

    <input type="hidden" name="id_processo" id="id_processo" value="<?php echo isset($cronograma->id_processo) ? $cronograma->id_processo : $processo->id_processo; ?>" />
    <input type="hidden" name="id_cronograma" id="id_cronograma" value="<?php echo isset($cronograma->id_cronograma) ? $cronograma->id_cronograma : null; ?>" />
                <input type="hidden" name="salvar" id="salvar" value="1" />
                <a class="btn btn-success" id=bt_submit name=submit onclick="validar('form','cronograma');"><font color=black>Enviar</font></a>
                <a class="btn btn-secondary" id=bt_limpar onclick="form.clear();"><font color=black>Limpar</font></a>
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=cronogramacontroller&method=listar&id_processo=<?php echo isset($cronograma->id_processo) ? $cronograma->id_processo : $processo->id_processo; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');">Cancelar</a>
                <?php if(isset($cronograma->id_cronograma)){ ?>
                  <a class="btn btn-danger" id=bt_excluir  onclick="excluir();">Excluir</a>
                <?php } ?>


    </div>
    
</form>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>
