<?php

Namespace Controller ;
Use ECPForm\RegistrationForm ;

class Register extends Base {

    private $registrationForm;

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        if ($this->content["userSession"]->getLoginStatus() ) {
            $this->content["messages"][] = "You cant register, you're already logged in";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        if (!isset($this->content["formSet"])) { parent::initForm($pageVars, "registrationForm"); }
        if ( $this->content["formSet"]==true ) {
            if (!isset($this->registrationForm)) {$this->registrationForm = new \ECPForm\RegistrationForm(); }
            $this->content["formResult"] = $this->registrationForm->getValidationResult();
            $this->content["formRequest"] = $this->registrationForm->formRequest;
            if ($this->content["formResult"]["results"] == "true") {
                return array ("type"=>"view", "view"=>"registerSuccess", "pageVars"=>$this->content); } }
        return array ("type"=>"view", "view"=>"register", "pageVars"=>$this->content);
    }

}