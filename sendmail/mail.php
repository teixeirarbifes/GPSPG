<?php

require_once(GPATH.'controller'.S.'usuarioscontroller.php');
require_once(GPATH.'request'.S.'session.php');
require_once(GPATH.'database'.S.'conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'phpMailer'.S.'Classes'.S.'Exception.php';
require_once 'phpMailer'.S.'Classes'.S.'PHPMailer.php';
require_once 'phpMailer'.S.'Classes'.S.'SMTP.php';

//cron_email();
function criar_email($to_email,$to_nome,$titulo,$modelo,$data){    
    $conta = conta_emails_enviados_hora();
    ob_start();
    include "phpMailer".S."modelo".S.$modelo.".php";
    $message = ob_get_contents();
    ob_end_clean();
    
    if($conta<200){
        $relatorio = send(-1,$to_email,$to_nome,$titulo,$message);
    	if($relatorio[0]=="OK"){
            $query = "INSERT INTO tab_emails (txt_para,txt_nome,txt_titulo,txt_conteudo,dt_criacao,dt_envio,id_status,txt_resposta) VALUES ('".$to_email."','".$to_nome."','".$titulo."','".$message."','".(new DateTime())->format('Y-m-d H:i:s')."','".(new DateTime())->format('Y-m-d H:i:s')."',2,'".$relatorio[1]."');";
            $status = 2;
        }else{
            $query = "INSERT INTO tab_emails (txt_para,txt_nome,txt_titulo,txt_conteudo,dt_criacao,id_status,txt_resposta) VALUES ('".$to_email."','".$to_nome."','".$titulo."','".$message."','".(new DateTime())->format('Y-m-d H:i:s')."',1,'".$relatorio[1]."');";
            $status = 1;
        }
    }else{
            $query = "INSERT INTO tab_emails (txt_para,txt_nome,txt_titulo,txt_conteudo,dt_criacao,id_status,txt_resposta) VALUES ('".$to_email."','".$to_nome."','".$titulo."','".$message."','".(new DateTime())->format('Y-m-d H:i:s')."',0,'');";
            $status = 0;
    }

	if ($conexao = Conexao::getInstance()) {            
		$stmt = $conexao->prepare($query);
		if ($stmt->execute()) {
			return $status;
		}else{
            if($status!=2)
			return 3;
		}		
	}   
	if($status!=2)
    return 3;
}

function conta_emails_enviados_hora(){
    $criacao = new DateTime();
	$ultima_hora = $criacao->sub(new DateInterval('PT1H'));

    $controller = new Controller();
    $conexao = Conexao::getInstance();        
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM tab_emails WHERE id_status = 2 AND dt_envio >= '".$ultima_hora->format('Y-m-d H:m:s')."';");
    if($stmt->execute()){
        $count = $stmt->fetchColumn();
    }
    $stmt->closeCursor();
    $conta = 0;
    if ($count) {
        $conta = (int) $count;
    }
    return $conta;
}

function enviar_emails(){

    $conta =  conta_emails_enviados_hora();
    $envios = 200 - $conta;
    
    if($envios > 0){
        $conexao = Conexao::getInstance();
        $stmt    = $conexao->prepare("SELECT * FROM tab_emails WHERE NOT id_status = 2;");
        $result  = array();
        if ($stmt->execute()) {
            
            $rs = $stmt->fetchAll();
        }
        if (count($rs) > 0) {
            foreach($rs as $email)
                send($email['id_email'],$email['txt_para'],$email['txt_nome'],$email['txt_titulo'],$email['txt_conteudo']);
        }
    }
}

function send($id_email,$to_email,$to_nome,$titulo,$message,$anexo = ""){   
        date_default_timezone_set('America/Sao_Paulo');      
        
        ob_start();
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug  = 1;
        $mail->Host = SMTP_SERVER;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER; //paste one generated by Mailtrap
        $mail->Password = SMTP_PASS; //paste one generated by Mailtrap
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;
        $mail->AuthType = 'PLAIN';
        
        $mail->setFrom(SMTP_MAIL, 'GPS-PG');
        //$mail->Sender = "suporte.ppgep@ifes.edu.br";
        $mail->ConfirmReadingTo = SMTP_CONFIRM;
        $mail->addAddress($to_email, $to_nome);
        //$mail->addCC('cc1@example.com', 'Elena');
        //$mail->addBCC('bcc1@example.com', 'Alex');
        $mail->Subject = '[GPS-PG] '.$titulo;
        
        //$mail->addAttachment('path/to/invoice1.pdf', 'invoice1.pdf');
        //$mysql_data = $mysql_row['blob_data'];
        //$mail->addStringAttachment($mysql_data, 'db_data.db'); 
        //$mail->addStringAttachment(file_get_contents($url), 'myfile.pdf');
        
        $mail->isHTML(true);
        
        $mail->addEmbeddedImage(GPATH.'images/logosmall.png.jpg', 'logo');
        $mail->CharSet="UTF-8";
        $mail->Body = '<img src="cid:logo">';
        
        $mail->Body = $message;
       
        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        
        if($mail->send()){
            $resposta = 'OK';
        }else{
            $resposta = 'NOK';
        }
        $result = ob_get_contents();
        ob_end_clean();
        
        return [$resposta,$result];    
}

?>