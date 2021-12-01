<?php 
function get_ufs(){
    $readJSONFile = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
    $array = json_decode($readJSONFile, TRUE);    

    usort($array, function ($a, $b) {
         return $a['sigla'] >= $b['sigla'];
    });

    $arr = array();
    foreach($array as $uf){
        $arr[] = array('uf' =>  $uf['sigla'],'nome' =>  $uf['nome']);
    }
    return $arr;
}

function get_paises(){
    $readJSONFile = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/paises');
    $array = json_decode($readJSONFile, TRUE);    

    $arr = array();
    foreach($array as $pais){
        $arr[] = array('id' =>  $pais['id']['M49'],'nome' =>  $pais['nome']);
    }
    return $arr;
}

function get_pais($codigo){
    $readJSONFile = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/paises/'.$codigo);
    $array = json_decode($readJSONFile, TRUE);    

    $arr = array();
    foreach($array as $pais){
        $arr[] = array('id' =>  $pais['id']['M49'],'nome' =>  $pais['nome']);
    }
    return $arr;
}

function get_uf($codigo){
    $readJSONFile = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'.$codigo);
    $array = json_decode($readJSONFile, TRUE);    

    $arr = array();
    $arr[] = $array;
    return $arr;
}



function get_by_CEP($cep,$json=False){
    $readJSONFile = file_get_contents("https://viacep.com.br/ws/".str_replace('-','',str_replace(".","",$cep))."/json/");
    $array = json_decode($readJSONFile, TRUE);
    $array['localidade_code'] = get_code_by_city($array['uf'],$array['localidade']);
    return $json ? $array : json_encode($array);
}

function get_cities($uf){
    $readJSONFile = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'.$uf.'/municipios');
    $array = json_decode($readJSONFile, TRUE);    
    
    $arr = array();
    foreach($array as $city){
        $arr[] = array('id' =>  $city['id'],'nome' =>  $city['nome']);
    }
    return $arr;
}

function get_city_by_code($code){
    $readJSONFile = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/municipios/'.$code);
    $array = json_decode($readJSONFile, TRUE);    
            
    return $array['nome'];
}

function get_code_by_city($uf,$city){
    $arr = get_cities($uf);

    foreach($arr as $c){
        if($c['nome']==$city)
            return $c['id'];
    }
    return false;
}

function get_ajax_ufs($sel){
    $arr = get_ufs();
    $selected = isset($sel) ? $sel : "";
    foreach($arr as $uf){
        echo "<option ".($selected==$uf['uf'] ? "selected" : "")." value='".$uf['uf']."'>".$uf['nome']."</option>";
    }
}


function get_ajax_paises($sel){
    $arr = get_paises();
    $selected = isset($sel) ? $sel : "";
    foreach($arr as $pais){
        echo "<option ".($selected==$pais['id'] ? "selected" : "")." value='".$pais['id']."'>".$pais['nome']."</option>";
    }
}

function get_ajax_cities($uf,$sel){
    $arr = get_cities($uf);
    $selected = isset($sel) ? $sel : "";
    foreach($arr as $city){
        echo "<option ".($sel==$city['id'] ? "selected" : "")." value='".$city['id']."'>".$city['nome']."</option>";
    }
}

if($_GET['tipo']=='cidade'){
    echo get_ajax_cities($_GET['uf'],$_GET['sel']);
}else if($_GET['tipo']=='estado'){
    echo get_ajax_ufs($_GET['sel']);
}else if($_GET['tipo']=='cep'){
    echo get_by_CEP($_GET['cep']);
}
?>