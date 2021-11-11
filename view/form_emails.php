<script>   

    function callback(evento){
        if(evento==1){
            var excluir = document.getElementById('excluir');
            submit('excluir');
            close_modal();
        }else if(evento==2){
            close_modal();
        }else{
            close_modal();
        }
    }

    function excluir(){
        display_modal("Confirmação de exclusão","Deseja realmente excluir o email <b><?php echo $email->id_email; ?></b> referente à <b><?php echo $email->txt_email; ?></b>?",callback,"Sim, pode excluir!","Não, deixe como está!");
    }
</script>
<form id="excluir" action="?controller=emailscontroller&method=excluir&id=<?php echo $email->id_email; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post">
        <input type="hidden" name="excluir" value="1"/>
</form>

<form id=form class="form-horizontal" action="?controller=emailscontroller&<?php echo isset($email->id_email) ? "method=atualizar&id={$email->id_email}" : "method=salvar"; ?>&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>" method="post" >



  
<input type="hidden" name="id_email" id="id_email" value="<?php echo isset($email->id_email) ? $email->id_email : null; ?>" />
                <input type="hidden" name="salvar" id="salvar" value="1" />
                <!--a class="btn btn-success" id=bt_submit name=submit onclick="validar('form','usuario');"><font color=black>Enviar</font></a-->
                <!--a class="btn btn-secondary" id=bt_limpar onclick="form.clear();"><font color=black>Limpar</font></a-->
                <a class="btn btn-secondary" id=bt_cancelar  onclick="go_link('?controller=emailscontroller&method=listar&pag=<?php echo $data_table['pag']; ?>&num=<?php echo $data_table['num']; ?>');">Cancelar</a>
                <?php if(isset($usuario->id_user)){ ?>
                  <a class="btn btn-danger" id=bt_excluir  onclick="excluir();">Excluir</a>
                <?php } ?>
                </br></br>
ID: <?php echo $email->id_email;?></br>
PARA: <b><?php echo $email->txt_para;?> - <?php echo $email->txt_nome;?></b></br>
ENVIADO EM: <?php echo $email->dt_envio;?> - Criado em <?php echo $email->dt_criacao;?></br>
<h3><?php echo $email->txt_titulo;?><h3>
<p><?php echo $email->txt_conteudo;?></p>
<p style="font-size: 60%;">Resposta do servidor:</br><?php echo $email->txt_resposta;?></p>



    </div>
    
</form>
<span id="ajaxloading" style="display:none;">Validando formulário...</span>

<script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>
