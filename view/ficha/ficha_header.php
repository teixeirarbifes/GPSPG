
<?php
  if(UsuariosController::is_logged()){
    $h_usuario = UsuariosController::get_usuario();
    $h_aberto = ProcessosController::aberto($processo->id_processo);
    $p_inscricao = Inscricao::get_id_by_processo($processo->id_processo,$h_usuario['id_user']);  
  }else{
    $usuario = null;
  }
  echo $h_aberto;
?>

<h3><b><font color="darkblue"><?php echo $processo->txt_processo; ?></font></b></h3>
<?php if(UsuariosController::is_logged() && $h_usuario!=null){ ?>
  <div class="col-lg-6">
        <?php if(!isset($inscricao->id_inscricao) || !($inscricao->id_ficha_enviada > 0)){ ?>
            <img style="float:left" src="images/warning.png" width="20px"/> <b><font size=2 color=red> Sua inscrição para esse processo seletivo ainda não foi iniciada.</font></b>
      <? }else if($inscricao->id_ficha_enviada > 0){ ?>
            <?php if($h_aberto == 2){ ?>
              </br><img style="float:left" src="images/warning.png" width="20px"/> <b><font size=2 color=red> Sua inscrição ainda não foi enviada! Importante enviar para análise a sua inscrição dentro do prazo.</font></b>
            <?php }else if($h_aberto == 1){ ?>
              </br><img style="float:left" src="images/warning.png" width="20px"/> <b><font size=2 color=red> Sua inscrição foi enviada dentro do prazo e não poderá ser enviada para análise.</font></b>
            <?php } ?>
      <?php }else{ ?>
         </br><b><font size=2 color=darkgreen> Sua inscrição já foi enviada para análise.</br>Sempre o último que será considerado para análise.</font></b>                      
      <?php } ?>
  </div>        

<?php } ?>
<hr>
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
<a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="ficha" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=FichaController&method=editar&id_ficha=<?php echo $inscricao->id_ficha_rascunho; ?>');"><font size=3 color=black><?=$pagina=="ficha"?'<b><font color=blue>':''?>1. Editar ficha</br>de inscrição</font><?=$pagina=="ficha"?'</font></b>':''?></a>&nbsp;  
<a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="pessoais" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_ficha&id_ficha=<?php echo $inscricao->id_ficha_rascunho; ?>');"><font size=3 color=black><?=$pagina=="pessoal"?'<b><font color=blue>':''?>2. Doc. Pessoais</br>e formulários</font><?=$pagina=="pessoal"?'</font></b>':''?></a>&nbsp;  
<a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="curriculo" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_curriculo&id_ficha=<?php echo $ficha->id_ficha; ?>');"><font size=3 color=black><?=$pagina=="curriculo"?'<b><font color=blue>':''?>3. Currículo</br>e anexos</font><?=$pagina=="curriculo"?'</font></b>':''?></a>&nbsp;  
<!--span class="d-flex d-sm-flex d-md-flex d-lg-none"></br></span-->
</br></br>
<?php } ?>
<?php 
    if(!isset($inscricao->id_inscricao) || $inscricao->id_inscricao==0){ ?>
            <?php
            if($aberto==2){ ?>
              <?php if($usuario!=null){                 
                if($iniciar == 1) echo "<script>confirma_inscricao();</script>";
                ?>
                <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 6px;padding-right: 6px;padding-bottom: 6px;cursor:pointer" class="btn btn-success btn-sm" onclick="confirma_inscricao();"><font size=4 color=black>Iniciar a minha inscrição</font></a>
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
        <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="informações" ? 'secondary"' : 'light'?>" id=salvar  onclick="go_link('/?controller=ProcessosController&method=visualizar_candidato&id_processo=<?php echo $processo->id_processo; ?>');"><font size=2 color=black><?=$pagina=="informação"?'<b><font color=blue>':''?>Informações do</br>processo seletivo<?=$pagina=="informação"?'</font></b>':''?></font></a>&nbsp;
      <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer"  class="btn btn-sm btn-<?=$pagina=="verificar" ? 'secondary"' : 'light'?>" id=salvar  onclick="go_link('/?controller=InscricaoController&method=verificar&id_processo=<?php echo $processo->id_processo; ?>');"><font size=2 color=red><?=$pagina=="verificar"?'<b><font color=red>':''?>4. Verificar e enviar</br>para análise<?=$pagina=="verificar"?'</font></b>':''?></font></a>&nbsp;  
     <?php if(isset($inscricao->id_inscricao) && $inscricao->id_ficha_enviada > 0){ ?>
        <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-sm btn-<?=$pagina=="conferir" ? 'secondary"' : 'light'?> <?=$habilitar_inscricao ? "" : "disabled"?>" id=salvar  onclick="go_link('/?controller=InscricaoController&method=ver_entregue&id_processo=<?php echo $processo->id_processo; ?>');"><font size=2 color=black><?=$pagina=="conferir"?'<b><font color=blue>':''?>Conferir</br>último envio.<?=$pagina=="verificar"?'</font></b>':''?></font></a>&nbsp;          
        <?php } ?>      
      <?php } ?>

<hr>
