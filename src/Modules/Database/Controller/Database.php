<?php

Namespace Controller ;

class Database extends Base {

    public function execute($pageVars) {

        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="configure" || $action== "config" || $action== "conf") {
            $dbConfigureModel = new \Model\DBConfigure();
            $this->content["dbResult"] = $dbConfigureModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="reset") {
            $dbConfigureModel = new \Model\DBConfigure();
            $this->content["dbResult"] = $dbConfigureModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="install") {
            $dbInstallModel = new \Model\DBInstall();
            $this->content["dbResult"] = $dbInstallModel->askWhetherToInstallDB();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="drop") {
            $dbInstallModel = new \Model\DBInstall();
            $this->content["dbResult"] = $dbInstallModel->askWhetherToDropDB();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="useradd") {
            $dbInstallModel = new \Model\DBInstall();
            $this->content["dbResult"] = $dbInstallModel->askWhetherToAddUser();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        else if ($action=="userdrop") {
            $dbInstallModel = new \Model\DBInstall();
            $this->content["dbResult"] = $dbInstallModel->askWhetherToDropUser();
            return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
        $this->content["messages"][] = "Invalid DB Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}