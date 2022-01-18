<?php
$titulo = "Apresentação de Recurso para Processo Seletivo";
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
Referente ao processo seletivo <b><?=$data['processo']->txt_processo?></b>, o seu recurso foi apresentado, destinado à "recurso contra o resultado parcial das inscrições"</b>, em <?=$data['dt_submissao']?> sob o protocolo <?=$data['recurso']->txt_protocolo?>.
</br></br>
Argumentação fundamentada:
<?=$data['recurso']->txt_recurso?>
</br></br>
O acompanhamento da análise do recurso poderá ser realizado no sistema e na página do processo seletivo, conforme edital.
<span>
</p>
</br></br>
<font size=1>
<p>Caso não tenha realizado cadastro ou apresentado recurso no site GPS-PG, <a href="https://gpspg.gemad.net/?wrong=1&email=<?php echo $to_email?>">Clique aqui</a>.</br>Qualquer coisa, pode entrar em contato pelo e-mail suporte.ppgep@gemad.net.</p>
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