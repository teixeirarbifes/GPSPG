<h3><b><font color="darkblue"><?php echo $processo->txt_processo; ?></font></b></h3>
<hr>
<font color="darkred">A ficha, bem como toda inscrição, somente será considerada após o envio (ou reenvio). Lembre-se de enviar para análise a sua inscrição após finalizada sua edição.</font>
</br></br>
<a class="btn btn-<?=$pagina=="ficha" ? 'info"' : 'primary'?>" id=salvar   onclick="go_link('/?controller=FichaController&method=editar&id_ficha=<?php echo $ficha->id_ficha; ?>');"><font color=black>Editar Ficha</br>de Inscrição</font></a>&nbsp;  
<a class="btn btn-<?=$pagina=="pessoal" ? 'info"' : 'primary'?>" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_ficha&id_ficha=<?php echo $ficha->id_ficha; ?>');"><font color=black>Documentos Pessoais</br>e Formulários</font></a>&nbsp;  
<a class="btn btn-<?=$pagina=="curriculo" ? 'info"' : 'primary'?>" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_curriculo&id_ficha=<?php echo $ficha->id_ficha; ?>');"><font color=black>Currículo</br>e anexos</font></a>&nbsp;  
<a class="btn btn-primary" id=salvar  onclick="go_link('/?controller=DocumentosController&method=listar_curriculo&id_ficha=<?php echo $ficha->id_ficha; ?>');"><font color=black>Informações sobre</br>o processo</font></a>&nbsp;  


<hr>
<div class=container>
    
</div>