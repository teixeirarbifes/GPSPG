<?php 
ob_start(); ?>
  <div class="{class}">      
    <label for="{field}">{label}</label>
    <input type="text" id="{field}" class="maskField form-control" aria-describedby="erro_{field}" name="{field}" value="{value}" {adicional}/>
    <div style="font-color:red;font-size:12px" class="form-text text-muted"><font color=red><div class="msg_error" id="erro_{field}">{msg}</div></font></div>
  </div>
<?php global $tmp_textfield;
$tmp_textfield = ob_get_contents(); 
ob_end_clean();

function textfield($label,$field,$class,$obrigatorio = false,$adicional="",$msg="",$value=""){
  global $tmp_textfield;
  global $ficha;
  if($field!="" && $field!=null)
  if(isset($ficha->{$field})) $value = $ficha->{$field};
  echo str_replace('{msg}',$msg,str_replace('{adicional}',$adicional,str_replace('{label}',$label.($obrigatorio ? '<font color=red>*</font>' : ''),str_replace('{class}',$class,str_replace('{value}',$value,str_replace('{field}',$field,$tmp_textfield))))));
}
?>
<h3><b><font color="darkblue"><?php echo $processo->txt_processo; ?></font></b></h3>
<hr>
<img src="images/editarficha.png" style="width:400px;"/></br>
<font color="darkred">A ficha, bem como toda inscrição, somente será considerada após o envio (ou reenvio). Lembre-se de salvar a sua inscrição após edição.</font>
<hr>

<div style="text-align:left">
<div class="container p-2">
<div class="row">
    <div class='col-md-12'>
      
                <a class="btn btn-primary" id=salvar  onclick="$('#voltar').val(0); validar('form','ficha');"><font color=black>Salvar</font></a>&nbsp;  
                <a class="btn btn-primary" id=salvar  onclick="$('#voltar').val(1); validar('form','ficha');"><font color=black>Salvar e Voltar</font></a>&nbsp;                
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=inscricaocontroller&method=dashboard&id_processo=<?php echo $processo->id_processo; ?>');"><font color=black>Voltar</font></a>
</div></div></div>
<form id=form class="form-horizontal" action="?controller=fichacontroller&method=atualizar&id=<?php echo $ficha->id_ficha; ?>" method="post" >
<input type=hidden id=voltar name=voltar value=0/>
<div class="form-group">
 <div class="container border p-2">
 <div class=row>
