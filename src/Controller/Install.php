<?php

Namespace Controller ;

class Install extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];

        if ($action=="dev-client") {

          $install = new \Controller\DevClient();
          return $install->execute($pageVars);}

        if ($action=="dev-server") {

          $install = new \Controller\DevClient();
          return $install->execute($pageVars);}

        if ($action=="test-server") {

          $install = new \Controller\DevClient();
          return $install->execute($pageVars);}

        if ($action=="git-server") {

          $install = new \Controller\DevClient();
          return $install->execute($pageVars);}

        if ($action=="production") {

          $install = new \Controller\DevClient();
          return $install->execute($pageVars);}

        if ($action=="autopilot") {

            $autoPilotType= (isset($pageVars["route"]["extraParams"][0])) ? $pageVars["route"]["extraParams"][0] : null;

            if (isset($autoPilotType) && strlen($autoPilotType)>0 ) {

                $autoPilotFile = getcwd().'/'.escapeshellcmd($autoPilotType);
                $autoPilot = $this->loadAutoPilot($autoPilotFile);

                if ( $autoPilot!==null ) {

                    $phpUnitModel = new \Model\PHPUnit();
                    $this->content["phpUnitInstallResult"] = $phpUnitModel->runAutoPilotPHPAppInstall($autoPilot);
                    if ($autoPilot->phpUnitInstallExecute && $this->content["phpUnitInstallResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot PHPUnit Install Broken";
                        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);  }

                }

                else {
                    $this->content["autoPilotErrors"]="Auto Pilot couldn't load"; } }

            else {
                $this->content["autoPilotErrors"]="Auto Pilot not defined";  } }

        else {
            $this->content["autoPilotErrors"]="No Action"; }

        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);

    }

    private function loadAutoPilot($autoPilotFile){
        if (file_exists($autoPilotFile)) {
            include_once($autoPilotFile); }
        $autoPilot = (class_exists('\Core\AutoPilotConfigured')) ? new \Core\AutoPilotConfigured() : null ;
        return $autoPilot;
    }

}