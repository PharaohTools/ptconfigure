<?php

Namespace Controller ;
Use ECPForm\RegistrationForm ;

class Register extends Base {

    public function execute($pageVars) {
        parent::initUser($pageVars);
        if ($this->content["user"]->getLoginStatus() ) {
            $this->content["messages"][] = "You cant register, you're already logged in";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        if ( $this->checkIfFormPosted("registrationForm") ) {
            $registrationForm = new RegistrationForm();
            $this->content["formResult"] = $registrationForm->getValidationResult();
            $this->content["formRequest"] = $registrationForm->formRequest;
            if ($this->content["formResult"]["results"] == "true") {
                return array ("type"=>"view", "view"=>"registerSuccess", "pageVars"=>$this->content);
            }
        }
        return array ("type"=>"view", "view"=>"register", "pageVars"=>$this->content);
    }

}