<div class=row>
<div class="col-md-12 col-sm-12">  
<div id=display_erro class="alert alert-danger display-error" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
</div>
</div>
  <div class="row">
  <form method="post" action="" enctype="multipart/form-data" id="myform">
  <div class="col-md-2 col-sm-12">    
        <div>
        <b>Sua foto:</b><br/>
        <img style="display:<?php echo isset($ficha->txt_photo) ? "block" : "none" ?>" src="<?php echo isset($ficha->txt_photo) ? "/photo.php?uq=".uniqid()."&id=".$ficha->id_ficha : "" ?>" id="img" width="162" height="180">
        </div>
        <div >
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="file_picture" id="file_picture" aria-describedby="inputGroupFileAddon01">
                    <input type="hidden" value="" name="id" id="id"/>
                    <input type="hidden" value="<?php echo isset($ficha->txt_photo) ? $ficha->id_ficha : "" ?>" name="id_saved" id="id_saved"/>

                    <span id="status_photo"><b><font color="red"><?php echo isset($ficha->txt_photo) && $ficha->txt_photo!="" ? "<b><font color=green>Foto salva!</font></b>" : "" ?></font></b></span>
                    <a id="excluir_photo" onclick="excluir_photo();" style="display:none;cursor:pointer">Excluir foto temporária</a>



                    <label class="custom-file-label"  for="txt_picture">Selecione</label>
                </div>
              <!--input type="button" class="button_photo" value="Upload" id="but_upload"-->
              <div id="erro_txt_picture" class="form-text text-muted"></div>
        </div>
        
   </div>
   </form>

   <div class="col-md-10 col-sm-12">
    <div class="row">
      <div class='col-md-12'><h4><b><font color=darkblue>Informe alguns dados iniciais:</font></b></h4></div>
      <?php textfield('Nome completo',  '',         'col-lg-6 col-md-6 col-sm-12',  false, 'disabled',"<b><font color=green size=1px>O nome completo pode ser alterado na página do perfil.</font></b>",UsuariosController::get_usuario()['txt_nome'] ); ?>
      <?php textfield('E-mail',         '',        'col-lg-6 col-md-6 col-sm-12', false, 'disabled',"<b><font color=green size=1px>O e-mail pode ser alterado na página do perfil.</font></b>",UsuariosController::get_usuario()['txt_email']); ?>
      <?php textfield('Nome da mãe',    'txt_nome_mae',     'col-lg-6 col-md-6 col-sm-12'); ?>
      <?php textfield('Nome do pai',    'txt_nome_pai',     'col-lg-6 col-md-6 col-sm-12'); ?>
      <?php textfield('Telefone',       'txt_telefone',     'col-lg-3 col-md-6 col-sm-12',false,"mask='(999) 9999–9999'"); ?>
      <?php textfield('Celular',        'txt_celular',      'col-lg-3 col-md-6 col-sm-12',false,"mask='(999) 99999–9999'"); ?>
      
      <div class="col-md-3 col-sm-5">      
        <label for="txt_civil">Estado Civil</label>
        <div class="container p-2" id="txt_civil">
        <select id="txt_civil" class="form-control" aria-describedby="erro_txt_civil" name="txt_civil" >
                        <option value="0"></option>
                        <option <?=$ficha->txt_civil == 1 ? "selected" : ""?> value="1">Casado</option>
                        <option <?=$ficha->txt_civil == 2 ? "selected" : ""?> value="2">Divorciado</option>
                        <option <?=$ficha->txt_civil == 3 ? "selected" : ""?> value="3">Separado</option>
                        <option <?=$ficha->txt_civil == 4 ? "selected" : ""?> value="4">Solteiro</option>
                        <option <?=$ficha->txt_civil == 5 ? "selected" : ""?> value="5">União estável</option>
                        <option <?=$ficha->txt_civil == 6 ? "selected" : ""?> value="6">Viúvo</option>
                            </select>   
        </div>     
        <div style="font-color:red;font-size:12px" class="form-text text-muted"><font color=red><div class="msg_error" id="erro_txt_civil"></div></font></div>
      </div>
      
      <div class="col-md-3 col-sm-5">      
        <label for="txt_sexo">Sexo</label>
        <div class="container p-2" id="txt_sexo">
        <select id="txt_sexo" class="form-control" aria-describedby="erro_txt_sexo" name="txt_sexo" >
                        <option value="0"></option>
                        <option <?=$ficha->txt_sexo == 1 ? "selected" : ""?> value="1">Masculino</option>
                        <option <?=$ficha->txt_sexo == 2 ? "selected" : ""?> value="2">Feminino</option>
                        <option <?=$ficha->txt_sexo == 3 ? "selected" : ""?> value="3">Ignorado</option>
                            </select>   
        </div>     
        <div style="font-color:red;font-size:12px" class="form-text text-muted"><font color=red><div class="msg_error" id="erro_txt_civil"></div></font></div>
      </div>
    
    </div>
   </div>
  </div>
  
</div>
</BR>
<div class="container border p-2">
  <div class="row">
      <div class='col-md-12'><h4><b><font color=darkblue>Informe a sua naturalidade:</font></b></h4></div>
      <?php textfield('País',           'txt_natural_pais', 'col-md-3 col-sm-12'); ?>
      <?php textfield('Estado',         'txt_natural_estado','col-md-2 col-sm-12'); ?>
      <?php textfield('Cidade',         'txt_natural_cidade','col-md-4 col-sm-12'); ?>
  </div>
