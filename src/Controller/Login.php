<?php

Namespace Controller ;
Use ECPForm\LoginForm ;

class Login extends Base {

    public function execute($pageVars) {
        parent::initUser($pageVars);
        if ($this->content["user"]->getLoginStatus() ) {
            $this->content["messages"][] = "You can't login, you're already logged in";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        if ( $this->checkIfFormPosted("loginForm") ) {
            $loginForm = new LoginForm();
            $this->content["formResult"] = $loginForm->getValidationResult();
            $this->content["formRequest"] = $loginForm->formRequest;
            if ($this->content["formResult"]["results"] == "true") {
                $this->content["messages"][] = "You are now logged in";
                return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
            }}
        return array ("type"=>"view", "view"=>"login", "pageVars"=>$this->content);
    }

}