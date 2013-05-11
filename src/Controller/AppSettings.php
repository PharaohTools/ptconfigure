<?php

Namespace Controller ;

class AppSettings extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];
        $appSettingsModel = new \Model\AppSettings();

        if ($action=="set") {
            $this->content["configResult"] = $appSettingsModel->askWhetherToSetConfig();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="get") {
            $this->content["configResult"] = $appSettingsModel->askWhetherToGetConfig();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="list") {
            $this->content["configResult"] = $appSettingsModel->askWhetherToListConfigs();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}