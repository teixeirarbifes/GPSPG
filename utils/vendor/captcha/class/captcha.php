<?php
 $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
 function secure_generate_string($input, $strength = 5, $secure = true) {
     $input_length = strlen($input);
     $random_string = '';
     for($i = 0; $i < $strength; $i++) {
         if($secure) {
             $random_character = $input[random_int(0, $input_length - 1)];
         } else {
             $random_character = $input[mt_rand(0, $input_length - 1)];
         }
         $random_string .= $random_character;
     }
   
     return $random_string;
 }
 $string_length = 6;
 $captcha_string = secure_generate_string($permitted_chars, $string_length);
 $captcha_string_key = hash('sha256', $captcha_string);
 session_start();
 $_SESSION[$captcha_string_key] = $captcha_string;
 
ob_start();
?>
<br>
<div class="container-small" style="max-width:210px">
<div class = "p-2 border" st>
<image src="/utils/vendor/captcha/class/captcha_image.php?key=<?php echo $captcha_string_key; ?>"/>
    <div class="pb-sm-2">
    <input type=hidden name="key" value="<?php echo $captcha_string_key; ?>"/>
    </br>
    <a style="cursor:pointer" onclick="go_link('/utils/vendor/captcha/class/captcha.php','captcha',false);">Recarregar imagem</a>
    </br>
    </br><b>Por favor, digite as letras que aparecem na figura acima.</br>
    <input type=text id=codigo name="codigo"></input>
    </div>
    </span>
</div>
</div>
</br>
<?php
$content = ob_get_contents();
ob_end_clean();
echo json_encode(['code'=>200, 'msg'=>$content]);
