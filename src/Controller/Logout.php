<?php

Namespace Controller ;
Use ECPForm\LoginForm ;

class Logout extends Base {

    public function execute($pageVars) {
        parent::initUser($pageVars);
        if ($this->content["user"]->getLoginStatus() ) {
            $user = new \Model\User();
            $user->logout();
            $this->content["messages"][] = "You have been logged out successfully";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        $this->content["messages"][] = "You cannot log out as you are not logged in.";
        return array ("type"=>"control", "view"=>"login", "pageVars"=>$this->content);
    }

}