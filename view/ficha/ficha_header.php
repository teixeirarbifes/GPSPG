<h3><b><font color="darkblue"><?php echo $processo->txt_processo; ?></font></b></h3>
<?php 
if(!isset($habilitar_inscricao)) $habilitar_inscricao = false;
 if(isset($inscricao->id_inscricao) && $inscricao->id_inscricao>0){
  $habilitar_inscricao = true;
 }
 $aberto = ProcessosController::aberto($processo->id_processo);
?>
<?php 
if(isset($inscricao->id_inscricao) && $inscricao->id_inscricao>0){?>
</br>
<a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="ficha" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=FichaController&method=editar&id_ficha=<?php echo $inscricao->id_ficha_rascunho; ?>');"><font size=4 color=black><?=$pagina=="ficha"?'<b><font color=blue>':''?>1. Editar ficha</br>de inscrição</font><?=$pagina=="ficha"?'</font></b>':''?></a>&nbsp;  
<a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="pessoais" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_ficha&id_ficha=<?php echo $inscricao->id_ficha_rascunho; ?>');"><font size=4 color=black><?=$pagina=="pessoal"?'<b><font color=blue>':''?>2. Pessoais</br>e anexos</font><?=$pagina=="pessoal"?'</font></b>':''?></a>&nbsp;  
<a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="curriculo" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_curriculo&id_ficha=<?php echo $ficha->id_ficha; ?>');"><font size=4 color=black><?=$pagina=="curriculo"?'<b><font color=blue>':''?>3. Currículo</br>e anexos</font><?=$pagina=="curriculo"?'</font></b>':''?></a>&nbsp;  
<!--span class="d-flex d-sm-flex d-md-flex d-lg-none"></br></span-->
<?php } ?>
<?php 
    if(!isset($inscricao->id_inscricao) || $inscricao->id_inscricao==0){ ?>
            <?php
            if($aberto==2){ ?>
              <?php if($usuario!=null){                 
                if($iniciar == 1) echo "<script>confirma_inscricao();</script>";
                ?>
                <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-success btn-sm" onclick="confirma_inscricao();"><font size=2 color=black>Iniciar inscrição</font></a>
              <?php }else{ ?>
                <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-success disabled btn-sm"><font size=2 color=black>Iniciar inscrição!</font></a>
              <?php } ?>
            <?php
            }else if($aberto==1){ ?>
              <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-danger disabled btn-sm" ><font size=2 color=black>Inscrições</br>encerradas!</font></a>
            <?php }else{ ?>
              <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-warning disabled btn-sm" ><font size=2 color=black>Inscrições</br>em breve!</font></a>
            <?php } ?>
            </br></br>     
        <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="informações" ? 'secondary"' : 'light'?>" id=salvar  onclick="go_link('/?controller=ProcessosController&method=visualizar_candidato&id_processo=<?php echo $processo->id_processo; ?>');"><font size=2 color=black><?=$pagina=="informação"?'<b><font color=blue>':''?>Informações sobre o processo seletivo<?=$pagina=="informação"?'</font></b>':''?></font></a>&nbsp;

      <?php }else{ ?>    
      <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer"  class="btn btn-sm btn-<?=$pagina=="verificar" ? 'secondary"' : 'light'?>" id=salvar  onclick="go_link('/?controller=InscricaoController&method=verificar&id_processo=<?php echo $processo->id_processo; ?>');"><font size=4 color=black><?=$pagina=="verificar"?'<b><font color=blue>':''?>4. Verificar e enviar</br>para análise<?=$pagina=="verificar"?'</font></b>':''?></font></a>&nbsp;  
     <?php if(isset($inscricao->id_inscricao) && $inscricao->id_ficha_enviada > 0){ ?>
        </br></br>     
        <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="informações" ? 'secondary"' : 'light'?>" id=salvar  onclick="go_link('/?controller=ProcessosController&method=visualizar_candidato&id_processo=<?php echo $processo->id_processo; ?>');"><font size=2 color=black><?=$pagina=="informação"?'<b><font color=blue>':''?>Informações sobre o processo seletivo<?=$pagina=="informação"?'</font></b>':''?></font></a>&nbsp;
        <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="conferir" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=InscricaoController&method=ver_entregue&id_processo=<?php echo $processo->id_processo; ?>');"><font size=2 color=black><?=$pagina=="conferir"?'<b><font color=blue>':''?>Conferir envio realizado.<?=$pagina=="verificar"?'</font></b>':''?></font></a>&nbsp;          
        <?php } ?>      
      <?php } ?>

<hr>