</div>
<br/>
<div class="container border p-2">
  <div class="row">
    <div class='col-md-12'><h4><b><font color=darkblue>Informe os seus documentos</font></b></h4></div>
    <?php textfield('CPF',  '',         'col-lg-2 col-md-3 col-sm-12',  false, 'disabled',"<b><font color=green size=1px>O CPF foi informado no cadastro.</font></b>",UsuariosController::get_usuario()['txt_cpf'] ); ?>
  </div>
  <hr/>
  <div class="row">
    <div class='col-md-12'><h5><b>Registro Geral (RG)</b></54></div>
    <?php textfield('RG',             'txt_rg',           'col-md-4 col-sm-12'); ?>
    <?php textfield('Orgão Expedidor','txt_rg_orgao',     'col-md-2 col-sm-12'); ?>
    <?php textfield('UF',             'txt_rg_uf',        'col-md-2 col-sm-12'); ?>
    <div class="col-md-2 col-sm-12">      
    <label for="txt_rg_expedicao">Data de Expedição</label>
    <input type="date" id="txt_rg_expedicao" class="maskField form-control" aria-describedby="erro_txt_rg_expedicao" name="txt_rg_expedicao" value="<?php echo $ficha->txt_rg_expedicao; ?>" {adicional}/>
    <div  class="form-text text-muted"><font color=red><div class="msg_error" id="erro_txt_rg_expedicao"></div></font></div>
    </div>
  </div>
  <hr/>
  <div class="row">
    <div class='col-md-12'><h5><b>Título de Eleitor</b></54></div>
  
    <?php textfield('Título de Eleitor','txt_eleitor',    'col-md-4 col-sm-12'); ?>
    <?php textfield('Zona',           'txt_eleitor_zona', 'col-md-1 col-sm-12'); ?>
    <?php textfield('Seção',          'txt_eleitor_secao','col-md-1 col-sm-12'); ?>
    <?php textfield('Estado',         'txt_eleitor_estado','col-md-2 col-sm-12'); ?>
    <div class="col-md-2 col-sm-12">      
    <label for="txt_eleitor_emissao">Data de Emissão</label>
    <input type="date" id=txt_eleitor_emissao" class="maskField form-control" aria-describedby="erro_txt_eleitor_emissao" name="txt_eleitor_emissao" value="<?php echo $ficha->txt_eleitor_emissao; ?>" {adicional}/>
    <div  class="form-text text-muted"><font color=red><div class="msg_error" id="erro_txt_eleitor_emissao"></div></font></div>
    </div>
    </div>
</div>
</br>
<div class="container border p-2">
<div class="row">
    <div class='col-md-12'><h4><b><font color=darkblue>Informe o seu endereço</font></b></h4></div>
    <?php textfield('Logadouro',      'txt_logadouro',    'col-md-8 col-sm-12'); ?>
    <?php textfield('Número',         'txt_numero',       'col-md-1 col-sm-12'); ?>
    <?php textfield('Complemento',    'txt_complemento',  'col-md-4 col-sm-12'); ?>
    <?php textfield('CEP',            'txt_cep',          'col-md-2 col-sm-12'); ?>
    <?php textfield('Bairro',         'txt_bairro',       'col-md-3 col-sm-12'); ?>
    <?php textfield('Cidade',         'txt_cidade',       'col-md-4 col-sm-12'); ?>
    <?php textfield('Estado',         'txt_estado',       'col-md-2 col-sm-12'); ?>
  </div>
    </br></br></br>
                <input type="hidden" name="id_ficha" id="id_ficha" value="<?php echo $ficha->id_ficha;?>" />
                <input type="hidden" name="salvar" id="salvar" value="1" />
                <input type="hidden" id="id_modalidade" name="id_modalidade" value="<?php echo isset($ficha->id_modalidade) ? $ficha->id_modalidade>0 ? $ficha->id_modalidade : "" : ""; ?>">
</div>    
</div>

<div class="container border p-2">
    <div class="row">
        <div class="col-lg-12" id="id_modalidade_box">
            <h3>Escolha a modalidade para a inscrição:</h3>
            <div  class="form-text text-muted"><font color=red><div class="msg_error" id="erro_id_modalidade"></div></font></div>
        </div>
    </div>    
    <?php foreach($modalidade as $m){ ?>
    <input onclick="$('#id_modalidade').val(<?=$m->id_modalidade?>);" type="radio" name="id_modalidade_select" id="id_modalidade<?=$m->id_modalidade?>" <?php echo $ficha->id_modalidade == $m->id_modalidade ? "checked" : "";?>
    <font size=4><b><font color=darkblue><?=$m->sigla?></font></b> - <b><?=$m->modalidade?></b> [<font color=black><?=$m->num_vagas?> vagas</font>]</font>      </br></br>
    <?php } ?>
</div>

