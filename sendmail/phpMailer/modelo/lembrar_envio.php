<?php
$titulo = "Envio de inscrição pendente";
?>

<div style="font-size:14px; border: 20px solid #969696;text-align:center;max-width:700px;padding:2ex">
<a href="https://gpspg.gemad.net"><img src="cid:logo"/><?php //img src="/images/logosmall.png.jpg" ?></a>
</br>
<h2>Sistema de Gestão de Processos Seletivos - Pós-Graduação</h2>
<hr>
<div style="text-align:left;font-family: Arial, Helvetica, sans-serif;">
<p>
    Prezado(a) <?=$data['txt_nome']?>,
</p>
<p>
<span style="font-size:14px">
Referente ao processo seletivo <b><?=$data['txt_processo']?></b> consta em nossos registros que não foi concluído o envio de inscrição para análise</b>.
</br></br>
<font color=red>Ainda dá tempo! Verifique a sua inscrição quanto à ficha e aos documentos. Faça o envio dentro do prazo!</font>
</br></br>
Lembrando que inscrições já enviadas para análise podem ser reenvidas (retificadas) dentro do prazo de inscrição.
</br></br>
Durante o processo de análise, inscrições não enviadas no prazo ou com documentação incompleta poderão ser indeferidas conforme previsto no edital.
<span>
</p>
</br></br>
<font size=1>
<p>Caso não tenha realizado cadastro ou já tenha efetuado sua inscrição no site GPS-PG, <a href="https://gpspg.gemad.net/?wrong=1&email=<?php echo $to_email?>">Clique aqui</a>.</br>Qualquer coisa, pode entrar em contato pelo e-mail suporte.ppgep@gemad.net.</p>
</br>
<p>
</font>
Cordialmente,
</br></br>
GPS-PG - Sistema de Gestão de Processos Seletivos</br>
GEMAD - Grupo de Estudos em Manufatura Digital</br>
<a href="https://gpspg.gemad.net">https://gpspg.gemad.net</a>
</p>
</div>
</div>