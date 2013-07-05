<?php

Namespace Controller ;

class AppSettings extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $appSettingsModel = new \Model\AppSettings();

        if ($action=="set") {
          $this->content["configResult"] = $appSettingsModel->askWhetherToSetConfig();
          return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="get") {
            $this->content["configResult"] = $appSettingsModel->askWhetherToGetConfig();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="delete") {
            $this->content["configResult"] = $appSettingsModel->askWhetherToDeleteConfig();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        if ($action=="list") {
            $this->content["configResult"] = $appSettingsModel->askWhetherToListConfigs();
            return array ("type"=>"view", "view"=>"config", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}