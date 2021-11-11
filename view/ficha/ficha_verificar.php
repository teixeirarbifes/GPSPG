<?php ob_start(); ?>
  <div class="{class}">      
    <label for="{field}">{label}</label>
    <input type="text" id="{field}" class="maskField form-control" aria-describedby="erro_{field}" name="{field}" value="{value}" disabled {adicional}/>
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

<div class="form-group">
 <div class="container border p-2">
  <div class="row">
  <div class="col-md-2 col-sm-12">    
        <div>
        <b>Sua foto:</b><br/>
        <img style="display:<?php echo isset($ficha->txt_photo) ? "block" : "none" ?>" src="<?php echo isset($ficha->txt_photo) ? "/photo.php?uq=".uniqid()."&id=".$ficha->id_ficha : "" ?>" id="img" width="162" height="180">
        </div>
        
        
   </div>

   <div class="col-md-10 col-sm-12">
    <div class="row">
      <div class='col-md-12'><h4><b><font color=darkblue>Confira seus dados iniciais:</font></b></h4></div>
      <?php textfield('Nome completo',  '',         'col-lg-6 col-md-6 col-sm-12',  false, 'disabled',"<b><font color=green size=1px>O nome completo pode ser alterado na página do perfil.</font></b>",UsuariosController::get_usuario()['txt_nome'] ); ?>
      <?php textfield('E-mail',         '',        'col-lg-6 col-md-6 col-sm-12', false, 'disabled',"<b><font color=green size=1px>O e-mail pode ser alterado na página do perfil.</font></b>",UsuariosController::get_usuario()['txt_email']); ?>
      <?php textfield('Nome da mãe',    'txt_nome_mae',     'col-lg-6 col-md-6 col-sm-12'); ?>
      <?php textfield('Nome do pai',    'txt_nome_pai',     'col-lg-6 col-md-6 col-sm-12'); ?>
      <?php textfield('Telefone',       'txt_telefone',     'col-lg-3 col-md-6 col-sm-12',false,"mask='(999) 9999–9999'"); ?>
      <?php textfield('Celular',        'txt_celular',      'col-lg-3 col-md-6 col-sm-12',false,"mask='(999) 99999–9999'"); ?>
      <?php textfield('Estado civil',   'txt_civil',        'col-md-3 col-sm-5'); ?>
      <?php textfield('Sexo',           'txt_sexo',         'col-md-3 col-sm-5'); ?>
    </div>
   </div>
  </div>
  
</div>
</BR>
<div class="container border p-2">
  <div class="row">
      <div class='col-md-12'><h4><b><font color=darkblue>Confira a sua naturalidade:</font></b></h4></div>
      <?php textfield('País',           'txt_natural_pais', 'col-md-3 col-sm-12'); ?>
      <?php textfield('Estado',         'txt_natural_estado','col-md-2 col-sm-12'); ?>
      <?php textfield('Cidade',         'txt_natural_cidade','col-md-4 col-sm-12'); ?>
  </div>
</div>
<br/>
<div class="container border p-2">
  <div class="row">
    <div class='col-md-12'><h4><b><font color=darkblue>Configura os documentos:</font></b></h4></div>
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
    <input disabled type="date" id="txt_rg_expedicao" class="maskField form-control" aria-describedby="erro_txt_rg_expedicao" name="txt_rg_expedicao" value="<?php echo $ficha->txt_rg_expedicao; ?>" {adicional}/>
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
    <input disabled type="date" id=txt_eleitor_emissao" class="maskField form-control" aria-describedby="erro_txt_eleitor_emissao" name="txt_eleitor_emissao" value="<?php echo $ficha->txt_eleitor_emissao; ?>" {adicional}/>
    <div  class="form-text text-muted"><font color=red><div class="msg_error" id="erro_txt_eleitor_emissao"></div></font></div>
    </div>
    </div>
</div>
</br>
<div class="container border p-2">
<div class="row">
    <div class='col-md-12'><h4><b><font color=darkblue>Confira o seu endereço</font></b></h4></div>
    <?php textfield('Logadouro',      'txt_logadouro',    'col-md-8 col-sm-12'); ?>
    <?php textfield('Número',         'txt_numero',       'col-md-1 col-sm-12'); ?>
    <?php textfield('Complemento',    'txt_complemento',  'col-md-4 col-sm-12'); ?>
    <?php textfield('CEP',            'txt_cep',          'col-md-2 col-sm-12'); ?>
    <?php textfield('Bairro',         'txt_bairro',       'col-md-3 col-sm-12'); ?>
    <?php textfield('Cidade',         'txt_cidade',       'col-md-4 col-sm-12'); ?>
    <?php textfield('Estado',         'txt_estado',       'col-md-2 col-sm-12'); ?>
  </div>
</div>    
</div>

<div class="container border p-2">
<div class="row">
<div class='col-md-12'>
<h4>Configura sua modalidade de inscrição</h4></br>
A modalidade de inscrição selecionada foi <b><?=$ficha->sigla?> - <?=$ficha->modalidade?></b>, <?=$ficha->desc_modalidade?>
</div>
</div>    
</div>

<script src="../ajax/ajax_submit.js"></script>
