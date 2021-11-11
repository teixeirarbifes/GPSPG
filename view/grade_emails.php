<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['ID','id_email'],['Para','txt_para'],['Nome','txt_nome'],['Título','txt_titulo']];
$id = 'id_email';
$exclusao = 0;
$salvar = 0;
?>
<script>
let modal_id = 0;
function excluir(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o registro de e-mail <b>"+data['id_email']+"</b> referente à <b>"+data['txt_para']+"</b>?",callback,"Sim, pode excluir!","Não, deixe como está!")   
    modal_id = data['id_email'];
}

function callback(evento){
    if(evento==1){
        var excluir = document.getElementById('excluir');        
        go_ajax(excluir.action.replace('{$id}',modal_id),'excluir');
    }else if(evento==2){
        close_modal();
    }
}

function confirma(evento){
        close_modal();
}
</script>

<?php include GPATH."utils".S."modal.php"; ?>
<h1>Log de Envio de E-mails</h1>
<hr>
<div class="container">

<?php 
$pagination = "";
include GPATH."utils".S."pagination.php";
include GPATH."utils".S."table.php"; 
echo $paginacao;
?>


</div>