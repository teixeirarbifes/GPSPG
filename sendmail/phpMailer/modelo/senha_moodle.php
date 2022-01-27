<?php
$titulo = "Informações de acesso para ambiente de prova online";
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
<p>
<span style="font-size:14px">
Considerando que sua inscrição para o processo seletivo foi homologada, informamos nesse e-mail as informações para acesso à plataforma Moodle, denominada AVA-IFES - Ambiente Virtual de Aprendizagem do IFES, no site http://ava.cefor.ifes.edu.br.</br><br>    

A prova do processo seletivo será realizada domingo, dia 06 de fevereiro de 2022, das 14h às 17h, conforme estabelecido no item 10 do edital.</br><br>

O acesso ao ambiente de prova será por meio do CPF do candidato habilitado:</br><br>

<span style="font-size:18px">
<font face="Courier New">
Usuário: <b><font color=darkblue><?=str_replace("-","",str_replace(".","",$data['txt_cpf']))?></font></b></br>
Senha inicial: <b><font color=darkblue>Mud@r123</font></b></br>
Link para acesso: <a target=_blank href="http://ava.cefor.ifes.edu.br">http://ava.cefor.ifes.edu.br</a>
</font>
</span></br><br>

A página para realização da prova já está disponível, denominada “Processo seletivo 67/2021 - Pós-Graduação lato sensu em Engenharia de Produção com ênfase em Tecnologias da Decisão”, pela opção “Cursos” do sistema após acesso.</br><br>

Orientamos aos candidatos para acessarem a plataforma antes da data de realização da prova oficial. Recomendamos a alteração da senha inicial logo após o primeiro acesso. A recuperação de senha é útil no caso de esquecimento.</br><br>

Caso tenha problemas no acesso, recomendamos que envie e-mail para suporte.ppgep.car@ifes.edu.br para maiores orientações, utilizando o e-mail cadastrado no processo seletivo. As dúvidas sobre a utilização do sistema serão esclarecidas até 15h00 de sexta-feira, dia 04 de janeiro de 2021, pelo email suporte.ppgep.car@ifes.edu.br.</br><br>

E informamos que, no período de 26 a 02 de fevereiro de 2022, o candidato poderá realizar uma prova de treino no AVA-IFES para se ambientar ao uso da plataforma. A prova de treino contém questões fora da bibliografia do edital e não altera o resultado do concurso.</br><br>

Maiores informações estão disponíveis no edital e na página da prova no sistema.</br><br>

<font size=1>
<p>Caso não tenha realizado cadastro no site GPS-PG, <a href="https://gpspg.gemad.net/?wrong=1&email=<?php echo $to_email?>">Clique aqui</a>.</br>Qualquer coisa, pode entrar em contato pelo e-mail suporte.ppgep@gemad.net.</p>
</font>
</br>
<p>
Cordialmente,
</br></br>
GPS-PG - Sistema de Gestão de Processos Seletivos</br>
GEMAD - Grupo de Estudos em Manufatura Digital</br>
<a href="https://gpspg.gemad.net">https://gpspg.gemad.net</a>
</p>
</div>
</div>