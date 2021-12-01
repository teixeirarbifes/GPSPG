<?php
require_once('conf.php');
require_once(GPATH.'controller'.S.'mensageirocontroller.php');
require_once(GPATH.'controller'.S.'usuarioscontroller.php');
require_once(GPATH.'request'.S.'session.php');
require_once(GPATH.'utils'.S.'util_local.php');
define('VERSION',156);
setlocale(LC_ALL, 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');


if($_REQUEST['action'] == 'download'){
    $_REQUEST['controller'] = "documentoscontroller";
    $_REQUEST['method'] = 'download';
    $_REQUEST['id_doc'] = $_REQUEST['d'];
    $_REQUEST['id_ficha'] = $_REQUEST['f'];
    $_REQUEST['pag'] = 1;
    $_REQUEST['num'] = 1000;
}else if($_REQUEST['action'] == 'zip'){
    $_REQUEST['controller'] = "inscricaocontroller";
    $_REQUEST['method'] = 'download_inscricao';
    $_REQUEST['id_processo'] = $_REQUEST['p'];
}

$data = Session::getInstance(); 

if(!$data->__isset("mensagens")) $data->mensagens = new ArrayObject();

error_reporting(E_ALL);
ini_set('display_errors', true);

spl_autoload_register(function($class) {
    $class = strtolower($class);
    if (file_exists(GPATH."controller".S."$class.php")) {
        require_once GPATH."controller".S."$class.php";
        return true;
    }
});


if(isset($_GET['service']) && $_GET['service']=='update_status'){
  CronogramaController::update_status();
  echo "OK";
  exit();

}else{

  CronogramaController::update_status();
}

$request = new Request();
$ajax = 1;
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');
if(empty($txt_controller)){	
  $ativa = $request->__get('ativacao');
  if(!empty($ativa) && $ativa==1){
    $txt_controller = 'usuarioscontroller';
	  $txt_method = "form_ativar";
    $ajax = 0;
  }else{
	  $txt_controller = 'homecontroller';
	  $txt_method = "home";
	  $ajax = 0;
  }
}else{
	$ajax = 1;
}	

$result = "";
if(UsuariosController::is_logged())
  if(UsuariosController::primeiroacesso()){
    $txt_controller = "usuarioscontroller";
    $txt_method="welcome"; 
  }else if($txt_controller!=""){
    if(!($txt_controller=="usuarioscontroller" && $txt_method=="sair") && $txt_controller!="mensageirocontroller"){		
      if(isset($usuario->id_user)){
        if($usuario->bl_force_change){
          $txt_controller = "usuarioscontroller";
          $txt_method = "alterar_senha";
          $controller = new Controller();
          $controller->msg('Para sua segurança, é necessário alterar a sua senha para continuar acessando o sistema.',0);
        }
      }
    }
}

$controller = isset($txt_controller) ? ((class_exists($txt_controller)) ? new $txt_controller : NULL ) : null;
$method     = isset($txt_method) ? $txt_method : null;
if ($controller && $method) {
	if (method_exists($controller, $method)) {
		$parameters = $_REQUEST;
		unset($parameters['controller']);
		unset($parameters['method']);
		$result .= call_user_func(array($controller, $method), $parameters);            
	} else {
		echo "Método não encontrado!";
	}
} else {
	echo "Controller não encontrado!";
}

if(isset($_REQUEST['action']) || ($method!=null && $method=='download')){
	echo $result;
	exit();
}

if($result!="reload")
$msgs = (new MensageiroController())->listar_session();
else
$msgs = "";
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//if(is_numeric($request->__get('ajax')) && $request->__get('ajax')==1){
if($ajax == 1 && (!isset($_REQUEST['noajax']) || $_REQUEST['noajax'] != 1)){
	if(UsuariosController::is_logged()){
		$id_user = UsuariosController::get_usuario()['id_user'];
	}else
		$id_user = 0;

	echo "<input type=hidden id='gpspg_id_user' value='".$id_user."'/><msgs>".$msgs.'</msgs>'.$result;
  exit();
}
ob_start();
#exit();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- Required meta tags --> 
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>GPSPG - Sistema de Gestáo de Processos Seletivos - Pós-graduação</title>
  <title><?=DESENVOLVIMENTO == 1 ? "DEVELOPMENT :: " : ""?> GPSPG - Sistema de Gestáo de Processos Seletivos - Pós-graduação</title>
  <!-- base:css -->
  <link rel="stylesheet" href="regal/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="regal/vendors/feather/feather.css">
  <link rel="stylesheet" href="regal/vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="regal/vendors/flag-icon-css/css/flag-icon.min.css"/>
  <link rel="stylesheet" href="regal/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="regal/vendors/jquery-bar-rating/fontawesome-stars-o.css">
  <link rel="stylesheet" href="regal/vendors/jquery-bar-rating/fontawesome-stars.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="regal/css/style.css">
  <!-- endinject -->
  
    <link rel="stylesheet" href="assets/css/main2.css?155" />
    <link href='https://use.fontawesome.com/releases/v5.1.0/css/all.css' rel='stylesheet' integrity='sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt' crossorigin='anonymous' />
    <link href='/utils/css/modal.css?155' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js' integrity='sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T' crossorigin='anonymous'></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
    <script src="/assets/function.js?<?=VERSION?>"></script>
		<script src="/assets/js/captcha.js?<?=VERSION?>"></script>
		<script src="/ajax/ajax_submit.js?<?=VERSION?>"></script>
		<script src="https://cdn.tiny.cloud/1/l5hr79dltkjldhpincf3rzg93ch5tz7yjblanibpzinyjize/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">	
    <script src="/utils/change_form.js?<?=VERSION?>"></script>
    <script src="regal/vendors/base/vendor.bundle.base.js"></script>
    <!--link href="/utils/vendor/jstable/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" /-->
    <link href="/utils/vendor/jtable/scripts/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />
    <!--link href="/utils/vendor/jtable//Scripts/jtable/themes/metro/blue/jtable.css" rel="stylesheet" type="text/css" /-->
    
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->
    <!--script src="/utils/vendor/jtable/scripts/jquery-1.6.4.min.js" type="text/javascript"></script-->
    <script src="/utils/vendor/jtable/scripts/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

    <script src="/utils/vendor/jtable/scripts/jtable/jquery.jtable2.js" type="text/javascript"></script>  

  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
<div id="carregando" class="dialog-loading-wrapper" style="display:none;padding:10px">
			<span class="dialog-loading-icon">
				<img width=60px src="/images/loading.gif"/>
				<span id="txt_carregando" style="font-size:20px"><b>Carregando página...</b></span>
				<img style="cursor:pointer" onclick="cancela_ajax();" width=30px src="/images/cancel.png"/>
			</span>
		</div>
		<?php include GPATH."utils".S."modal.php"; ?>
		<?php include GPATH."utils".S."modal_loading.php"; ?>
		<input type="hidden" id="check_id_user" value="<?php 
			if (UsuariosController::is_logged())
				echo UsuariosController::get_usuario()['id_user'];
			else
				echo 0;
		 			?>"/>
		<script>
			function check_user(user){
				if(document.getElementById('check_id_user').value != user){
					alert('Você saiu do sistema!');
					window.
				}
			}
		</script>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="/"><img src="images/logotiny.png" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/"><img src="images/simbol.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <div class="container-fluid p-2">
        <table style="width:100%"><tr><td>
        <font size=3><b><?=DESENVOLVIMENTO == 1 ? "<font color=red>DEVELOPMENT :: </font>" : ""?> Sistema de Gestão de Processos Seletivos - Pós-Graduação</b></font>
        </td></tr><tr><td>


        
        <?php if(!UsuariosController::is_logged()){ ?>
          <font size=1><b>Olá, candidato!</b> </font></br>
            <a style="color:black;cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-success" onclick="go_link('/?controller=usuarioscontroller&method=registrar1');"  >
                <i class="icon-head"></i> Registrar-se!
            </a> 
            <a style="cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer" class="btn btn-secondary"  onclick="go_link('/?controller=usuarioscontroller&method=form_login');">
                <i class="icon-inbox"></i> Acessar
            </a>
            <?php }else{?>
              <font size=1><b>Olá, <?=UsuariosController::get_usuario()['txt_nome']?></b> </font></br>
              <a style="cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer"  class="btn btn-secondary"  onclick="go_link('/?controller=usuarioscontroller&method=form_perfil	');"><i class="icon-head"></i> Perfil</span></a> 
              <a style="cursor:pointer;padding-left: 6px;padding-top: 3px;padding-right: 6px;padding-bottom: 3px;cursor:pointer"   class="btn btn-secondary"  onclick="go_link('/?controller=usuarioscontroller&method=sair');"><i class="icon-outbox"></i> Sair</a>             
            <?php } ?>
        </td><td>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button></td></tr></table>
      </div>
      
        
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="/">
              <i class="icon-layout menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Homepage</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=processoscontroller&method=listar_candidato');" >
              <i class="icon-paper menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Processos Seletivos</span>
            </a>
          </li>
                    
          <?php if(UsuariosController::is_logged()){ ?>
          <li class="nav-item">
            <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=inscricaocontroller&method=listar_inscricao');" >
              <i class="icon-file menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Minhas inscrições</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=listar');" >
              <i class="icon-archive menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Meus recursos</span>
            </a>
          </li>    

          <?php }
          if(!UsuariosController::is_logged()){ ?>
          <li class="nav-item">
            <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=registrar1');"><span>
              <i class="icon-circle-plus menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Novo cadastro</span>
            </a>
          </li>   
          <li class="nav-item">
            <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=form_login');"><span>
              <i class="icon-inbox menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Acesso ao Sistema</span>
            </a>
          </li>                    
          <li class="nav-item">
            <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=form_ativar');"><span>
              <i class="icon-circle-check menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Ativar conta</span>
            </a>
          </li> 
          <?php }else{ ?> 
            <!--li class="nav-item">
            <a class="nav-link" href="#ui-basic" data-toggle="collapse" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-disc menu-icon" style="color:black"></i>
              <span class="menu-title" style="color:black">Usuário</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=form_perfil	');"><span>
                  <span class="menu-title" style="color:black">Perfil de Usuário</span>
                </a>
              </li> 

              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=alterar_senha');"><span>
                  <span class="menu-title" style="color:black"> Alterar Senha</span>
                </a>
              </li> 

              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=sair');"><span>
                  <span class="menu-title" style="color:black">Sair do Sistema</span>
                </a>
              </li> 

            </ul>
            </div>
            </li-->    
          <?php } ?>	

      

          <?php if(UsuariosController::is_logged()){ ?>
          <?php if($controller->check_auth([3,4])) { ?>

            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-admin" aria-expanded="false" aria-controls="ui-admin">
              <i class="icon-disc menu-icon"></i>
              <span class="menu-title">Administração</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-admin">
              <ul class="nav flex-column sub-menu">
              <?php if($controller->check_auth([4])) { ?> 
              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=listar');"><span >
                  <span class="menu-title">Usuários</span>
                </a>
              </li> 
              <?php } ?>

              <?php if($controller->check_auth([4])) { ?> 
              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=usuarioscontroller&method=listar');"><span>
                  <span class="menu-title">Controle de Acesso</span>
                </a>
              </li> 
              <?php } ?>
              
              <?php if($controller->check_auth([3,4])) { ?> 
              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=processoscontroller&method=listar');"><span>
                  <span class="menu-title">Processos</span>
                </a>
              </li> 
              <?php } ?>

              <?php if($controller->check_auth([3,4])) { ?> 
              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=statuscontroller&method=listar');"><span>
                  <span class="menu-title">Cadastro de Status</span>
                </a>
              </li> 
              <?php } ?>

              <?php if($controller->check_auth([4])) { ?> 
              <li class="nav-item">
                <a class="nav-link" href="#" style="cursor:pointer" onclick="go_link('/?controller=emailscontroller&method=listar');"><span>
                  <span class="menu-title">Log de Emails</span>
                </a>
              </li> 
              <?php } ?>

            </ul>
            </div>
            </li>    
          <?php } } ?>

            </ui>
            <div class="descricao container p-3">
            <section>
									<header class="minor">
										<b>Navegadores suportados</b>
									</header>
                  </br>
                      <div class=container>
                          <div class=row>
													    <div class="thumbnail" style="display: block;float:left;width:20px">
												      <a target="_blank" href="https://brave.com/">
													      <img src="images/brave.png" alt="Lights" style="width:20px">													
												      </a>
												  </div>
												  <div class="thumbnail" style="display: block;float:left;width:20px">
												      <a target="_blank" href="https://chrome.com/">
													        <img src="images/chrome.png" alt="Lights" style="width:20px">
												      </a>
												  </div>
												  <div class="thumbnail" style="display: block;float:left;width:20px">
												      <a target="_blank" href="https://www.microsoft.com/en-us/edge">
													      <img src="images/edge.png" alt="Lights" style="width:20px">
												      </a>
												  </div>
                          <div class="thumbnail" style="display: block;float:left;width:20px">
                          <a  target="_blank" href="https://firefox.com/">
                            <img src="images/firefox.png" alt="Lights" style="width:20px">
                          </a>
                          </div>
                          <div class="thumbnail" style="display: block;float:left;width:20px">
                          <a target="_blank" href="https://www.maxthon.com">
                            <img src="images/maxthom.png" alt="Lights" style="width:20px">
                          </a>
                          </div>
                          <div class="thumbnail" style="display: block;float:left;width:20px">
                          <a target="_blank" href="https://www.opera.com/pt-br">
                            <img src="images/opera.png" alt="Lights" style="width:20px">
                          </a>
                          </div>
                          <div class="thumbnail" style="display: block;float:left;width:20px">
                          <a target="_blank" href="https://www.apple.com/br/safari/">
                            <img src="images/safari.png" alt="Lights" style="width:20px">
                          </a>
                          </div>
												  <div class="thumbnail" style="display: block;float:left;width:20px">
												    <a target="_blank" href="https://vivaldi.com/">
												  	  <img src="images/vivaldi.png" alt="Lights" style="width:20px">
												    </a>
                          </div>
                          <div class="container">
                          <div class="row">
										      <font size=1>
                             </br><b>Esta aplicação web foi projetada e testada em navegadores modernos, utilizando HTML5, CCS3 e Javascript.</br></b>								
										        </br><font color=darkblue>Caso esteja utilizando um navegador antigo, sugiremos que atualize para alguma opção acima.</font>
      										</font>
                          </br>
                        </div>
                      </div>
                      </div> 

                      <div class="container">
                          <div class="row">
                              <font size=1></br>Desenvolvedor:</br></br>
                              <b>Prof. Rafael Buback Teixeira</b></br>
                              Engenharia de Produção</br>
                              IFES - Campus Cariacica</br>
                              rafael.teixeira@ifes.edu.br</font>
                          </div>
                      </div>
                      </br>
                      <div class="container">
                          <div class="row">
                              <div class="thumbnail" style="display: block;float:left;width:200px">
                                <font size=1>Mantenedor:</br></br></font>
												        <a target="_blank" href="https://gemad.net">
													        <img src="https://gemad.net/wp-content/uploads/2021/10/logo_gemad.png" alt="Lights" style="width:200px">
												        </a>
                              </div>
                          </div>
                      </div>            
              </div>
					  </section>
            </div>
      </nav>
      <div style="position:fixed;right:10px;top:60px;z-index:999999999">
    </br>
    <div id="msgs" ><?=$msgs?></div>
        <script>
          //go_link('/?controller=mensageirocontroller&method=listar_session&ajax=1','msgs',false);
        </script>   
    </div>
   
      <!-- partial -->
      <div class="main-panel justify-content-right">
        <div class="content-wrapper  justify-content-right">

          <div class="row mt-3  justify-content-right">
          <span id="conteudo" class="justify-content-right"  style="width:100%">
								<?php echo $result; ?>
								</span>            
          </div>
          
          
            </div>
            
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © GEMAD.NET 2021</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
          </div>
          <span class="text-muted d-block text-center text-sm-left d-sm-inline-block mt-2">Distributed By: <a href="https://www.themewagon.com/" target="_blank">ThemeWagon</a></span> 
        </footer>
        
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- base:js -->
    <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="regal/js/off-canvas.js"></script>
  <script src="regal/js/hoverable-collapse.js"></script>
  <script src="regal/js/template.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="regal/vendors/chart.js/Chart.min.js"></script>
  <script src="regal/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="regal/js/dashboard.js?<?=VERSION?>"></script>
  <!-- End custom js for this page-->
		<!-- Scripts -->
    <!--script src="assets/js/jquery.min.js?v=12"></script-->
			<script src="assets/js/browser.min.js?<?=VERSION?>"></script>
			<script src="assets/js/breakpoints.min.js?<?=VERSION?>"></script>
			<script src="assets/js/util.js?<?=VERSION?>"></script>
			<!--script src="assets/js/main.js"></script-->
		
<script>
			$(function(){
 var keyStop = {
   8: ":not(input:text, textarea, input:file, input:password)", // stop backspace = back
   13: "input:text, input:password", // stop enter = submit 

   end: null
 };
 $(document).bind("keydown", function(event){
  var selector = keyStop[event.which];

  if(selector !== undefined && $(event.target).is(selector)) {
      event.preventDefault(); //stop event
  }
  return true;
 });
});
</script>

<script type="text/javascript" src="/utils/vendor/mask2/jquery.mask.js"></script>

</body>
</html>
<?php 

function sanitize_output($buffer) {
	$search = [
		'/\>[^\S ]+/s',
		'/[^\S ]+\</s',
		'/(\s)+/s',
		'/<!--(.|\s)*?-->/'
	];
	
	$replace = [
		'>',
		'<',
		'\\1',
		''
	];
	
	$buffer = str_replace(array("\r", "\n"), '', preg_replace($search, $replace, $buffer));
	
	return $buffer;
}

$content = ob_get_contents();
ob_end_clean();
//$content = PHPWee\Minify::html($content);
echo $content;
?>



