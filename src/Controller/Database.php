<?php

Namespace Controller ;

class Database extends Base {

    private $availPlats = array("drupal", "d7", "d6", "drupal6", "drupal7",
      "gcfw", "gcfw2", "php", "joomla", "joomla15", "j15", "joomla30", "j30");

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
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
        $this->content["messages"][] = "Invalid DB Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}