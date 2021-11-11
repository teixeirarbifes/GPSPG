
<h3><b><font color=darkblue><?php echo $processo->txt_processo; ?></font></b></h3>
</br>
<h3>Painel de edição de inscrição</h3>
<hr>
<a class="btn btn-secondary" onclick="go_link('?controller=processoscontroller&method=visualizar_candidato&id_processo=<?=$processo->id_processo?>');"><font color=black><< Voltar para processo seletivo</font></a>
<hr>
<div class=row>
  <div>
  <?php $aberto = ProcessosController::aberto($processo->id_processo); ?>
<?php if($aberto==2){ ?>
<div>
    Você está no área de edição da sua inscrição.</br>
    Preencha sua ficha de inscrição corretamente e anexe documentos pessoais, formulários e documentos de currículo.</br>
    Ao final desse processo, clique no botão "Verificar e enviar inscrição em edição" para verificar os dados informados e enviar a inscrição.</br>
    </br>
    <font color=red>Inscrições que não forem enviadas não serão consideradas no processo seletivo.</font>
    <font color=red>Somente a última inscrição enviada será considerada.</font>
    </br></br>
    <b><font color=darkblue size=3>Clique nos botões abaixo para acessar os formulários de edição.</font></b>
</div>
<div class="row">
<div style="width:fit-content; block-size: fit-content;float:left;" class="p-2">
<a style="cursor:pointer" onclick="go_link('/?controller=FichaController&method=editar&id_ficha=<?php echo $ficha->id_ficha; ?>');">
<img width=200px class="img-responsive center-block" src="/images/botao_ficha.jpg" alt="Your Alt Text">
</a>
</div>
<div style="width:fit-content; block-size: fit-content;float:left;" class="p-2">
<a style="cursor:pointer" onclick="go_link('/?controller=DocumentosController&method=listar_ficha&id_ficha=<?php echo $ficha->id_ficha; ?>');">
<img width=200px class="img-responsive center-block" src="/images/botao_pessoal.jpg" alt="Your Alt Text">
</a>
</div>
<div style="width:fit-content; block-size: fit-content;float:left" class="p-2">
<a style="cursor:pointer" onclick="go_link('/?controller=DocumentosController&method=listar_curriculo&id_ficha=<?php echo $ficha->id_ficha; ?>');">
<img width=200px class="img-responsive center-block" src="/images/botao_curriculo.jpg" alt="Your Alt Text">
</a>
</div>
</div>

<?php }else if($aberto==0){ ?>
  <div>
    O período de inscrições ainda não iniciou. Por tanto, aguarde o período de inscrições para acessar os formulários de edição.
  </div>
<?php }else{ ?>
  <div>
    O período de inscrições está encerrado. Não é possível acessar os formulários de edição.
  </div>
<?php } ?>
<div class="row">
</div></br></br>

<b><font color=darkblue size=3>Verificação dos dados</font></b>
<div class="row">
<?php
if($aberto==2){ ?>
<div style="width:fit-content; block-size: fit-content;float:left" class="p-2">
<a style="cursor:pointer" onclick="go_link('/?controller=inscricaocontroller&method=verificar&id_processo=<?php echo $processo->id_processo; ?>');">
<img width=300px class="img-responsive center-block" src="/images/botao_verificar.jpg" alt="Your Alt Text">
</a>
</div>

<?php }else{ ?>
<?php } ?>
<?php if(isset($inscricao->id_ficha_enviada) && $inscricao->id_ficha_enviada>0){ ?>
<div style="width:fit-content; block-size: fit-content;float:left" class="p-2">
<a style="cursor:pointer" onclick="go_link('/?controller=inscricaocontroller&method=ver_entregue&id_processo=<?php echo $processo->id_processo; ?>');">
<img width=300px class="img-responsive center-block" src="/images/botao_conferir.jpg" alt="Your Alt Text">
</a>
</div>
<?php } ?>
</div>
<?php if($aberto==0){ ?>
<font color=blue>O período de envio de inscrições ainda não está aberto.</font>
<?php }else if($aberto==1){ ?>
  <font color=red>O período de envio de inscrições está encerrado!</font>
<?php } ?>
</div>
</div>
</div>