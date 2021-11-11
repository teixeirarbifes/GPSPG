<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['ID','id_status'],['Status','txt_status']];
$id = 'id_status';
$exclusao = 0;
?>
<script>
let modal_id = 0;
function excluir(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o status <b>"+data['id_status']+"</b>?",callback,"Sim, pode excluir!","Não, deixe como está!")   
    modal_id = data['id_processo'];
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
<h1>Cadastro de Processos Seletivos</h1>
<hr>
<div class="container">

<?php 
$pagination = "";
include GPATH."utils".S."pagination.php";
include GPATH."utils".S."table.php"; 
echo $paginacao;
?>


</div>