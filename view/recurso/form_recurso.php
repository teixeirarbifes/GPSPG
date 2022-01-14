<h1>Formulário de Recurso do Processo Seletivo</h1>
<hr>

<?php

if(isset($data_table['sucess']) && $data_table['sucess'] == 1){
?>

O seu recurso foi submetido com êxito e um e-mail com o conteúdo submetido foi enviado para seu e-mail de cadastro.

</br></br>

Processo Seletivo: <b><?=$processo->txt_processo?></b></br></br>
Destinado à: Recurso contra o resultado parcial das inscrições</br></br>
Argumentação Fundamentada: </br><?=$recurso->txt_recurso?></br></br>
Submetido em: <?=$recurso->dt_submissao?> sob o protocolo <?=$recurso->txt_protocolo?> 


<?php }else{?>


<form id=form class="form-horizontal" action="?controller=recursocontroller&method=salvar" method="post" >

<br/><br/><br/>
<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
 <div class="form-group">
 <div class="row">
  <div class="col-12 col-md-12 col-lg-12">
    Processo Seletivo: <b><?=$processo->txt_processo?></b>
    </br></br>
   </div>
 </div>
 <div class="row">
  <div class="col-12 col-md-12 col-lg-12">
        Destinado à: <select class="combo" id="id_classe_recurso" name="id_classe_recurso" value="">
          <option value="">Selecione uma opção</option>                    
          <option value="1">recurso contra o resultado parcial das inscrições</option>                    
        </select>
        </br></br>
   </div>
 </div>
 <div class="row">
   <div class="col-10 col-md-4 col-lg-4">      
      <label for="txt_recurso">Argumentação Fundamentada:</label>
      <textarea style="resize: vertical;" rows="6" cols="50" id="txt_recurso" class="form-control" aria-describedby="erro_txt_recurso" name="txt_recurso">Digite aqui sua argumentação...</textarea>
      <div id="erro_txt_recurso" class="form-text text-muted"></div>
  </div>
  </div>
  <div class="row">
  <div class="col-md-12">      
     Enviar arquivo:
  </div>
</div>  
    </br>

    <input type="hidden" name="id_processo" id="id_processo" value="<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>" />
    <input type="hidden" name="salvar" id="salvar" value="1" />
    <a class="btn btn-success" id=bt_submit name=submit onclick="validar('form','recurso');"><font color=black>Enviar</font></a>
    <a class="btn btn-secondary" id=bt_limpar onclick="form.clear();"><font color=black>Limpar</font></a>
    </div>
    
</form>
<span id="ajaxloading" style="display:none;">Validando formulário...</span>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
</script>
<?php } ?>