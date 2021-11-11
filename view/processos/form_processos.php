<script>   
delete excluir;
delete callback;
$funcao_excluir = "excluir_form_processo";
    callback_form_processo = function(evento){
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

    excluir_form_processo = function(){
        display_modal("Confirmação de exclusão","Deseja realmente excluir o processo <b><?php echo $processo->id_processo; ?></b>?",callback_form_processo,"Sim, pode excluir!","Não, deixe como está!");
    }
</script>
<form id="excluir" action="?controller=processoscontroller&method=excluir&id=<?php echo $processo->id_processo; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post">
        <input type="hidden" name="excluir" value="1"/>
</form>
<h1>Cadastro de Processos Seletivos</h1>
<h4>Editando...</h4>
<hr>

<form id=form class="form-horizontal" action="?controller=processoscontroller&<?php echo isset($processo->id_processo) ? "method=atualizar&id={$processo->id_processo}" : "method=salvar"; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post" >

<br/><br/><br/>
<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
 <div class="form-group">
 <div class="row">
   <div class="col-10 col-md-4 col-lg-4">      
      <label for="txt_processo">Título do Processo Seletivo</label>
      <input type="text" id="txt_processo" class="form-control" aria-describedby="erro_txt_processo" name="txt_processo" value="<?php
                echo isset($processo->txt_processo) ? $processo->txt_processo : null;
                ?>"/>
      <div id="erro_txt_processo" class="form-text text-muted"></div>
  </div>
  </div>
  <div class="row">
  <div class="col-md-12">      
      <label for="txt_descricao">Descrição:</label>
      <textarea id="txt_descricao" name="txt_descricao">
      <?php
                echo isset($processo->txt_descricao) ? $processo->txt_descricao : null;
                ?>
  </textarea>
  <div id="erro_txt_descricao" class="msg_error form-text text-muted"></div>
  <script>
    tinymce.remove('#txt_descricao')
    tinymce.init({
    selector: '#txt_descricao',
    width: 600,
    height: 300,
    plugins: [
    'advlist autolink link image lists charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
    'table emoticons template paste help'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
    'forecolor backcolor emoticons | help',
    menu: {
    favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: 'favs file edit view insert format tools table help',
    content_css: 'css/content.css'
    });
  </script>
  </div>  

</div>
  <div class="row"> 
  <div class="col-10 col-md-3 col-lg-2">  
      <label for="id_status">Função:</label>
      <select class="combo" id="id_status" name="id_status" value="<?php
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
                <a class="btn btn-success" id=bt_submit name=submit onclick="tinyMCE.triggerSave(true,true);validar('form','processo');"><font color=black>Enviar</font></a>
                <a class="btn btn-secondary" id=bt_limpar onclick="form.clear();"><font color=black>Limpar</font></a>
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=processoscontroller&method=visualizar&id_processo=<?php echo isset($processo->id_processo) ? $processo->id_processo : null; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');">Cancelar</a>
                <?php if(isset($processo->id_processo)){ ?>
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
