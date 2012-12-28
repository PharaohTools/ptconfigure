<?php

Namespace Controller ;
Use ECPForm\LoginForm ;

class Login extends Base {

    private $loginForm;

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        if ($this->content["userSession"]->getLoginStatus() ) {
            $this->content["messages"][] = "You can't login, you're already logged in";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        if (!isset($this->content["formSet"])) { parent::initForm($pageVars, "loginForm"); }
        if ( $this->content["formSet"]==true ) {
            if (!isset($this->loginForm)) {$this->loginForm = new \ECPForm\LoginForm(); }
            $this->content["formResult"] = $this->loginForm->getValidationResult();
            $this->content["formRequest"] = $this->loginForm->formRequest;
            if ($this->content["formResult"]["results"] == "true") {
                $this->content["messages"][] = "You are now logged in";
                return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); } }
        return array ("type"=>"view", "view"=>"login", "pageVars"=>$this->content);
    }

}