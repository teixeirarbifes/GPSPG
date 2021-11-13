<script>
function callback_inscricao(evento){
  if(evento==2){
    go_link('?controller=inscricaocontroller&method=inscrever&id_processo=<?php echo $processo->id_processo; ?>');
  }
  close_modal();
};
function confirma_inscricao(){
  display_modal("INCRIÇÃO DE PROCESSO SELETIVO","Você está se inscrevendo no processo seletivo '<?php echo $processo->txt_processo; ?>'. <font color=red>Importante preencher todo o formulário, anexar todos documentos exigidos e enviar a sua inscrição ao final.</font></br></br>A sua inscrição no processo seletivo somente é garantida após cumprir todos preenchimentos e envios exigidos.</br></br>Continuar?",callback_inscricao,"Não. Verei isso depois!","Sim, iniciar inscrição!");
};
</script>

<h3><b><font color="darkblue"><?php echo $processo->txt_processo; ?></font></b></h3>
</br>
<h3>Informações sobre o processo seletivo</h3>
<hr>
<div class=row>
<div id=display_erro class="alert alert-danger display-error col-10 col-md-5 col-lg-5" style="display: none">
 <b>Não foi possível enviar o formulário pelos seguintes motivos:</br></b><ul><span id="erro" name="erro"></span></ul>
 </div>
</div>
 <div class="form-group">
<?php


$date1 = $processo->dt_inicio_inscricao;
$date2 = $processo->dt_fim_inscricao;

if(isset($inscricao->dt_enviado)){
$date3 = $inscricao->dt_enviado;
$hora = $inscricao->hora_enviado;
$t3 = strtotime($date3);
$s3 = utf8_encode(strftime('%d de %B de %Y &agrave;s ',$t3)).$hora;
}

$t1 = strtotime($date1);
$t2 = strtotime($date2);


$s1 = utf8_encode(strftime('%A, %d de %B de %Y &agrave;s %H:%M:%S',$t1));
$s2 = utf8_encode(strftime('%A, %d de %B de %Y &agrave;s %H:%M:%S',$t2));
$aberto = ProcessosController::aberto($processo->id_processo);
?>
<a class="btn btn-secondary" onclick="go_link('?controller=processoscontroller&method=listar_candidato&pag=<?php echo isset($data_table['pag']) ? $data_table['pag'] : 1; ?>&num=<?php echo isset($data_table['num']) ? $data_table['num'] : 5; ?>');"><font color=black><< Voltar à lista de processos</font></a>          
<?php 
    if($data_table['id_inscricao']==0){ ?>
              
              <?php
              if($aberto==2){ ?>
                <?php if($usuario!=null){ ?>
                  <a class="btn btn-success" onclick="confirma_inscricao();"><font color=black>Iniciar a minha inscrição!</font></a>
                <?php }else{ ?>
                  <a class="btn btn-success disabled"><font color=black>Iniciar a minha inscrição!</font></a>
                <?php } ?>
              <?php
              }else if($aberto==1){ ?>
                <a class="btn btn-danger disabled" ><font color=black>Inscrições encerradas!</font></a>
              <?php }else{ ?>
                <a class="btn btn-warning disabled" ><font color=black>Inscrições em breve!</font></a>
              <?php } ?>
    <?php }else{ ?>
    <a class="btn btn-warning " onclick="go_link('?controller=inscricaocontroller&method=dashboard&id_processo=<?=$processo->id_processo?>');"><font color=black>Ir para a inscrição</font></a>
    <?php } ?>
    </br>
    <?php if($usuario==null){
        if($aberto==2){ ?>
          </br><font color=red>O período de inscrições está aberto. Para se inscrever acesse o sistema (login) com o seu usuário.</font>
        <?php }else if($aberto==1){ ?>
          </br><font color=red>O período de inscrições está encerrado. Tenha seu cadastro no sistema para outros processos seletivos.</font>
        <?php }else{ ?>
        </br><font color=blue>O período de inscrições começará em breve. Faça seu registro de usuário. Caso já tenha, aguarde o início das inscrições.</font>
        <?php } ?>
    <?php }else if($data_table['id_inscricao']!=0){ ?>
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
        <?php } ?>  <?php } ?>
