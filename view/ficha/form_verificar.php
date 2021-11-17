

  <?php 
if($data_table['rascunho']==1)
$pagina = "verificar";
else
$pagina = "conferir";

?>
<?php include GPATH."view".S.'ficha'.S."ficha_header.php"; ?>    
<?php if($data_table['rascunho']==1){ ?>
<h3>Verificar inscrição e enviar para avaliação</h3>
<?php }else{ ?>
<h3>Dados do último envio para análise</h3>
<?php } ?>
<hr>
<?php if($data_table['rascunho']==1){ ?>
<div>
    <B>Você está no área de verificação final da sua inscrição antes do envio.</B></br>
    <b><font color=red>Inscrições que não forem enviadas não serão consideradas no processo seletivo.</font></b>  <b><font color=red>Somente a última inscrição enviada será considerada.</font></b>
    </br></br>Ao final da sua conferëncia, no final dessa página, aceite os termos, digite sua senha e clique em ENVIAR PARA ANÁLISE.</br>
    </br>   
</div>
<?php }else{ ?>
  <div>
    <B>Os dados abaixo referem-se a um último envio de inscrição para análise.</B></br>
    <b><font color=red>Somente a última inscrição enviada será considerada.</font></b>
  </br></br>Caso identifique alguma inconsistência, ainda dentro do prazo de inscrição, uma retificação poderá ser realizada, bastando editar a sua inscrição e realizar novo envio.</br>
    </br>   
</div>
<?php } ?>
<div style="text-align:left">
<?php include "ficha_verificar.php"; ?>

</div>

</br></br>

<div class="container border p-2">
      <div class="row">
    <div class="col-md-12 col-sm-12">  
    <h4><font color=blue>Documentos pessoais e formulários apresentados (clique para download).</font></h4>
    </div>
    </div>
<?php 
if($documentos_pessoais)
foreach($documentos_pessoais as $doc){ ?>
 
  <hr>
  <div class="row">
  <div class="col-md-1 col-sm-12">
    <a class="btn btn-success stretched-link" style="cursor:pointer" onclick="go_link('/?action=download&d=<?=$doc->id_doc?>&f=<?=$ficha->id_ficha?>');");">Baixar</a>
 </div>
  <div class="col-md-3 col-sm-12"> 
    <span style="width:100%"><b><font size=4><?=$doc->txt_classe?></font></b></span>
  </div>
  <div class="col-md-8 col-sm-12">
    <?=$doc->txt_filename?>
 </div>
 
</div>
 
<?php } ?>
</font>

</div></div></div>

</br></br>
<div class="container p-2">  
<div class="row">
<div class="col-md-8 col-sm-12 border"> 
     <h3>Matriz de Pontuação</h3>
        <div class="row">
            <div class='col-md-12'>
                <?php include GPATH."view".S.'documentos'.S."matriz_curriculo.php"; ?>            
        </div>
    </div>
   </div>
</div>
</div>



<div class="container border p-2">
      <div class="row">
    <div class="col-md-12 col-sm-12">  
    <h4><font color=blue>Documentos de currículo apresentados (clique para download).</font></h4>
    </div>
    </div>
<?php 
if($documentos_curriculo)
foreach($documentos_curriculo as $doc){ ?>
 
  <hr>
  <div class="row">
  <div class="col-md-1 col-sm-12">
    <a class="btn btn-success stretched-link" style="cursor:pointer" onclick="go_link('/?action=download&d=<?=$doc->id_doc?>&f=<?=$ficha->id_ficha?>');">Baixar</a>
 </div>
  <div class="col-md-3 col-sm-12"> 
    <span style="width:100%"><b><font size=4><?=$doc->txt_classe?></font></b></span>
  </div>
  <div class="col-md-8 col-sm-12">
    <?=$doc->txt_filename?>

 </div>
 
</div>
 
<?php } ?>
</font>

</div></div></div>


</br></br>
<?php if($data_table['rascunho']==1){ ?>
<form id=form class="form-horizontal" action="?controller=inscricaocontroller&method=entregar&id_processo=<?php echo $inscricao->id_processo; ?>" method="post" >

<div class='container border'>
</br>
<div class=row>
    <div class=row>
      <div class="col-md-12 col-sm-12">  
        <div id=display_erro class="alert alert-danger display-error" style="display: none">
          <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
        </div>
      </div>
    </div>
  </div>
<h3>Declaração</h3>

<b><font color=darkblue size=2px>Marque a declaração se estiver de acordo.</font></b>
<div class="container" id="concordo">
  <input type="checkbox" name="concordo">
  <label for="concordo"><font color=black>Eu, <?php echo $usuario['txt_nome']; ?>, CPF nº <?php echo $usuario['txt_cpf']; ?>, declaro, sob as penas da Lei, que são verdadeiras e completas as informações prestadas neste sistema eletrônico para essa inscrição. Entendo que somente será considerado o último protocolo de envio de inscrição conforme edital. Entendo que os dados da inscrição devem estar em consonância com as normas do edital do processo seletivo.</font>
</label>
</div>
</br><div>
<b><font color=darkblue size=2px>Digite a sua senha para autenticação</font></b>
</div>
<div class="row">
    <div class="col-sm-12 col-md-4">      
      <input type="password" id="txt_senha" class="form-control" aria-describedby="erro_txt_senha" name="txt_senha"/>
      <div id="erro_txt_senha" class="form-text text-muted"></div>
  </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-4"> 
    <a class="btn btn-primary" id=salvar  onclick="validar('form','entregar',null,false);"><font color=black>Enviar e protocolar inscrição</font></a>&nbsp;  
</div>
</form>
<?php }else{ ?>
  <div class="container border p-2">
      <div class="row">
    <div class="col-md-12 col-sm-12">
  <h4>Os dados informados acima já constam como inscrição enviada.</h4>
</div>
</div>
</div>
<?php } ?>
</br>
</br>
</div>
</div>
</br></br></br></br></br></br></br>
</div>
</div>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
</script>
