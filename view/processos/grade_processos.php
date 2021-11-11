<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['ID','id_processo'],['Processo Seletivo','txt_processo'],['Status','status']];
$id = 'id_processo';
$exclusao = 1;
$visualizar = 1;
$funcao_excluir = "excluir_grade_processos";
?>
<script>
let modal_id = 0;
delete excluir;
delete callback;
excluir_grade_processos = function(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o processo <b>"+data['id_processo']+"</b>?",callback_grade_processos,"Sim, pode excluir!","Não, deixe como está!")   
    modal_id = data['id_processo'];
}

callback_grade_processos = function(evento){
    if(evento==1){
        var excluir = document.getElementById('excluir');        
        excluir.action = "?controller=processoscontroller&method=excluir&excluir=1&id_processo="+modal_id+"&id=" + modal_id;
        alert(excluir.action);
        submit('excluir');
        close_modal();
    }else if(evento==2){
        close_modal();
    }
}

//function confirma(evento){
//        close_modal();
//}
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