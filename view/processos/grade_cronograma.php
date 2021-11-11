<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['ID','id_cronograma'],['Momento','dt_inicio'],['Evento','txt_descricao'],['Status','status']];
$id = 'id_cronograma';
$get_string = '&id_processo='.$processo->id_processo;
$exclusao = 1;
$visualizar = 0;
$funcao_excluir = "excluir_grade_cronograma";
?>
<script>
try{
    let modal_id = 0;
}catch(e){
    modal_id = 0;
}
excluir_grade_cronograma = function(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o processo <b>"+data['id_cronograma']+"</b>?",callback_grade_cronograma,"Sim, pode excluir!","Não, deixe como está!")   
    modal_id = data['id_cronograma'];
}
callback_grade_cronograma = function(evento){
    if(evento==1){
        var excluir = document.getElementById('excluir');        
        excluir.action = "?controller=cronogramacontroller&method=excluir&excluir=1<?php echo $get_string; ?>&id_cronograma=" + modal_id + "&id=" + modal_id;
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
<h1>Cronograma</h1>
<hr>
<div class="container">

<?php 
$pagination = "";
include GPATH."utils".S."pagination.php";
include GPATH."utils".S."table.php"; 
echo $paginacao;
?>


</div>