<?php

Namespace Controller ;

class UserPage extends Base {

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        if ($this->content["userSession"]->getLoginStatus()==false ) {
            $this->content["messages"][] = "Please Login";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        return array ("type"=>"view", "view"=>"userPage", "pageVars"=>$this->content);
    }

}