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
<h1>Processos Seletivos Públicos</h1>
</br>
<b>Para iniciar a inscrição para um processo seletivo com inscrições abertas, clique em <font color=red>Mais informações</font> e, depois, na página do processo seletivo, clique em <font color=red>Iniciar inscrição</font>.</b>
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
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $date1 = $processo->dt_inicio_inscricao;
    $date2 = $processo->dt_fim_inscricao;

    $t1 = strtotime($date1);
    $t2 = strtotime($date2);

    $s1 = strftime('%A, %d de %B de %Y às %H:%M:%S',$t1);
    $s2 = strftime('%A, %d de %B de %Y às %H:%M:%S',$t2);
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
                  Última ocorrência: <b><?=$processo->cronograma->txt_descricao?> em <?=strftime('%d de %B de %Y às %H:%M:%S',strtotime($processo->cronograma->dt_inicio))?></b>
                <?php } ?>
                </h6>
              </div>

        </p>
          <p class="card-text" style="text-align:left">
        </br>
                <a class="btn btn-primary" onclick="go_link('?controller=processoscontroller&method=visualizar_candidato&id_processo=<?php echo $processo->id_processo; ?>&pag=<?php echo $params['pag']; ?>&num=<?php echo $params['limit']; ?>');"><font color=black>Mais informações...</font></a> 
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