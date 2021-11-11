<?php
$titulo = "Confirmação de inscrição";
?>

<div style="font-size:14px; border: 20px solid #969696;text-align:center;max-width:700px;padding:2ex">
<a href="https://gpspg.gemad.net"><img src="cid:logo"/><?php //img src="/images/logosmall.png.jpg" ?></a>
</br>
<h2>Sistema de Gestão de Processos Seletivos - Pós-Graduação</h2>
<hr>
<div style="text-align:left;font-family: Arial, Helvetica, sans-serif;">
<p style="font-size:14px">
Este e-mail confirma o envio de sua inscrição para o processo seletivo <b><?=$data['processo']?></b> em <?=$data['data']?>.
<p>
<p style="font-size:14px">
</br>Sua inscrição continua disponível para conferência no sistema. Retificações e reenvios poderão ser realizados durante o período de inscrição, mas somente o último envio será considerado.<span>
</p>
<p>
<b>O código de protocolo de envio é</b>
</br></br>
<span style="font-size:25px;color:blue"><strong><?php echo $data['protocolo']?></strong></span>
</p>
<p>Qualquer dúvida, entrar em contato pelo e-mail suporte.ppgep@gemad.net.</p>
</br>
<p>
Cordialmente,
</br></br>
GPS-PG - Ifes - Campus Cariacica
<a href="https://gpspg.gemad.net">https://gpspg.gemad.net</a>
</p>
</div>
</div>