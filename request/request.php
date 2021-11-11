<?php
class Request
{
    protected $request;
    protected $get;
    protected $post;
    protected $cookie;
 
    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
    }
 
    public function __get($nome)
    {
        if (isset($this->request[$nome])) {
            return $this->request[$nome];
        }
        return false;
    }

    public function __get_all(){
        return $this->request;
    }

    public function __get_GET($nome)
    {
        if (isset($this->get[$nome])) {
            return $this->get[$nome];
        }
        return false;
    }
    
    public function __get_GET_all(){
        return $this->get;
    }

    
    public function __get_POST($nome)
    {
        if (isset($this->post[$nome])) {
            return $this->post[$nome];
        }
        return false;
    }

        
    public function __get_POST_all(){
        return $this->post;
    }

    
    public function __get_COOKIE($nome)
    {
        if (isset($this->cookie[$nome])) {
            return $this->cookie[$nome];
        }
        return false;
    }

    public function __get_COOKIE_all(){
        return $this->cookie;
    }
}

?>