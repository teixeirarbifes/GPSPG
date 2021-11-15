<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['Processo Seletivo','txt_processo'],['Status','status']];
$id = 'id_processo';
$exclusao = 1;
$visualizar = 1;
$funcao_excluir = "excluir_grade_processos";
?>

<?php include GPATH."utils".S."modal.php"; ?>
<h3>Processos Seletivos Públicos</h3>
</br>
<?php if(UsuariosController::is_logged()){?>
<b>Para iniciar a sua inscrição, escolha um processo seletivo e clique no botão <font color=darkred>Iniciar a minha inscrição.</font></br>
Obtenha <font color=darkblue>mais informações</font> sobre um processo seletivo clicando no botão correspondente.</b>
</br></br>
Caso já tenha iniciado sua inscrição, clique no botão <font color=darkblue>mais informações</font>.
<?php }else{ ?>
<b>Para iniciar a sua inscrição, primeiramente, registre-se! Caso já tenha registro, acesse o sistema.</br>
Obtenha <font color=darkblue>mais informações</font> sobre um processo seletivo clicando no botão correspondente.</b>
<?php } ?>
<hr>
<div class="container">

<?php 
$pagination = "";
include GPATH."utils".S."pagination.php";
?>
<?php foreach($data_table as $processo){ ?>
 <div class="card text-center">
  <div class="card-header" style="text-align:left">
  <h4><b><?php echo $processo->txt_processo; ?></b></h4>
  </div>
  <div class="card-body">
  <div class="col-sm-12 col-md-12">
  <p class="card-text" style="text-align:left">
    <?php

    $date1 = $processo->dt_inicio_inscricao;
    $date2 = $processo->dt_fim_inscricao;

    $t1 = strtotime($date1);
    $t2 = strtotime($date2);

    $s1 = utf8_encode(strftime('%A, %d de %B de %Y &agrave;s %H:%M:%S',$t1));
    $s2 = utf8_encode(strftime('%A, %d de %B de %Y &agrave;s %H:%M:%S',$t2));
    ?>
    <font size=4><div style="text-align:left;" ><i class="icon-calendar" ></i>PERÍODO DE INSCRIÇÃO</br></br>
            Inicio: &nbsp&nbsp&nbsp&nbsp <b><font color=darkgreen><?=$s1?></font></b></br></br>Término: <b><font color=red><?=$s2?></font></b></div></div>
  <div class="col-md-12">
              <?php 
              $aberto = ProcessosController::aberto($processo->id_processo);
                if($aberto==2){ ?>
              </br><h4 style="text-align:left"><span class="glyphicon glyphicon-plus" style="font-size:16px;"></span><font color=darkgreen>Aceitando envio de inscrições...</font></br></br></h4>
              <?php }else if($aberto==0){ ?>
                </br><h4 style="text-align:left"><span class="glyphicon glyphicon-plus" style="font-size:16px;"></span><font color=darkblue>O período de inscrição iniciará em breve.</font></br></br></h4>
              <?php }else{ ?>
              </br><h4 style="text-align:left"><span class="glyphicon glyphicon-lock" style="font-size:16px;"></span>&nbsp <b><font color=red>Envio de inscrições encerrado!</font></br></br></h4>
              <?php } ?>

                            <h6 style="text-align:left">
                <?php if($processo->cronograma==null){ ?>
                  Última ocorrência: <font color=red>-----</font>
                <?php }else{ ?>                  
                  Última ocorrência: <b><?=$processo->cronograma->txt_descricao?> em <?=utf8_encode(strftime('%d de %B de %Y',strtotime($processo->cronograma->dt_inicio)))?></b>
                <?php } ?>
                </br>
                <?php if(!UsuariosController::is_logged()){
                    if($aberto==2){ ?>
                      </br><font color=red>O período de inscrições está aberto. Para se inscrever acesse o sistema (login) com o seu usuário.</font>
                    <?php }else if($aberto==1){ ?>
                      </br><font color=red>O período de inscrições está encerrado. Tenha seu cadastro no sistema para outros processos seletivos.</font>
                    <?php }else{ ?>
                    </br><font color=blue>O período de inscrições começará em breve. Faça seu registro de usuário. Caso já tenha, aguarde o início das inscrições.</font>
                    <?php } ?>
                <?php }else if($processo->inscrito){ ?>
                  <?php if($aberto==2){ ?>
                  <?php }else if($aberto==1){ ?>
                      </br><font color=red>O período de inscrições está encerrado. Consulte os dados da sua inscrição.</font>
                    <?php }else{ ?>
                      </br><font color=blue>O período de inscrições comecará em breve. Aguarde o início para iniciar sua inscrição.</font>
                    <?php } ?>    
                <?php }else{ ?>
                  <?php if($aberto==2){ ?>
                    </br><font color=darkgreen>O período de inscrições está aberto. Você ainda não se inscreveu. Increva-se!</font>
                  <?php }else if($aberto==1){ ?>
                      </br><font color=red>O período de inscrições está encerrado. Não é possível mais iniciar sua inscrição.</font>
                    <?php }else{ ?>
                      </br><font color=blue>O período de inscrições comecará em breve. Aguarde o início para iniciar sua inscrição.</font>
                    <?php } ?>  
                  <?php } ?>                
                  </h6>
                 

              </div>

        </p>
          <p class="card-text" style="text-align:left">
        </br>
                  <?php 
                  
                  $pag = $params['pag'];
                  $limit = $params['limit'];
                  if(!$processo->inscrito){ ?>                        
                        <?php
                        if($aberto==2){ ?>
                          <?php if(UsuariosController::is_logged()){ ?>
                            <a class="btn btn-success" onclick="go_link('?controller=processoscontroller&method=visualizar_candidato&iniciar=1&id_processo=<?=$processo->id_processo?>&pag=<?php echo $pag ?>&num=<?php echo $limit; ?>');"><font color=black>Iniciar a minha inscrição!</font></a>
                          <?php }else{ ?>
                            <a class="btn btn-success disabled"><font color=black>Iniciar a minha inscrição</font></a>
                          <?php } ?>
                        <?php
                        }else if($aberto==1){ ?>
                          <a class="btn btn-danger disabled" ><font color=black>Inscrições encerradas!</font></a>
                        <?php }else{ ?>
                          <a class="btn btn-warning disabled" ><font color=black>Inscrições em breve!</font></a>
                        <?php } ?>
                <?php }else{ ?>
                    <?php if($processo->enviado){ ?>
                      <a class="btn btn-success " onclick="go_link('?controller=inscricaocontroller&method=ver_entregue&id_processo=<?=$processo->id_processo?>');"><font color=black>Inscrição enviada</font></a>                    
                      <?php }else{ ?>
                        <?php if($aberto==2){ ?>
                          <a class="btn btn-warning " onclick="go_link('?controller=inscricaocontroller&method=verificar&id_processo=<?=$processo->id_processo?>');"><font color=black>Em edição</font></a>               
                        <?php }else{ ?>
                          <a class="btn btn-danger " onclick="go_link('?controller=inscricaocontroller&method=verificar&id_processo=<?=$processo->id_processo?>');"><font color=black>Inscrição NÁO enviada</font></a>               
                        <?php } ?>
                      <?php } ?>
                <?php } ?>
                <a class="btn btn-primary" onclick="go_link('?controller=processoscontroller&method=visualizar_candidato&id_processo=<?php echo $processo->id_processo; ?>&pag=<?php echo $pag ?>&num=<?php echo $limit; ?>');"><font color=black>Mais informações...</font></a> 
            </p>

    </div>
  </div>
  </br>
<?php } ?>
</div>
</br></br>
<?php
//include GPATH."utils".S."table.php"; 
#echo $paginacao;
?>


</div>