<hr>              
<div class=row>
  <div class="col-sm-12 col-md-12">  
  <div class="card">
    <!--div class="card-header">
      <h3><b><font color=darkblue><?php  echo isset($processo->txt_processo) ? $processo->txt_processo : null; ?></font></b></h3>
    </div-->
    <div class="card-body">
      <div class="col-lg-6">
      <font size=4><div style="text-align:left;" ><i class="icon-calendar" ></i>PERÍODO DE INSCRIÇÃO</br></br>
            Inicio: &nbsp&nbsp&nbsp&nbsp <b><font color=darkgreen><?=$s1?></font></b></br></br>Término: <b><font color=red><?=$s2?></font></b></div></div>
  <div class="col-md-12">
              <?php 
              $aberto = ProcessosController::aberto($processo->id_processo);
              if($aberto == 2){ ?>
              </br><h4 style="text-align:left"><span class="glyphicon glyphicon-plus" style="font-size:16px;"></span><font color=darkgreen>Aceitando envio de inscrições...</font></br></br></h4>
              <?php }else if($aberto == 0){ ?>
                </br><h4 style="text-align:left"><span class="glyphicon glyphicon-plus" style="font-size:16px;"></span><font color=darkblue>O período de inscrição iniciará em breve.</font></br></br></h4>
              <?php }else{ ?>
              </br><h4 style="text-align:left"><span class="glyphicon glyphicon-lock" style="font-size:16px;"></span>&nbsp <b><font color=red>Envio de inscrições encerrado.</font></br></br></h4>
              <?php } ?>
                <h6 style="text-align:left">
                <?php if($evento==null){ ?>
                  Última ocorrência: <font color=red>-----</font>
                <?php }else{ ?>                  
                  Última ocorrência: <b><?=$evento->txt_descricao?> em <?=utf8_encode(strftime('%d de %B de %Y &agrave;s %H:%M:%S',strtotime($evento->dt_inicio)))?></b>
                <?php } ?>
                </h6>
              </div>       

        <?php if($usuario!=null){ ?>
        <div class="col-lg-6 bg-light border">
                      <?php if($data_table['id_inscricao']==0){ ?>
                          Não há inscrição do seu usuário para esse processo seletivo.
                      <?php }else if($data_table['id_ficha_enviada']==0){ ?>
                          $
                          <?php $aberto = ProcessosController::aberto($processo->id_processo);
                          if($aberto == 1){ ?>
                            </br><img style="float:left" src="images/warning.png" width="40px"/> <b><font color=red> Você iniciou a sua inscrição mas não enviou. Faça o envio da sua inscrição ainda dentro do prazo.</font></b>
                          <?php }else if($aberto == 2){ ?>
                            </br><img style="float:left" src="images/warning.png" width="40px"/> <b><font color=red> Você iniciou a sua inscrição mas não enviou. Considerando que o prazo de inscrição expirou, sua inscrição não poderá mais ser aceita.</font></b>
                          <?php } ?>

                      <?php }else{ ?>
                      </br></br>
                      <img width=150px align=left src="/images/enviado.png"/>
                      <p><b><font color=darkgreen>Você já enviou sua inscrição para esse processo seletivo.</font></b></p>
                      <p>Data e hora do último envio: <?=$s3?></p>
                      <p>Chave de protocolo: <?=$inscricao->key_inscricao?></p>
                      <p>Aguarde as próximas etapas do processo seletivo.</p>
                      <?php } ?>
                      </br></br>

        </div>
        <?php } ?>

    </div>
  </div>
  </div>  
</div>
<div class=row>
<div class="col-12">
  <div class="card">
    <!--div class="card-header">
      Descr
    </div-->
    <div class="card-body">
      <h5 class="card-title">Descrição:</h5>
      <p class="card-text"><?php  echo isset($processo->txt_descricao) ? $processo->txt_descricao : null; ?></p>
    </div>
  </div>
</div>
</div>


 
 <div class="row">   
      <div class="col-md-12 p-4">
            

        </div>
      <div class="col-md-12">     
            <b></b>   
    </div>  
</div>
<span id="ajaxloading" style="display:none;">Validando formulário...</span>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>
