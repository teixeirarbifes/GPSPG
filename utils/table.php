<?php
if($data_table) $com = 1;
else $com = 0;
if(!isset($get_string)) $get_string = "";
echo $get_string;
?>
<table class="table table-bordered table-striped" style="top:40px;">
        <thead>
            <tr>
                <?php foreach($campos as $row){ $txt = $row[0]; echo "<th>".$txt."</th>"; } ?>
                <th><?php if(!isset($novo) || $novo!=0){ ?>
                    <a onclick="go_link('?controller=<?php echo $txt_controller.$get_string;?>&method=criar&pag=<?php echo $params['pag']; ?>&num=<?php echo $params['limit']; ?>');" class="btn btn-primary btn-sm">Novo</a></th>
                    <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if($exclusao==1){ ?>
            <form id="excluir" action="?controller=<?php echo $txt_controller.$get_string;?>&method=excluir&id={$id}" method="post">
                    <input type="hidden" name="excluir" value="1"/>
            </form>
            <?php } ?>
            <?php
            if ($com == 1) {
                $item = 0;
                foreach ($data_table as $row) {
                    $item = $item + 1;
                    ?>
                    <tr>
                        <?php 
                        $row_data = array();
                        foreach($campos as $campo){
                            $txt_campo = $campo[1];
                            echo "<td style='font-weight:normal'>".$row->$txt_campo."</td>";
                            $row_data[$txt_campo] = $row->$txt_campo;
                        }                        
                            $row_data[$id] = $row->$id;
                        ?>
                        <td style="white-space: nowrap;">
                            <?php if(isset($visualizar) && $visualizar==1){ ?>    
                                <a onclick="go_link('?controller=<?php echo $txt_controller;?>&method=visualizar&<?php echo $id.'='.$row->$id; ?>&pag=<?php echo $params['pag']; ?>&num=<?php echo $params['limit'].$get_string; ?>');" class="btn btn-primary btn-sm">Visualizar</a>    
                            <?php }else if(isset($visualizar) && $visualizar==2){ ?>    
                                <a onclick="go_link('?controller=<?=$visualizar_controller?>&method=<?=$visualizar_method?>&<?php echo $id.'='.$row->$id; ?><?=isset($visualizar_extra) ? '&'.$visualizar_extra : ''?>&pag=<?php echo $params['pag']; ?>&num=<?php echo $params['limit'].$get_string; ?>');" class="btn btn-primary btn-sm"><font color=white><?=$visualizar_txt?></font></a>    
                            <?php }else if(isset($visualizar) && $visualizar==3){ ?>    
                                <a onclick="go_link('<?php echo $visualizar_url.$row->$id; ?><?=isset($visualizar_extra) ? '&'.$visualizar_extra : ''?>');" class="btn btn-secondary btn-sm"><font color=white><?=$visualizar_txt?></font></a>    
                            <?php }else{ ?>
                            <a onclick="go_link('?controller=<?php echo $txt_controller;?>&method=editar&<?php echo $id.'='.$row->$id; ?>&pag=<?php echo $params['pag']; ?>&num=<?php echo $params['limit'].$get_string; ?>');" class="btn btn-primary btn-sm">Detalhe</a>
                            <?php } ?>
                            <?php if($exclusao==1){ ?>
                            <a onclick='<?php echo (isset($funcao_excluir) ? $funcao_excluir : "excluir")."(".json_encode($row_data).");" ?>' class="btn btn-danger btn-sm"><font color=white>Excluir</font></a>                            
                            <?php } ?>
                            <?php if(isset($botao_extra_1) && $botao_extra_1==1){ ?>    
                            </br></br><a onclick="go_link('?controller=<?=$botao_extra_1_controller?>&method=<?=$botao_extra_1_method?>&<?php echo $id.'='.$row->$id; ?>&pag=<?php echo $params['pag']; ?>&num=<?php echo $params['limit'].$get_string; ?>');" class="btn btn-primary btn-sm"><font color=white><?=$botao_extra_1_txt?></font></a>    
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5">Nenhum registro encontrado</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <script src="../ajax/ajax_submit.js"></script>
<script>
    conf_form('form');
    conf_form('excluir');
</script>