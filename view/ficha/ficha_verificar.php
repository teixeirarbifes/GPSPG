<?php ob_start(); ?>
  <div class="{class} border p-2">      
    <font color=darkblue>{label}:</font></br>
    <div class="row p-2"><b>{value}</b></div>   
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
 <div class="container p-2">
  <div class="row">
  <div class="col-md-2 col-sm-12">    
        <div>
        <b>Sua foto:</b><br/>
        <img style="display:<?php echo isset($ficha->txt_photo) ? "block" : "none" ?>" src="<?php echo isset($ficha->txt_photo) ? "/photo.php?uq=".uniqid()."&id=".$ficha->id_ficha : "" ?>" id="img" width="162" height="180">
        </div>
        
        
   </div>

   <div class="col-md-10 col-sm-12 p-2">
    <div class="row">
      <div class='col-md-12'><b><font color=darkblue>Dados gerais:</font></b></div>
      <?php textfield('Nome completo',  'txt_nome',         'col-lg-6 col-md-6 col-sm-12',  false, 'disabled',"<b><font color=green size=1px>O nome completo pode ser alterado na página do perfil.</font></b>" ); ?>
      <?php textfield('E-mail',         '',        'col-lg-6 col-md-6 col-sm-12', false, 'disabled',"<b><font color=green size=1px>O e-mail pode ser alterado na página do perfil.</font></b>",UsuariosController::get_usuario()['txt_email']); ?>
      <?php textfield('Data de nascimento',    'txt_nascimento',     'col-lg-3 col-md-3 col-sm-12'); ?>
      <?php textfield('Nome da mãe',    'txt_nome_mae',     'col-lg-5 col-md-5 col-sm-12'); ?>
      <?php textfield('Nome do pai',    'txt_nome_pai',     'col-lg-4 col-md-4 col-sm-12'); ?>
      <?php textfield('Telefone',       'txt_telefone',     'col-lg-6 col-md-6 col-sm-12',false,"mask='(999) 9999–9999'"); ?>
      <?php textfield('Celular',        'txt_celular',      'col-lg-6 col-md-6 col-sm-12',false,"mask='(999) 99999–9999'"); ?>
      <?php textfield('Estado civil',   'txt_civil',        'col-md-3 col-sm-12'); ?>
      <?php textfield('Sexo',           'txt_sexo',         'col-md-4 col-sm-12'); ?>
      <?php textfield('Escolaridade',           'txt_escolaridade',         'col-md-5 col-sm-12'); ?>
    </div>
   </div>
  </div>
  
</div>
</BR>
<div class="container p-2">
  <div class="row">
      <div class='col-md-12'><b><font color=darkblue>Naturalidade:</font></b></div>
      <?php textfield('País',           'txt_natural_pais', 'col-md-3 col-sm-12'); ?>
      <?php textfield('Estado',         'txt_natural_estado','col-md-2 col-sm-12'); ?>
      <?php textfield('Cidade',         'txt_natural_cidade','col-md-4 col-sm-12'); ?>
  </div>
</div>
<br/>
<div class="container p-2">
  <div class="row">
    <!--div class='col-md-12'><h4><b><font color=darkblue>Configura os documentos:</font></b></h4></div-->
    <?php textfield('CPF',  '',         'col-lg-2 col-md-3 col-sm-12',  false, 'disabled',"<b><font color=green size=1px>O CPF foi informado no cadastro.</font></b>",UsuariosController::get_usuario()['txt_cpf'] ); ?>
  </div>
  <hr/>
  <div class="row">
    <div class='col-md-12'><b><font color=darkblue>Registro Geral (RG)</font></b></div>
    <?php textfield('RG',             'txt_rg',           'col-md-4 col-sm-12'); ?>
    <?php textfield('Orgão Expedidor','txt_rg_orgao',     'col-md-2 col-sm-12'); ?>
    <?php textfield('UF',             'txt_rg_uf',        'col-md-2 col-sm-12'); ?>
    <?php textfield('Data de expedição',         'txt_rg_expedicao','col-md-2 col-sm-12'); ?>
  </div>
  <hr/>
  <div class="row">
    <div class='col-md-12'><b><font color=darkblue>Título de Eleitor</font></b></div>
  
    <?php textfield('Título de Eleitor','txt_eleitor',    'col-md-4 col-sm-12'); ?>
    <?php textfield('Zona',           'txt_eleitor_zona', 'col-md-1 col-sm-12'); ?>
    <?php textfield('Seção',          'txt_eleitor_secao','col-md-1 col-sm-12'); ?>
    <?php textfield('Estado',         'txt_eleitor_estado','col-md-2 col-sm-12'); ?>
    <?php textfield('Data de emissão',         'txt_eleitor_emissao','col-md-2 col-sm-12'); ?>
    </div>
</div>
</br>
<div class="container p-2">
<div class="row">
    <div class='col-md-12'><b><font color=darkblue>Endereço residencial</font></b></div>
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

<div class="container p-2 border">
<div class="row">
<div class='col-md-12'>
<!--h4>Confira sua modalidade de inscrição</h4></br-->
<?php if($ficha->bl_condicao_especial){ ?>
  <font size=3>Foi solicitada uma condição especial para a realização da prova. Tenha atenção às exigências do edital para o atendimento desta solicitação.</font>
<?php }else{ ?>
  <font size=3>Não foi solicitada condição especial para a realização da prova.</font>
<?php } ?>
</div>
</div>    
</div>


<div class="container p-2 border">
<div class="row">
<div class='col-md-12'>
<!--h4>Confira sua modalidade de inscrição</h4></br-->
<font size=3>A modalidade de inscrição selecionada foi <b><?=$ficha->sigla?> - <?=$ficha->modalidade?></b>, <?=$ficha->desc_modalidade?></font>
</div>
</div>    
</div>

<hr>

<script src="../ajax/ajax_submit.js"></script>
