<?php
    require_once(GPATH.'database'.S.'conexao.php');
    require_once(GPATH.'controller'.S.'controller.php');
    require_once(GPATH.'controller'.S.'fichacontroller.php');
    require_once(GPATH.'controller'.S.'usuarioscontroller.php');
    require_once(GPATH.'model'.S.'ficha.php');
    require_once(GPATH.'model'.S.'documentos.php');
    require_once(GPATH.'utils'.S.'util_local.php');
    

    class Validation_Classe{
        

        public static function exist_cep($validation){
            $cep = get_by_CEP($validation->value,true);
            if($cep['localidade']==false)
                $validation->errors[$validation->field] = 'O CEP informado é inexistente.';
            else
                return true;
        }

        public static function exist_usuario($validation){            
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare("SELECT * FROM tab_users WHERE NOT id_user = 0".$validation->id." AND txt_usuario LIKE '". $validation->value ."';");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $validation->errors[$validation->field] = 'O usuário informado já está em uso por outra pessoa.';
                }
            }
            return true;
        }

        
        public static function senha_confere($validation){            
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare("SELECT * FROM tab_users WHERE id_user = 0".$validation->id." AND txt_senha = '". hash('sha256',$validation->value) ."';");

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 0) {
                    $validation->errors[$validation->field] = 'Senha incorreta. Informe a senha correta para continuar.';
                }
            }
            return true;
        }


        public static function exist_email($validation){            
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare("SELECT * FROM tab_users WHERE NOT id_user = 0".$validation->id." AND txt_email = '". $validation->value ."';");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $validation->errors[$validation->field] = 'O e-mail informado já está em uso por outra pessoa.';
                }
            }
            return true;
        }

        public static function exist_cpf($validation){            
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare("SELECT * FROM tab_users WHERE NOT id_user = 0".$validation->id." AND txt_cpf = '". $validation->value ."';");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $validation->errors[$validation->field] = 'O CPF informado já está em uso por outra pessoa.';
                }
            }
            return true;
        }

        public static function validation_usuario($data){
            $val = new Validation();            
            $val->field('txt_email')->name('Email')->value($data['txt_email'])->id($data['id_user'])->funcao('exist_email')->pattern('email')->required();            
            $val->field('txt_usuario')->name('Usuario')->value($data['txt_usuario'])->id($data['id_user'])->funcao('exist_usuario')->pattern('username')->required();            
            if(!$data['id_user']>0 || ($data['id_user']>0 && !$data['txt_senha']=='')) $val->field('txt_senha')->name('Senha')->value($data['txt_senha'])->pattern('senha')->required();            
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_registro1($data){   
            $val = new Validation();      
            $val->field('txt_nome')->name('Nome Completo')->value($data['txt_nome'])->nome()->pattern('words')->required();
            $val->field('txt_email')->name('Email')->value($data['txt_email'])->id($data['id_user'])->funcao('exist_email')->pattern('email')->required();            
            $val->field('txt_usuario')->name('Usuario')->value($data['txt_usuario'])->id($data['id_user'])->funcao('exist_usuario')->pattern('username')->required();                        
            $val->field('txt_cpf')->name('CPF')->value($data['txt_cpf'])->funcao('exist_cpf')->cpf()->required();
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_perfil($data){
            $val = new Validation();
            
            $val->field('txt_nome')->name('Nome Completo')->value($data['txt_nome'])->pattern('words')->required();            
            $val->field('txt_email')->name('Email')->value($data['txt_email'])->id($data['id_user'])->funcao('exist_email')->pattern('email')->required();                        
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_upload($data){
            $val = new Validation();
        
            $val->field('id_classe')->name('TIPO DE DOCUMENTO')->value($data['id_classe'])->check_classe_num($data['id_ficha'])->required();            
            $val->field('txt_filename')->name('ARQUIVO')->check_file($data['txt_filename_file'],$data['txt_filename_size'],$data['txt_filename_type']);
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_entregar($data){
            $val = new Validation();         
            $control = new UsuariosController();
            if(!UsuariosController::is_logged()){
                $val->errors['falha'] = 'Você precisa estar logado.';
                return $val->getErrors();
            }else{
                $user = UsuariosController::get_usuario()['id_user'];
            }

            $val->field('concordo')->name('De acordo (checkbox)')->value($data['concordo'])->required();            
            $val->field('txt_senha')->name('Senha')->id($user)->value($data['txt_senha'])->funcao('senha_confere')->required(); 
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_ativar($data){
            $val = new Validation();            
            $val->field('txt_email')->name('Email')->value($data['txt_email'])->pattern('email')->required();          
            $val->field('key')->name('Chave de ativação')->value($data['key'])->required();                        
            $val->field('txt_senha')->name('Senha')->value($data['txt_senha'])->pattern('senha')->required();           
            $val->field('txt_senha2')->name('Confirmação')->value($data['txt_senha2'])->equal($data['txt_senha'])->required();           
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_senha($data){
            $val = new Validation();            
            
            $val->field('txt_atual')->name('Senha atual')->value($data['txt_atual'])->required();
            $val->field('txt_senha')->name('Nova senha')->value($data['txt_senha'])->pattern('senha')->required();        
            $val->field('txt_senha2')->name('Confirmação de senha')->value($data['txt_senha2'])->equal($data['txt_senha'])->required();           
            
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_processo($data){
            $val = new Validation();            
            
            $val->field('txt_processo')->name('Título de Processo')->value($data['txt_processo'])->required();
            
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        

        public static function validation_status($data){
            $val = new Validation();            
            
            $val->field('txt_status')->name('Status')->value($data['txt_status'])->required();
            
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }
        
        
        public static function validation_cronograma($data){
            $val = new Validation();            
            
            //$val->field('txt_status')->name('Status')->value($data['txt_status'])->required();
            
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_documento($data){
            $val = new Validation();            
            
            //$val->field('txt_status')->name('Status')->value($data['txt_status'])->required();
            
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }

        public static function validation_ficha($data,$buscacep=true){
            if(session_id() == '') session_start();
            $val = new Validation();            
        
          
            if($data['txt_nome_mae']!="" && $data['txt_nome_mae']!=null)
            $val->field('txt_nome_mae')->name('Nome da mãe')->value($data['txt_nome_mae'])->pattern('words')->required();
            
            if($data['txt_nome_pai']!="" && $data['txt_nome_pai']!=null)
            $val->field('txt_nome_pai')->name('Nome do pai')->value($data['txt_nome_pai'])->pattern('words')->required();

            $val->field('txt_civil')->name('Estado Civil')->value((int)$data['txt_civil'])->min(1)->required();

            $val->field('txt_telefone')->name('telefone ou Celular')->value($data['txt_telefone'])->required();
            $val->field('txt_celular')->name('telefone ou celular')->value($data['txt_celular'])->required();

            if(!isset($val->errors['txt_telefone']) || !isset($val->errors['txt_celular'])){
                unset($val->errors['txt_telefone']);
                unset($val->errors['txt_celular']);
            }else{

            }

            $val->field('txt_nascimento')->name('Data nascimento')->value($data['txt_nascimento'])->isdate()->required();
            
            
            $val->field('txt_civil')->name('Estado Civil')->value((int)$data['txt_civil'])->min(1)->required();
            $val->field('txt_sexo')->name('Sexo')->value((int)$data['txt_sexo'])->min(1)->required();
            
            $val->field('txt_natural_pais')->name('"Naturalidade: País"')->value($data['txt_natural_pais'])->required();
            $val->field('txt_natural_estado')->name('"Naturalidade: Estado"')->value($data['txt_natural_estado'])->required();
            $val->field('txt_natural_cidade')->name('"Naturalidade: Cidade"')->value($data['txt_natural_cidade'])->required();

            $val->field('txt_rg')->name('RG')->value($data['txt_rg'])->pattern('int')->required();
            $val->field('txt_rg_orgao')->name('Orgão Expedidor')->value($data['txt_rg_orgao'])->required();
            $val->field('txt_rg_uf')->name('UF do RG')->value($data['txt_rg_uf'])->required();
            $val->field('txt_rg_expedicao')->name('Data de expedição do RG')->value($data['txt_rg_expedicao'])->isdate()->required();
            
            $val->field('txt_eleitor')->name('Título de Eleitor')->value($data['txt_eleitor'])->pattern('int')->required();
            $val->field('txt_eleitor_emissao')->name('Data de emissão do título de eleitor')->value($data['txt_eleitor_emissao'])->isdate()->required();
            $val->field('txt_eleitor_zona')->name('Zona')->value($data['txt_eleitor_zona'])->pattern('int')->required();
            $val->field('txt_eleitor_secao')->name('Seção')->value($data['txt_eleitor_secao'])->pattern('int')->required();
            $val->field('txt_eleitor_estado')->name('Estado do Título de Eleitor')->value($data['txt_eleitor_estado'])->required();
            
            $val->field('txt_cep')->name('CEP')->value($data['txt_cep'])->pattern('cep')->funcao('exist_cep')->required();

            
              //  $data['txt_logadouro'] = $cep['logradouro'];
              //  $data['txt_bairro'] = $cep['bairro'];
             //  $data['txt_cidade'] = $cep['localidade'];
            //$data['txt_estado'] = $cep['uf'];
            //    }
            
            if(isset($data['txt_logadouro']))
            $val->field('txt_logadouro')->name('Logadouro')->value($data['txt_logadouro'])->required();
            
            if(isset($data['txt_bairro']))
            $val->field('txt_bairro')->name('Bairro')->value($data['txt_bairro'])->required();

            $val->field('txt_numero')->name('Número')->value($data['txt_numero'])->required();

            
            //$val->field('txt_cidade')->name('Cidade')->value($data['txt_cidade'])->required();
            //$val->field('txt_estado')->name('Estado')->value($data['txt_estado'])->required();

            $val->field('id_modalidade_box')->name('Modalidade de Inscrição')->value($data['id_modalidade'])->required();

            $ficha = Ficha::find($data['id_ficha'],false);
            $photo = FichaController::check_photo($data["id"],$ficha);
            $change = $photo[0];
            $ficha->txt_photo = $photo[1];
            $old = $photo[2];
            $temp = $photo[3];
            $new = $photo[4];

            $val->field('txt_photo')->name('Foto 3x4')->value($photo[1])->required();
            if($val->isSuccess()){
                return null;
            }else{
                return $val->getErrors();
            }
        }
        
    }

    /**
     * Validation 
     *
     * Semplice classe PHP per la validazione.
     *
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @copyright (c) 2016, Davide Cesarano
     * @license https://github.com/davidecesarano/Validation/blob/master/LICENSE MIT License
     * @link https://github.com/davidecesarano/Validation
     */
     
    class Validation {
        
        /**
         * @var array $patterns
         */
        public $patterns = array(
            'uri'           => ['[A-Za-z0-9-\/_?&=]+','Uri para o campo {campo} inválida.'],
            'url'           => ['[A-Za-z0-9-:.\/_?&=#]+','URL para o campo {campo} inválida.'],
            'alpha'         => ['[\p{L}]+','O campo {campo} somente permite letras.'],
            'words'         => ['[\p{L}\s]+','O campo {campo} requer palavras.'],
            'alphanum'      => ['[\p{L}0-9]+','O campo {campo} somente permite letras e números.'],
            'int'           => ['[0-9]+','O campo {campo} deve ser um número inteiro.'],
            'float'         => ['[0-9\.,]+','O campo {campo} deve ser um número, podendo ser flutuante.'],
            'tel'           => ['[0-9+\s()-]+','O campo {campo} deve ser um telefone.'],
            'text'          => ['[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+','O campo {campo} deve conter um texto.'],
            'file'          => ['[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}','O campo {campo} deve conter um nome de arquivo válido.'],
            'folder'        => ['[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+','O campo {campo} deve conter um diretório válido.'],
            'address'       => ['[\p{L}0-9\s.,()°-]+','O campo {campo} deve conter um endereço, contendo letras, números e símbolos .,()°-'],
            'date_dmy'      => ['[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}','O campo {campo} deve ser uma data no formato dd\\mm\\aaaa'],
            'date_ymd'      => ['[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}','O campo {campo} deve ser uma data no formato aaaa\\mm\\dd'],
            'cep'           => ['[0-9]{2}\.[0-9]{3}\-[0-9]{3}','Informe um CEP no formato 00.000-000.'],
            'email'         => ['\S{1,}@\S{2,}\.\S{2,}','O campo {campo} deve conter um e-mail em formato válido'],
            'username'      => ['[A-Za-z][A-Za-z0-9]{3,20}','O usuário deve ter de 3 a 20 caracteres alphanuméricos, sem espaços ou símbolos.'],
            'senha'         => ['(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}','A senha deve ter de 8 a 20 caracteres com uma maiúscula, uma minúscula, um número e um caractere especial: @ $ ! % * ? &']
            
            //'email'         => ['[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]','O campo {campo} deve conter um e-mail em formato válido']
        );
        
        /**
         * @var array $errors
         */
        public $errors = array();
        
        /**
         * Nome del campo
         * 
         * @param string $name
         * @return this
         */
        public function name($name){
            
            $this->name = $name;
            return $this;
        
        }

        function CPF()
         {
             $cpf = $this->value;
            if (strpos($cpf, "-") !== false)
            {
            $cpf = str_replace("-", "", $cpf);
            }
            if (strpos($cpf, ".") !== false)
            {
                $cpf = str_replace(".", "", $cpf);
            }
            $sum = 0;
            $cpf = str_split( $cpf );
            $cpftrueverifier = array();
            $cpfnumbers = array_splice( $cpf , 0, 9 );
            $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
            for ( $i = 0; $i <= 8; $i++ )
            {
                $sum += $cpfnumbers[$i]*$cpfdefault[$i];
            }
            $sumresult = $sum % 11;  
            if ( $sumresult < 2 )
            {
                $cpftrueverifier[0] = 0;
            }
            else
            {
                $cpftrueverifier[0] = 11-$sumresult;
            }
            $sum = 0;
            $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
            $cpfnumbers[9] = $cpftrueverifier[0];
            for ( $i = 0; $i <= 9; $i++ )
            {
                $sum += $cpfnumbers[$i]*$cpfdefault[$i];
            }
            $sumresult = $sum % 11;
            if ( $sumresult < 2 )
            {
                $cpftrueverifier[1] = 0;
            }
            else
            {
                $cpftrueverifier[1] = 11 - $sumresult;
            }
            $returner = false;
            if ( $cpf == $cpftrueverifier )
            {
                $returner = true;
            }


            $cpfver = array_merge($cpfnumbers, $cpf);

            if ( count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0) )

            {

                $returner = false;

            }
            if (!$returner){
                $this->errors[$this->field] = "O CPF informado não é válido";
            }
            return $this;
        }

        public function field($field){
            
            $this->field = $field;
            return $this;
        
        }

        public function id($id){
            
            $this->id = $id;
            return $this;
        
        }
        
        /**
         * Valore del campo
         * 
         * @param mixed $value
         * @return this
         */
        public function value($value){
            
            $this->value = $value;
            return $this;
        
        }

        public function msg($value){
            $this->msg = $value;
            return $this;
        }
        
        /**
         * File
         * 
         * @param mixed $value
         * @return this
         */
        public function file($value){
            
            $this->file = $value;
            return $this;
        
        }
        
        public function nome(){
            $array = explode(' ',$this->value);
            $conta_palavras = 0;
            foreach($array as $a){
                if($a!="")  $conta_palavras++;
            }
            if($conta_palavras<=1){
                $this->errors[$this->field] = 'No campo "'.$this->name.'" informe nome(s) e sobrenome(s) separado(s) por espaço.';
            }
            return $this;
        }

        public function isdate(){
            if (DateTime::createFromFormat('Y-m-d', $this->value) == false) {
                $this->errors[$this->field] = 'O campo '.$this->name.' precisa ter uma data válida.';
            }
            return $this;
        }

        public function istime(){
            if (DateTime::createFromFormat('H:i:s', $this->value) == false) {
                $this->errors[$this->field] = 'O campo '.$this->name.' precisa ter um horário válido.';
            }
            return $this;
        }

        public function isdatetime($format = 'Y-m-d H:i:s'){
            if (DateTime::createFromFormat($format, $this->value) == false) {
                    $this->errors[$this->field] = 'O campo '.$this->name.' precisa ter uma data e hora válida.';
              }
              return $this;
        }

        /**
         * Pattern da applicare al riconoscimento
         * dell'espressione regolare
         * 
         * @param string $name nome del pattern
         * @return this
         */
        public function pattern($name){
            
            if($name == 'array'){
                
                if(!is_array($this->value)){
                    $this->errors[$this->field] = 'Formato campo '.$this->name.' não é válido.';
                }
            
            }else{
            
                $regex = '/^('.$this->patterns[$name][0].')$/u';
                if($this->value != '' && !preg_match($regex, $this->value)){
                    $this->errors[$this->field] = str_replace('{campo}',$this->name,$this->patterns[$name][1]);
                }
                
            }
            return $this;
            
        }

        public function funcao($funcao){                
            $retorna = eval('Validation_Classe::'.$funcao.'($this);');
            return $this;
        }
        
        /**
         * Pattern personalizzata
         * 
         * @param string $pattern
         * @return this
         */
        public function customPattern($pattern,$msg){
            
            $regex = '/^('.$pattern.')$/u';
            if($this->value != '' && !preg_match($regex, $this->value)){
                $this->errors[$this->field] = $msg;
            }
            return $this;
            
        }
        
        /**
         * Campo obbligatorio
         * 
         * @return this
         */
        public function required(){
            
            if((isset($this->file) && $this->file['error'] == 4) || ($this->value == '' || $this->value == null)){
                $this->errors[$this->field] = 'O campo '.$this->name.' é obrigatório.';                
            }            
            return $this;
            
        }

       
        /**
         * Lunghezza minima
         * del valore del campo
         * 
         * @param int $min
         * @return this
         */
        public function min($length){
            
            if(is_string($this->value)){
                
                if(strlen($this->value) < $length){
                    $this->errors[$this->field] = 'O campo '.$this->name.' não pode ter menos que '.$length.' caracteres.';
                }
           
            }else{
                
                if($this->value < $length){
                    $this->errors[$this->field] = 'O valor campo '.$this->name.' não pode ser inferior a '.$length;
                }
                
            }
            return $this;
            
        }
            
        /**
         * Lunghezza massima
         * del valore del campo
         * 
         * @param int $max
         * @return this
         */
        public function max($length){
            
            if(is_string($this->value)){
                
                if(strlen($this->value) > $length){
                    $this->errors[$this->field] = 'O campo '.$this->name.' não pode ter mais que '.$length.' caracteres.';
                }
           
            }else{
                
                if($this->value > $length){
                    $this->errors[$this->field] = 'O valor campo '.$this->name.' não pode ser superior a '.$length;
                }
                
            }
            return $this;
            
        }
        
        /**
         * Confronta con il valore di
         * un altro campo
         * 
         * @param mixed $value
         * @return this
         */
        public function equal($value){
        
            if($this->value != $value){
                if($this->name)
                $this->errors[$this->field] = 'O valor do campo '.$this->name.' não corresponde.';
            }
            return $this;
            
        }

        public function check_file($file,$size,$type){
        
            
            if($file=='' || $file==null){
                $this->errors[$this->field] = 'O arquivo precisa ser selecionado.';
            }else if(substr(strrchr($file,'.'),1)!='pdf'){
                $this->errors[$this->field] = 'O arquivo precisa ser PDF.';
            }else if($size>2097152){
                $this->errors[$this->field] = 'O arquivo não pode ser maior que 2mbytes.';
            }

            return $this;
            
        }
        
        public function check_classe_num($id_ficha){
            if($this->value>0){
                $classe = Documentos::find_classe($this->value);
                if(!isset($classe->id_classe)){
                    $this->errors[$this->field] = "Tipo de documento é requerido.";
                }else{
                    if(Documentos::count_classe($this->value,$id_ficha)>=$classe->num_docs){
                        $this->errors[$this->field] = "Não é pemitido carregar mais que ".$classe->num_docs." documento(s) PDF para esse tipo.";
                    }
                }
            }
            return $this;                
        }
        
        /**
         * Dimensione massima del file 
         *
         * @param int $size
         * @return this 
         */
        public function maxSize($size){
            
            if($this->file['error'] != 4 && $this->file['size'] > $size){
                $this->errors[$this->field] = 'O arquivo '.$this->name.' últrapassa o tamanho máximo de '.number_format($size / 1048576, 2).' MB.';
            }
            return $this;
            
        }
        
        /**
         * Estensione (formato) del file
         *
         * @param string $extension
         * @return this 
         */
        public function ext($extension){

            if($this->file['error'] != 4 && pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension){
                $this->errors[$this->field] = 'O arquivo '.$this->name.' não tem extensão '.$extension.'.';
            }
            return $this;
            
        }

        
        /**
         * Purifica per prevenire attacchi XSS
         *
         * @param string $string
         * @return $string
         */
        public function purify($string){
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
        
        /**
         * Campi validati
         * 
         * @return boolean
         */
        public function isSuccess(){
            if(empty($this->errors)) return true;
        }
        
        /**
         * Errori della validazione
         * 
         * @return array $this->errors
         */
        public function getErrors(){
            if(!$this->isSuccess()) return $this->errors;
        }
        
        /**
         * Visualizza errori in formato Html
         * 
         * @return string $html
         */
        public function displayErrors(){
            
            $html = '<ul>';
                foreach($this->getErrors() as $error){
                    $html .= '<li>'.$error.'</li>';
                }
            $html .= '</ul>';
            
            return $html;
            
        }
        
        /**
         * Visualizza risultato della validazione
         *
         * @return booelan|string
         */
        public function result(){
            
            if(!$this->isSuccess()){
               
                /*foreach($this->getErrors() as $error){
                    echo "$error\n";
                }*/
                return $this->getErrors();
                
            }else{
                return true;
            }
        
        }
        
        /**
         * Verifica se il valore è
         * un numero intero
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_int($value){
            if(filter_var($value, FILTER_VALIDATE_INT)) return true;
        }
        
        /**
         * Verifica se il valore è
         * un numero float
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_float($value){
            if(filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
        }
        
        /**
         * Verifica se il valore è
         * una lettera dell'alfabeto
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_alpha($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
        }
        
        /**
         * Verifica se il valore è
         * una lettera o un numero
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_alphanum($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
        }
        
        /**
         * Verifica se il valore è
         * un url
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_url($value){
            if(filter_var($value, FILTER_VALIDATE_URL)) return true;
        }
        
        /**
         * Verifica se il valore è
         * un uri
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_uri($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
        }
        
        /**
         * Verifica se il valore è
         * true o false
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_bool($value){
            if(is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
        }
        
        /**
         * Verifica se il valore è
         * un'e-mail
         *
         * @param mixed $value
         * @return boolean
         */
        public static function is_email($value){
            if(filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
        }
        
    }
?>