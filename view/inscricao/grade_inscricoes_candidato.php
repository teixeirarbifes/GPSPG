<?php
$request = new Request();
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');

$campos = [['Processo seletivo','txt_processo'],['Aceita envios?','txt_aberto'],['Situação da inscrição','txt_status']];
$id = 'id_processo';
$exclusao = 0;
$novo = 0;
$visualizar = 2;
$visualizar_controller = "processoscontroller";
$visualizar_method = "visualizar_candidato";
$visualizar_txt = "Ir para...";
?>
<?php include GPATH."utils".S."modal.php"; ?>
<h1>Minhas inscrições em processos seletivos</h1>
<hr>
<div class="container">




<?php 
if($data_table){
?>
<div style="text-align:center">
<h4>Para mais informações sobre uma inscrição, clique em Visualizar.<h4>
</div>

<?php
    $pagination = "";
include GPATH."utils".S."pagination.php";
include GPATH."utils".S."table.php"; 
//echo $paginacao;
}else{
?>

<div style="text-align:center">
<h3>Não há ainda inscrição para seu cadastro.<h3>
<h3>Acesse a página "Processos Seletivos" para se inscrever em processo seletivo.<h3>
</div>

<?php } ?>

</div>