<?php

Namespace Controller ;

class DeveloperTools extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $thisModel = new \Model\DeveloperTools();

        if ($action=="install") {
          $this->content["appName"] = $thisModel->autopilotDefiner;
          $this->content["appInstallResult"] = $thisModel->askInstall();
          return array ("type"=>"view", "view"=>"appInstall", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}