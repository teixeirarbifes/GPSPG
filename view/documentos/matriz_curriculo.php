<div class="container d-sm-none d-lg-block d-md-block d-none">
   <div class="row">
    <div style="color:blue" class="border col-4 d-sm-none d-lg-block d-md-block d-block">
      Item de avaliação
    </div>
    <div style="color:blue" class=" border col-2 d-sm-none d-lg-block d-md-block d-block">
      Pontos por doc.
    </div>
    <div style="color:blue" class="border col-2 d-sm-none d-lg-block d-md-block d-block">
      Máximo
    </div>
    <div style="color:blue" class="border col-2 d-sm-none d-lg-block d-md-block d-block">
      Carregado(s)
    </div>
    <div style="color:blue" class="border col-2 d-sm-none d-lg-block d-md-block d-block">
      Total de Pontos
    </div>
    </div>

<?php $total = 0;
foreach($matriz_classe as $item){ 
    $total+= $item->total;
    ?>
  <div class="row">
    <div style="" class="border col-4 ">
        <font size=1><?=$item->txt_classe?></font>
    </div>
    <div style="" class=" border col-2 ">
        <?=$item->num_ponto?> ponto<?=$item->num_ponto > 1 ? "s" : ""?>/doc
    </div>
    <div style="" class=" border col-2 ">
        <?=$item->num_maximo?> ponto<?=$item->num_maximo > 1 ? "s" : ""?>
    </div>
    <div style="" class="border col-2 ">
        <?=$item->num_docs?> doc<?=$item->num_docs > 1 ? "s" : ""?>
    </div>
    <div style="" class="border col-2">
        <?=$item->total?> ponto<?=$item->total > 1 ? "s" : ""?>
    </div>
    <!--div style="" class="border col-md-1 col-sm-12">
      <a class="btn-primary">Upload</a>
    </div-->
  </div>
<?php } ?>
<div class="row">
    <div style="color:blue" class=" col-6 d-sm-none d-lg-block d-md-block d-block">
      
    </div>
    <div style="color:blue" class="  col-2 d-sm-none d-lg-block d-md-block d-block">
      
    </div>
    <div style="color:blue" class="border col-2 d-sm-none d-lg-block d-md-block d-block">
       Total Geral:
    </div>
    <div style="color:blue" class="border col-2 d-sm-none d-lg-block d-md-block d-block">
    <?=$total?> ponto<?=$total > 1 ? "s" : ""?>
    </div>
    </div>
</div>

<div class="container d-sm-block d-lg-none d-md-none d-block">    
<?php foreach($matriz_classe as $item){ ?>    
    <div class="row">
        <div style="color:blue" class="border col-4">
            Item de avaliação
        </div>
        <div style="" class="border col-6">
        <font size=1><?=$item->txt_classe?></font>
        </div>
    </div>
    <div class="row">
         <div style="color:blue" class="border col-4">
            Pontos por doc.
        </div>
        <div style="" class="border col-6">
            <?=$item->num_ponto?> ponto<?=$item->num_ponto > 1 ? "s" : ""?>/doc
        </div>
    </div>
    <div class="row">
         <div style="color:blue" class="border col-4">
            Máximo
        </div>
        <div style="" class="border col-6">
            <?=$item->num_maximo?> ponto<?=$item->num_maximo > 1 ? "s" : ""?>
        </div>
    </div>
  <div class="row">
        <div style="color:blue" class="border col-4">
            Carregado
        </div>
        <div style="" class="border col-6">
        <?=$item->num_docs?> doc<?=$item->num_docs > 1 ? "s" : ""?>
        </div>
  </div>  
  <div class="row">
        <div style="color:blue" class="border col-4">
            Total de Pontos
        </div>
        <div style="" class="border col-6">
        <?=$item->total?> ponto<?=$item->total > 1 ? "s" : ""?>
        </div>
  </div>    
  </br>
  <?php } ?>  
  <div class="row">
        <div style="color:blue" class="border col-4">
            Total Geral:
        </div>
        <div style="" class="border col-6">
        <?=$total?> ponto<?=$total > 1 ? "s" : ""?>
        </div>
    </div>
</div>