</form>
</div>
<div class="container p-2">
<div class="row">
    <div class='col-md-12'>
                <a class="btn btn-primary" id=salvar  onclick="$('#voltar').val(0); validar('form','ficha');"><font color=black>Salvar</font></a>&nbsp;  
                <a class="btn btn-primary" id=salvar  onclick="$('#voltar').val(1); validar('form','ficha');"><font color=black>Salvar e Voltar</font></a>&nbsp;
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=inscricaocontroller&method=dashboard&id_processo=<?php echo $processo->id_processo; ?>');"><font color=black>Voltar</font></a>
</div></div></div>

<script src="../ajax/ajax_submit.js"></script>

<script>
  
function excluir_photo(){
  if($('#id').val()>0){    
    if($('#id_saved').val()>0){
      $("#img").attr("src",'photo.php?id='+$('#id_saved').val());
      $("#status_photo").html("<b><font color=green>Foto no servidor!</font></b>");
      $('#erro_txt_picture').html('');
      $('#excluir_photo').hide();
      $('#img').show();
      $('#id').val("");
    }else{
      $("#img").attr("src",""); 
      $("#status_photo").html("<b><font color=red>Sem foto</font></b>");
      $('#erro_txt_picture').html('Foto temporária excluída.');
      $('#excluir_photo').hide();
      $('#img').hide();
      $('#id').val("");   
    }
  }else{
      $("#img").attr("src","");
      $("#status_photo").html("<b><font color=red>A excluir!</font></b>");
      $('#erro_txt_picture').html('Para salvar o formulário, escolha outra imagem.');
      $('#excluir_photo').hide();
      $('#img').hide();
      $('#id_saved').val("");  
      $('#id').val("-1");
  }
}

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

  $('#txt_telefone').mask('(00) 0000-0000');
  $('#txt_celular').mask('(00) 00000-0000');
  $('#txt_cpf').mask('000.000.000-00');
  $('#txt_rg').mask('0#');
  $('#txt_eleitor').mask('0#');
  $('#txt_cep').mask('00.000-000');
});


$(document).ready(function(){
$("#file_picture").change(function(){
    var fd = new FormData();
    var files = $('#file_picture')[0].files;
    var max =  1048576;
    if (files[0].size > max) {
      files.value = null; // Clear the field.
      $('#erro_txt_picture').html('<b><font color=red size=1>Carregue uma figua JPG ou PNG de máximo de 200kbytes.</font></b>');
      return;
   }
    // Check file selected or not
    $("#status_photo").html("<b><font color=black>Carregando foto...</font></b>");
    if(files.length > 0 ){
       fd.append('file',files[0]);
       fd.append('id',$('#id').val());
       $.ajax({
          url: 'upload.php',
          type: 'post',
          data: fd,
          contentType: false,
          processData: false,
          success: function(response){
            if(response == 'wrong_ext'){
              $("#status_photo").html("<b><font color=orange>Foto não carregada...</font></b>");
              if($("#id_saved").val()>0){
                $('#erro_txt_picture').html('<b><font color=red size=1>A foto deve ser JPEG ou PNG.</br>Mantida a foto atual.</font></b>');
              }else{
                $('#erro_txt_picture').html('<b><font color=red size=1>A foto deve ser JPEG ou PNG.</font></b>');
              }
             }else if(response == 'wrong_size'){
              $("#status_photo").html("<b><font color=orange>Foto não carregada...</font></b>");
              if($("#id_saved").val()>0){
                $('#erro_txt_picture').html('<b><font color=red size=1>A foto deve ter no máximo 1Mbyte.</font></b>');
              }else{
                $('#erro_txt_picture').html('<b><font color=red size=1>A foto deve ter no máximo 1Mbyte.</br>Mantida a foto atual.</font></b>');
              }
            }else{
                $('#erro_txt_picture').html('');
                $("#img").attr("src",'photo.php?temp=1&id='+response); 
                $("#id").val(response);
                $("#status_photo").html("<b><font color=orange>Foto carregada!</font></b>");
                $(".preview img").show(); // Display image element
                $('#excluir_photo').show();
                $('#img').show();
            }
            $("#file_picture").val('');
          },
       });
    }else{
       alert("Please select a file.");
    }
});
});
</script>
;