 <?php 

class Mail
{
    /**
     * Additional parameters for sending the mail 
     */
    private $addparams = '';
    
    /**
     * Collection of all attachments.
     */
    private $attachments = [];
    /**
     * Collection of all BCC (Blind Copy Carbon) mail-addresses.
     */
    private $bcc = [];
    /**
     * Collection of all CC (Copy Carbon) mail-addresses.
     */
    private $cc = [];
    /**
     * The formatted content (HTML) of the mail.
     */
    private $contentHTML = '';
    /**
     * The plain content (non HTML) content of the mail.
     */
    private $contentPlain = '';
    /**
     * Collection of all receivers.
     */
    private $receivers = [];
    /**
     * The mail-address on which should be answered.
     */
    private $replyTo = '';
    /**
     * The sender of the mail.
     */
    private $sender = '';
    /**
     * The subject of the mail.
     */
    private $subject = '';
    /**
     * Configuration for smtp
     */
    private $smtp = [];
    /**
     * For add attachment
     * @param resource $file
     * @return void
     */      
    public function addAttachment($file) {
        $this->attachments = $file;
    }
    /**
     * For add a new BCC 
     * @param string $bcc
     * @return boolean
     */    
    public function addBcc($bcc){
        if($this->isValidEmail($bcc) === true){
            if(is_array($bcc,$this->bcc) === false){
               $this->bcc[] = $bcc;
            }
        }
        return false;
    }
    /**
     * For  add a new cc 
     * @param string $cc 
     * @return boolean
     */        
    public function addCc($cc){
        if($this->isValidEmail($cc) === true){
            if(is_array($cc,$this->cc) === false){
                $this->cc[] = $cc;
            }
        }
        return false;
    }    
    /**
     * For add reciever
     * @param string $email
     * @return boolean
     */    
    public function addReceiver($email){
        if($this->isValidEmail($email) === true){
            if(@is_array($email,$this->receivers) === false){
                $this->receivers[] = $email;
            }
        }
        return false;      
    }
    /**
     * For prepare an attachment for sending with mail.
     * @return mix-data
     */
    public function prepareAttachment($attachment)
    {
            if ($this->isFile($attachment) !== true) {
                return false;
            }     
            //http://php.net/manual/en/class.finfo.php            
            $fileInfo = new finfo(FILEINFO_MIME_TYPE);
            $fileType = $fileInfo->file($attachment);
            $file = fopen($attachment, "r");
            $fileContent = fread($file, filesize($attachment));
            $fileContent = chunk_split(base64_encode($fileContent));
            fclose($file);
            $msgContent = 'Content-Type: '.$fileType.'; name='.basename($attachment)."\r\n";
            $msgContent .= 'Content-Transfer-Encoding: base64'."\r\n";
            $msgContent .= 'Content-ID: <'.basename($attachment).'>'."\r\n";
            $msgContent .= "\r\n".$fileContent."\r\n\r\n";
            return $msgContent;
    }    
    /**
     * For send the mail.
     * @return boolean.
     */    
    public function send()
    {
        //Check if a sender is available.
        if(empty(trim($this->sender))) {
            return false;
        }
        if((is_array($this->receivers) === false) || (count($this->receivers) < 1)) {
            return false;
        }
        $receivers = implode(',', $this->receivers);
        if(!empty(trim($this->replyTo))) {
            $headers[] = 'Reply-To: '.$this->replyTo;
        } else {
            $headers[] = 'Reply-To: '.$this->sender;
        }
        if((is_array($this->bcc) === true) && (count($this->bcc) > 0)) {
            $headers[] = 'Bcc: '.implode(',', $this->bcc);
        }
        if((is_array($this->cc) === true) && (count($this->cc) > 0)) {
            $headers[] = 'Cc: '.implode(',', $this->cc);
        }        
        //Generate boundaries for mail content areas.
        $boundaryMessage = md5(rand().'message');
        $boundaryContent = md5(rand().'content');
        //Set the header informations of the mail.
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'X-Mailer: PHP/'.phpversion();
        $headers[] = 'Date: '.date('r', $_SERVER['REQUEST_TIME']);
        $headers[] = 'X-Originating-IP: '.$_SERVER['SERVER_ADDR'];
        $headers[] = 'Content-Type: multipart/related;boundary='.$boundaryMessage;
        $headers[] = 'Content-Transfer-Encoding: 8bit';
        $headers[] = 'From: '.$this->sender;
        $headers[] = 'Return-Path: '.$this->sender;
        //Start to generate the content of the mail.
        $msgContent = "\r\n".'--'.$boundaryMessage."\r\n";
        $msgContent .= 'Content-Type: multipart/alternative; boundary='.$boundaryContent."\r\n";
        if(!empty(trim($this->contentPlain))) {
            $msgContent .= "\r\n".'--'.$boundaryContent."\r\n";
            $msgContent .= 'Content-Type: text/plain; charset=ISO-8859-1'."\r\n";
            $msgContent .= "\r\n".$this->contentPlain."\r\n";
        }
        if(!empty(trim($this->contentHTML))) {
            $msgContent .= "\r\n".'--'.$boundaryContent."\r\n";
            $msgContent .= 'Content-Type: text/html; charset=ISO-8859-1'."\r\n";
            $msgContent .= "\r\n".$this->contentHTML."\r\n";
        }
        //Close the message area of the mail.
        $msgContent .= "\r\n".'--'.$boundaryContent.'--'."\r\n";
        foreach($this->attachments as $attachment) {
            $attachmentContent = $this->prepareAttachment($attachment);
            if($attachmentContent !== false) {
                $msgContent .= "\r\n".'--'.$boundaryMessage."\r\n";
                $msgContent .= $attachmentContent;
            }
        }
        //Close the area of the whole mail content.
        $msgContent .= "\r\n".'--'.$boundaryMessage.'--'."\r\n";
        if (!isset($this->smtp['status']) || $this->smtp['status'] === false) {
            $return = mail($receivers, $this->subject, $msgContent, implode("\r\n", $headers), $this->addparams);
        } else {
            $return = $this->sendSMPT($receivers,$this->sender,$msgContent);
        }
        return $return;
    }
    /**
     * Check is email is valid
     * @param string $email
     * @return boolean 
     */
    public function isValidEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        }
        if (filter_var($email, FILTER_SANITIZE_EMAIL) !== false) {
            return true;
        } else {
            return false;
        }
    }
    public function isSMTP(bool $status = false,array $config = []) {
        if ($status === true) {    
            $this->smtp = $config;
            $this->smtp['status'] = $status;
        } else {
            $this->smtp['status'] = $status;
        }
    }
    public function sendSMPT($to, $from, $message)
        {
        $host = $this->smtp['host'];
        $user = $this->smtp['user'];
        $pass = $this->smtp['pass'];
        $port = $this->smtp['port'];    
        if ($h = fsockopen($host, $port))
        {
            $data = array(
                0,
                "EHLO $host",
                'AUTH LOGIN',
                base64_encode($user),
                base64_encode($pass),
                "MAIL FROM: <$from>",
                "RCPT TO: <$to>",
                'DATA',
                $message
            );
            foreach($data as $c)
            {
                $c && fwrite($h, "$c\r\n");
                while(substr(fgets($h, 256), 3, 1) != ' '){}
            }
            fwrite($h, "QUIT\r\n");
            return fclose($h);
        }    
    }
    /**
     * For set additional parameter for the mail function (4th parameter).
     * @param string $parameter
     * @return void
     */
    public function setaddParams($params)
    {
        $this->addparams = $params;
    }
    
    /**
     * For the formatted content (HTML) of the mail.
     * @param string $content
     * @return void
     */
    public function setContentHTML($content)
    {
        $content = wordwrap($content, 60, "\n");
        $this->contentHTML = $content;
    }
    /**
     * For set the plain content of the mail.
     * @param string $content 
     * @return void
     */
    public function setContentPlain($content)
    {
        $content = wordwrap($content, 60, "\n");
        $this->contentPlain = $content;
    }    
    /**
     * For set reply_to in mail.
     * @param string $subject The subject of the mail.
     * @return boolean
     */        
    public function setReplyTo($email)
    {
        if ($this->isValidEmail($email) === true) {
            $this->reply_to = $email;
        } else {
            return false;
        }
    } 
    /**
     * For set sender in mail.
     * @param string $subject The subject of the mail.
     * @return boolean
     */    
    public function setSender($email)
    {
        if ($this->isValidEmail($email) === true) {
            $this->sender = $email;
        } else {
            return false;
        }
    }
    /**
     * For set subject in mail.
     * @param string $subject The subject of the mail.
     * @return boolean
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }   
    /**
     * Check file exists or not
     * @param resource $file
     * @return boolean
     */    
    public function isFile()
    {
        if (file_exists($file)) {
            return true;
        } else {
            return false;
        }    
    }
    /**
     * Clear all the information
     * @return void
     */
    public function clear()
    {
        unset($this->cc);
        unset($this->bcc);
        unset($this->receivers);
        unset($this->attachments);
        unset($this->contentHTML);
        unset($this->contentPlain);
    }     
}
