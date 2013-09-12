<?php

Namespace Controller ;

class SeleniumServer extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $thisModel = new \Model\SeleniumServer($pageVars["route"]["extraParams"]);

        if ($action=="install") {
          $this->content["params"] = $thisModel->params;
          $this->content["appName"] = $thisModel->autopilotDefiner;
          $this->content["appInstallResult"] = $thisModel->askInstall();
          return array ("type"=>"view", "view"=>"appInstall", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}