<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['Nome','txt_nome'],['Usuário','txt_usuario'],['Email','txt_email'],['Função','role']];
$id = 'id_user';
$exclusao = 0;
?>
<script>
let modal_id = 0;
function excluir(data){
    display_modal("Confirmação de exclusão","Deseja realmente excluir o usuario <b>"+data['txt_usuario']+"</b> referente à <b>"+data['txt_nome']+"</b>?",callback,"Sim, pode excluir!","Não, deixe como está!")   
    modal_id = data['id_user'];
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
<h1>Usuarios</h1>
<hr>
<div class="container">

<?php 
$pagination = "";
include GPATH."utils".S."pagination.php";
include GPATH."utils".S."table.php"; 
echo $paginacao;
?>


</div>