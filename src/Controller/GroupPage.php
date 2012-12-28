<?php

Namespace Controller ;

class GroupPage extends Base {

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        if ($this->content["userSession"]->getLoginStatus()==false ) {
            $this->content["messages"][] = "Please Login";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        $role = new \Model\Role();
        $role->loadRoleByName("ViewGroupPage");
        if ($this->content["userData"]->hasRole($role) ) {
            return array ("type"=>"view", "view"=>"groupPageAllowed", "pageVars"=>$this->content);
        }
        return array ("type"=>"view", "view"=>"groupPageDenied", "pageVars"=>$this->content);
    }

}