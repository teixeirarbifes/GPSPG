           
                
            <?php   
            $data = Session::getInstance(); 
            $mensagens = $data->mensagens->getArrayCopy();            
            $item = -1; 
            
            foreach ($mensagens as $msg) {                                                    
                $item = $item + 1;
                if($msg[1] == 0)
                    $classe = "alert alert-success";
                else if($msg[1] == 1)
                    $classe = "alert alert-warning";
                else if($msg[1] == 2)
                    $classe = "alert alert-danger";
                else
                    $classe = "alert alert-info";
                ?>
                 
                <div  id="msg_<?php echo $item;?>" class="<?php echo $classe;?>" style="width:100%">
                    <font size=4><b><?php echo $msg[0];?></b></font>
                    <span class="closebtn_top" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>                    
            
                <script type="text/javascript">
                $(document).ready(function(){
                    <?php if($msg[1]==1 || $msg[1]==2){ ?>
                        $('#msg_<?php echo $item;?>').show();
                    <?php }else{?>
                        $('#msg_<?php echo $item;?>').show().delay(<?php echo 5000+$item*50;?>).fadeOut('slow');
                    <?php } ?>
                });
                </script>
                           
                <?php  
                $data->mensagens[$item][0] = 'deletado';
            }
            $data->mensagens = new ArrayObject();
            ?>
