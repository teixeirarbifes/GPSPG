
<?php
if(isset($inscricao->dt_enviado)){
$date3 = $inscricao->dt_enviado;
$hora = $inscricao->hora_enviado;
$t3 = strtotime($date3);
$s3 = utf8_encode(strftime('%d de %B de %Y &agrave;s ',$t3)).$hora;
}
?>

<h3><font color=black>Dados da Inscrição</font></h3>
<div class="col-lg-6 bg-light border">
                      
                      </br>
                      <img width=150px align=left src="/images/enviado.png"/>
                      <p><b><font color=darkgreen>Você já enviou sua inscrição para esse processo seletivo.</font></b></p>
                      <p>Data e hora do último envio:</br><b><?=$inscricao->data_enviado." às ".$inscricao->hora_enviado?></b></p>
                      <p>Chave de protocolo: <?=$inscricao->key_inscricao?></p>
                      </br>

</div>
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
  <a class="btn btn-light stretched-link" style="cursor:pointer" onclick="go_link('/?action=download&d=<?=$doc->id_doc?>&f=<?=$ficha->id_ficha?>');"><font color=black>Baixar</font></a>
 </div>
  <div class="col-md-3 col-sm-12"> 
    <span style="width:100%"><?=$doc->txt_classe?></span>
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
    <a class="btn btn-light stretched-link" style="cursor:pointer" onclick="go_link('/?action=download&d=<?=$doc->id_doc?>&f=<?=$ficha->id_ficha?>');"><font color=black>Baixar</font></a>
 </div>
  <div class="col-md-3 col-sm-12"> 
    <span style="width:100%"><?=$doc->txt_classe?></span>
  </div>
  <div class="col-md-8 col-sm-12">
    <?=$doc->txt_filename?>

 </div>
 
</div>
 
<?php } ?>
</font>

</div></div></div>
