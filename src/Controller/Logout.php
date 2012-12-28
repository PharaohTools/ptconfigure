<?php

Namespace Controller ;
Use ECPForm\LoginForm ;

class Logout extends Base {

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        if ($this->content["userSession"]->getLoginStatus() ) {
            $this->content["userSession"]->logoutUserSession();
            $this->content["messages"][] = "You have been logged out successfully";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        $this->content["messages"][] = "You cannot log out as you are not logged in";
        return array ("type"=>"control", "view"=>"login", "pageVars"=>$this->content);
    }

}