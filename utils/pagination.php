<?php ob_start(); 

if(!isset($get_string)) $get_string = "";
echo $get_string;
?>
<font size=3>
    <div class="row">
    <div class="col-4 col-md-4 col-lg-4"> 
    <span id="erro_role" class="form-text text-muted">Exibir:</span>
    <select id="limits" name="limits" onchange="go_link('/?controller=<?php echo $txt_controller.$get_string; ?>&method=<?php echo $txt_method; ?>&pag=<?php echo $params['pag']; ?>&num='+this.value);">
    <option <?php echo ($params['limit'] == 5 ? 'selected':''); ?> value="5"> 5 </option>                    
    <option <?php echo ($params['limit'] == 10 ? 'selected':''); ?> value="10"> 10 </option>                    
    <option <?php echo ($params['limit'] == 20 ? 'selected':''); ?> value="20"> 20 </option>                    
    <option <?php echo ($params['limit'] == 50 ? 'selected':''); ?> value="50"> 50 </option>                    
    <option <?php echo ($params['limit'] == 100 ? 'selected':''); ?> value="100"> 100 </option>                    
    <option <?php echo ($params['limit'] == 200 ? 'selected':''); ?> value="200"> 200 </option>                    
    <option <?php echo ($params['limit'] == 500 ? 'selected':''); ?> value="500"> 500 </option>                    
    <option <?php echo ($params['limit'] == 1000 ? 'selected':''); ?> value="1000"> 1000 </option>                    
    </select>
    </div>
    <div class="col-8 col-md-8 col-lg-8">      
    <?php for($i=1;$i<=$params['pags'];$i++){ ?>
        
        [ <?php
            if($i==$params['pag'])
                echo '<strong>'.$i.'</strong>'; 
            else 
                echo "<a style='cursor:pointer' onclick=\"go_link('/?controller=".$txt_controller.$get_string."&method=".$txt_method."&pag=".$i."&num=".$params['limit']."');\">".$i."</a>";
            ?> ]
    <?php }
    echo " || ";
    $nex = $params['pag']+1;
    if($nex>$params['pags']) $nex = $params['pags'];
    $bef = $params['pag']-1;
    if($bef < 1) $bef = 1;       

    if($bef != $params['pag'])
    echo "<a style='cursor:pointer' onclick=\"go_link('/?controller=".$txt_controller.$get_string."&method=".$txt_method."&pag=".$bef."&num=".$params['limit']."');\"> Anterior </a>";
    else
    echo "Anterior";

    if($nex != $params['pag'])
    echo "<a style='cursor:pointer' onclick=\"go_link('/?controller=".$txt_controller.$get_string."&method=".$txt_method."&pag=".$nex."&num=".$params['limit']."');\"> Próximo </a>";
    else
        echo "Proximo";    
    ?>
    </div>    
    </div>
    </font>
<?php $paginacao = ob_get_contents(); 
ob_end_clean();
?>
