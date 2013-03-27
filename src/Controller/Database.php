<?php

Namespace Controller ;

class Database extends Base {

    private $availPlats = array("drupal", "d7", "d6", "drupal6", "drupal7", "gcfw", "gcfw2", "php");

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];
        if ($action=="configure" || $action== "config" || $action== "conf") {
            $platform = (isset($pageVars["route"]["extraParams"][0])) ? $pageVars["route"]["extraParams"][0] : false;
            if (isset($platform) && in_array($platform, $this->availPlats)) {
                $dbConfigureModel = new \Model\DBConfigure($platform);
                $this->content["dbResult"] = $dbConfigureModel->askWhetherToConfigureDB();
                return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
            $this->content["messages"][] = "Invalid DB Platform";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);  }
        else if ($action=="reset") {
            $platform = (isset($pageVars["route"]["extraParams"][0])) ? $pageVars["route"]["extraParams"][0] : false;
            if (isset($platform) && in_array($platform, $this->availPlats)) {
                $dbConfigureModel = new \Model\DBConfigure($platform);
                $this->content["dbResult"] = $dbConfigureModel->askWhetherToResetDBConfiguration();
                return array ("type"=>"view", "view"=>"database", "pageVars"=>$this->content); }
            $this->content["messages"][] = "Invalid DB Platform";
            return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);  }
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