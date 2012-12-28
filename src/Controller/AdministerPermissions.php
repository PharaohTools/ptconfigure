<?php

Namespace Controller ;

class AdministerPermissions extends Base {

    public function execute($pageVars) {
        if (!isset($this->content["userData"]) && !isset($this->content["userSession"])) {
            parent::initUser($pageVars); }
        if ($this->content["userSession"]->getLoginStatus() == false) {
            $this->content["messages"][] = "Please Login to access this page";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        if ($this->content["user"]->isAdmin() == false ) {
            $this->content["messages"][] = "You don't have permission to access this item";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); }
        $this->checkAndPerformSave();
        $this->loadPageUserOrGroupData();
        $this->loadAllRoles();
        return array ("type"=>"view", "view"=>"administerPermissions", "pageVars"=>$this->content);
    }

    private function checkAndPerformSave() {
        if ($this->content["route"]["action"] == "save") {
            /* todo save operation*/
            echo "save operation"; }
    }

    private function loadPageUserOrGroupData() {
        $isUserGroupSet =  (isset($_REQUEST["PermUserOrGroup"]) ) ? true : false ;
        $this->content["userOrGroup"] = ($isUserGroupSet && $_REQUEST["PermUserOrGroup"]=="group") ? "group" : "user" ;
        $this->content["pagination"] = new \Core\Pagination() ;
        if ($this->content["userOrGroup"] == "group") {
            $groupRepository = new \Model\GroupRepository();
            $this->content["groupData"] = $groupRepository->getItemsByPage($this->content["pagination"]);
            return; }
        $userRepository = new \Model\UserRepository();
        $this->content["userData"] = $userRepository->getItemsByPage($this->content["pagination"]);
    }

    private function loadAllRoles() {
        $roleRepository = new \Model\RoleRepository();
        $this->content["roles"] = $roleRepository->findAll();
    }

}