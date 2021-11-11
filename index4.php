<?php
require_once('conf.php');
require_once(GPATH.'controller'.S.'mensageirocontroller.php');
require_once(GPATH.'controller'.S.'usuarioscontroller.php');
require_once(GPATH.'request'.S.'session.php');

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

$request = new Request();
$ajax = 1;
$txt_controller = $request->__get('controller');
$txt_method = $request->__get('method');
if(empty($txt_controller)){	
	$txt_controller = 'homecontroller';
	$txt_method = "home";
	$ajax = 0;
}else{
	$ajax = 1;
}	

$result = "";

if(UsuariosController::is_logged())
if($txt_controller!=""){
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

if($method!=null && $method=='download'){
	echo $result;
	exit();
}

//if(is_numeric($request->__get('ajax')) && $request->__get('ajax')==1){
if($ajax == 1 || (isset($_REQUEST['noajax']) && $_REQUEST['noajax'] != 1)){
	if(UsuariosController::is_logged()){
		$id_user = UsuariosController::get_usuario()['id_user'];
	}else
		$id_user = 0;

	echo "<input type=hidden id='gpspg_id_user' value='".$id_user."'/>".$result;
	exit();
}
ob_start();
?>

<!DOCTYPE HTML>
<!--
	Editorial by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>GPS-PG - Gestão de Processos Seletivos de Pós-Graduação</title>
		<meta charset="utf-8" />
		<link rel="shortcut icon" type="image/jpg" href="/images/favicon.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
        <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB' crossorigin='anonymous' />
        <link href='https://use.fontawesome.com/releases/v5.1.0/css/all.css' rel='stylesheet' integrity='sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt' crossorigin='anonymous' />
        <link href='/utils/css/modal.css' rel='stylesheet' />
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>
        <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js' integrity='sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T' crossorigin='anonymous'></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
		<script src="/assets/js/captcha.js"></script>
		<script src="/ajax/ajax_submit.js?v=<?php echo date('H:i:s');?>"></script>
		<script src="https://cdn.tiny.cloud/1/l5hr79dltkjldhpincf3rzg93ch5tz7yjblanibpzinyjize/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>	

	</head>
	<body class="is-preload">
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
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">
							
							<!-- Header -->
						
								<header id="header">
									<div class="row">
								    <div class="col-12 col-md-12 col-lg-4 logo"><a href="/" class="logo"><img style="width:100%;min-width:250px;" src="images/logo2.png"/></a></div>
									<div class="col-12 col-md-12 col-lg-8" style="text-align:right">
									</br><a href="/" class="logo"><strong>GPS-PG</strong> - Sistema de Gestão de Processos Seletivos de Pós-Graduação</a>
									</br></br>
									<?php 
										if(isset($_SESSION['usuario']) && $_SESSION['usuario']!=NULL){
										echo $_SESSION['usuario']['txt_nome'];?>, bem vindo ao GPS-PG.
										</br></br>
										<a class="btn btn-info" onclick="go_link('/?controller=usuarioscontroller&method=registrar');"><font color=white>Perfil</font></a>
										<a class="btn btn-danger" onclick="go_link('/?controller=usuarioscontroller&method=sair');"><font color=white>Sair</font></a>											

										<?php }else{ ?>
											Olá, visitante! Faça o seu registro e/ou acesse sua conta.
										</br></br>
										<a class="btn btn-info" onclick="go_link('/?controller=usuarioscontroller&method=registrar1');"><font color=white>Novo Registro</font></a>
											<a class="btn btn-success" onclick="go_link('/?controller=usuarioscontroller&method=form_login');"><font color=white>Acessar...</font></a>											
										<?php } ?>
								
									</div>
								</header>
								<span id="msgs" style="position: fixed;top: 0;left: 0;z-index: 999999;width: 100%;height: 23px;">saasasasas</span>
								<script>
									//go_link('/?controller=mensageirocontroller&method=listar_session&ajax=1','msgs',false);
								</script>
								<span id="conteudo">
								<?php echo $result; ?>
								</span>

							<!-- Banner -->
								<!--section id="banner">
									<div class="content">
										<header>
											<h1>Hi, I’m Editorial<br />
											by HTML5 UP</h1>
											<p>A free and fully responsive site template</p>
										</header>
										<p>Aenean ornare velit lacus, ac varius enim ullamcorper eu. Proin aliquam facilisis ante interdum congue. Integer mollis, nisl amet convallis, porttitor magna ullamcorper, amet egestas mauris. Ut magna finibus nisi nec lacinia. Nam maximus erat id euismod egestas. Pellentesque sapien ac quam. Lorem ipsum dolor sit nullam.</p>
										<ul class="actions">
											<li><a href="#" class="button big">Learn More</a></li>
										</ul>
									</div>
									<span class="image object">
										<img src="images/pic10.jpg" alt="" />
									</span>
								</section-->

							<!-- Section -->
								<!--section>
									<header class="major">
										<h2>Erat lacinia</h2>
									</header>
									<div class="features">
										<article>
											<span class="icon fa-gem"></span>
											<div class="content">
												<h3>Portitor ullamcorper</h3>
												<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											</div>
										</article>
										<article>
											<span class="icon solid fa-paper-plane"></span>
											<div class="content">
												<h3>Sapien veroeros</h3>
												<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											</div>
										</article>
										<article>
											<span class="icon solid fa-rocket"></span>
											<div class="content">
												<h3>Quam lorem ipsum</h3>
												<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											</div>
										</article>
										<article>
											<span class="icon solid fa-signal"></span>
											<div class="content">
												<h3>Sed magna finibus</h3>
												<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											</div>
										</article>
									</div>
								</section-->

							<!-- Section -->
								<!--section>
									<header class="major">
										<h2>Ipsum sed dolor</h2>
									</header>
									<div class="posts">
										<article>
											<a href="#" class="image"><img src="images/pic01.jpg" alt="" /></a>
											<h3>Interdum aenean</h3>
											<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											<ul class="actions">
												<li><a href="#" class="button">More</a></li>
											</ul>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic02.jpg" alt="" /></a>
											<h3>Nulla amet dolore</h3>
											<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											<ul class="actions">
												<li><a href="#" class="button">More</a></li>
											</ul>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic03.jpg" alt="" /></a>
											<h3>Tempus ullamcorper</h3>
											<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											<ul class="actions">
												<li><a href="#" class="button">More</a></li>
											</ul>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic04.jpg" alt="" /></a>
											<h3>Sed etiam facilis</h3>
											<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											<ul class="actions">
												<li><a href="#" class="button">More</a></li>
											</ul>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic05.jpg" alt="" /></a>
											<h3>Feugiat lorem aenean</h3>
											<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											<ul class="actions">
												<li><a href="#" class="button">More</a></li>
											</ul>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic06.jpg" alt="" /></a>
											<h3>Amet varius aliquam</h3>
											<p>Aenean ornare velit lacus, ac varius enim lorem ullamcorper dolore. Proin aliquam facilisis ante interdum. Sed nulla amet lorem feugiat tempus aliquam.</p>
											<ul class="actions">
												<li><a href="#" class="button">More</a></li>
											</ul>
										</article>
									</div>
								</section-->

						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">

							<!-- Search -->
								<!--section id="search" class="alt">
									<form method="post" action="#">
										<input type="text" name="query" id="query" placeholder="Search" />
									</form>
								</section-->

							<!-- Menu -->
								<nav id="menu" style="font-size:16px">
									<ul>
										<li><a href="/">Homepage</a></li>
										<li><a onclick="go_link('/?controller=processoscontroller&method=listar_candidato');"><b>Processos Seletivos</b></a></li>
										<?php if(!UsuariosController::is_logged()){ ?>
										<li><a onclick="go_link('/?controller=usuarioscontroller&method=registrar1');"><span class="glyphicon glyphicon-plus"> Novo Cadastro </span></a></li>
										<li><a onclick="go_link('/?controller=usuarioscontroller&method=form_login');"><span class="glyphicon glyphicon-user"> Acessar Sistema</span></a></li>
										<li><a onclick="go_link('/?controller=usuarioscontroller&method=form_ativar');"><span class="glyphicon glyphicon-ok"> Ativar Conta </span></a></li>
										<?php }else{ ?>
											<li><a onclick="go_link('/?controller=usuarioscontroller&method=form_perfil	');"><span class="glyphicon glyphicon-lock"> Perfil de Usuário</span></a></li>												
											<li><a onclick="go_link('/?controller=usuarioscontroller&method=alterar_senha');"><span class="glyphicon glyphicon-lock"> Alterar Senha </span></a></li>												
										<?php } ?>										
									</ul>
								</nav>
								<?php if($controller->check_auth([1])) { ?>
								<nav id="menu" style="font-size:16px">
									<header class="major">
										<h2>Menu do Candidato2</h2>
									</header>
									<ul>
									<li><a onclick="go_link('/?controller=documentoscontroller&method=listar_ficha');"><span class="glyphicon glyphicon-list-alt"> Minhas inscrições </span></a></li>										
										<li><a onclick="go_link('/?controller=inscricaocontroller&method=listar_inscricao');"><span class="glyphicon glyphicon-list-alt"> Minhas inscrições </span></a></li>
										<li><a onclick="go_link('/?controller=usuarioscontroller&method=listar');"><span class="glyphicon glyphicon-flag"> Meus recursos </span></a></li>
									
										<!--li><a href="elements.html">Administrativo</a></li>
										<li>
											<span class="opener">Submenu</span>
											<ul>
												<li><a href="/?controller=usuarioscontroller&method=listar">Cadastro de Usuários</a></li>
												<li><a href="#">Lorem Dolor</a></li>
												<li><a href="#">Ipsum Adipiscing</a></li>
												<li><a href="#">Tempus Magna</a></li>
												<li><a href="#">Feugiat Veroeros</a></li>
											</ul>
										</li-->
									</ul>
								</nav>	
								<?php } ?>
								
								<?php if(!$controller->check_auth([1])) { ?>
								<?php if(UsuariosController::is_logged()){ ?>
								<nav id="menu" style="font-size:16px">
									<header class="major">
										<h2>Administração</h2>
									</header>
									<ul>
										<?php if($controller->check_auth([4])) { ?> <li><a onclick='go_link("/?controller=usuarioscontroller&method=listar");'><span class='glyphicon glyphicon-user'> Cadastro de Usuários </span></a></li> <?php } ?>
										<?php if($controller->check_auth([4])) { ?> <li><a onclick="go_link('/?controller=usuarioscontroller&method=listar');"><span class="glyphicon glyphicon-lock"> Controle de Acesso </span></a></li> <?php } ?>
										<?php if($controller->check_auth([3,4])) { ?> <li><a onclick="go_link('/?controller=processoscontroller&method=listar');"><span class="glyphicon glyphicon-plus"> Cadastro de Processos </span></a></li> <?php } ?>																	
										<?php if($controller->check_auth([3,4])) { ?> <li><a onclick="go_link('/?controller=statuscontroller&method=listar');"><span class="glyphicon glyphicon-plus"> Cadastro de Status </span></a></li> <?php } ?>																	
										<?php if($controller->check_auth([4])) { ?> <li><a onclick="go_link('/?controller=emailscontroller&method=listar');"><span class="glyphicon glyphicon-envelope"> Log de Emails </span></a></li> <?php } ?>																				
									</ul>
								</nav>
								<?php } ?>
								<nav id="menu" style="font-size:12px">
										<ul>
											<li><a onclick="go_link('/?controller=usuarioscontroller&method=form_login');"><span class="glyphicon glyphicon-info-sign"> Sobre o GPS.PG</span></a></li>
											<?php if(UsuariosController::is_logged()){ ?>
											<li><a onclick="go_link('/?controller=usuarioscontroller&method=sair');"><b><span class="glyphicon glyphicon-log-out"> Sair do sistema</span></b></a></li>
											<?php } ?>
										</ul>
								</nav>


								<?php } ?>


							<!-- Section -->
								<section>
									<header class="minor">
										<h4>Navegadores suportados</h4>
									</header>
									<table><tr><td>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://brave.com/">
													<img src="images/brave.png" alt="Lights" style="width:30px;height:22px">													
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://chrome.com/">
													<img src="images/chrome.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://www.microsoft.com/en-us/edge">
													<img src="images/edge.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a  target="_blank" href="https://firefox.com/">
													<img src="images/firefox.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://www.maxthon.com">
													<img src="images/maxthom.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://www.opera.com/pt-br">
													<img src="images/opera.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://www.apple.com/br/safari/">
													<img src="images/safari.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
												<div class="thumbnail" style="display: block;float:left;width:30px">
												<a target="_blank" href="https://vivaldi.com/">
													<img src="images/vivaldi.png" alt="Lights" style="width:30px;height:22px">
												</a>
												</div>
								</td></tr><tr><td>
										<font size=1>
										<b>Esta aplicação web foi projetada para navegadores modernos, utilizando tecnologias avançadas de HTML5, CCS3, Ajax JQuery, Javascript e bootstrap.</br></br> Foi amplamente testada em oito navegadores modernos. Não funciona em navegadores antigos. Qualquer dúvida, favor entrar em contato.</b>								
										</br></br>Caso esteja utilizando um navegador antigo, sugiremos que atualize para alguma opção acima.

										</font>
								</td></tr></table>								
									</section>
									<section>
									<header class="minor">
										<h4>Informações</h4>
									</header>
									<p>Desenvolvido por:</br>Prof. Dr. Rafael Buback Teixeira</p>
									<p>Applicação nativa, baseada em <i>Entity Framework<i> usando MCV, em linguagem PHP.</p>
									<p>Banco de dados: MySql</p>
									<p>Versão da aplicação: 0.1 (15/09/2021)</p>

									<ul class="contact">
										<li class="icon solid fa-envelope"><a href="#">suporte.ppgep@ifes.edu.br</a></li>
										<li class="icon solid fa-envelope"><a href="#">rafael.buback@ifes.edu.br</a></li>
										<li class="icon solid fa-home">Rod. Governador José Henrique Sette, 184, Itacibá - Cariacica/ES<br/>
										Insituto Federal do Espírito Santo</br>Campus Cariacica</li>
									</ul>
								</section>
								
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Untitled. All rights reserved. Demo Images: <a href="https://unsplash.com">Unsplash</a>. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
								</footer>

						</div>
					</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
			
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
<input type="text" name="field-name" data-mask="00/00/0000" />

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
