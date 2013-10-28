<?php

Namespace Controller ;

class Version extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        $versionModel = new \Model\Version($pageVars["route"]["extraParams"]);

        if ($action=="latest") {
            $this->content["versioningResult"] = $versionModel->askWhetherToVersionLatest($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content); }
        if ($action=="rollback") {
            $this->content["versioningResult"] = $versionModel->askWhetherToVersionRollback($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content); }
        if ($action=="specific") {
            $this->content["versioningResult"] = $versionModel->askWhetherToVersionSpecific($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content); }

        if ($action=="autopilot") {

            $autoPilotType= (isset($pageVars["route"]["extraParams"][0])) ? $pageVars["route"]["extraParams"][0] : null;
            if (isset($autoPilotType) && strlen($autoPilotType)>0 ) {
                $autoPilotFile = getcwd().DIRECTORY_SEPARATOR.escapeshellcmd($autoPilotType);
                $autoPilot = $this->loadAutoPilot($autoPilotFile);
                if ( $autoPilot!==null ) {
                    $this->content["versioningResult"] = $versionModel->runAutoPilotVersion($autoPilot);
                    if ($autoPilot["sshVersionExecute"] && $this->content["versionResult"] != "1") {
                        $this->content["autoPilotErrors"]="Auto Pilot Version Script Broken";
                        return array ("type"=>"view", "view"=>"version", "pageVars"=>$this->content);  } }
                else {
                        $this->content["autoPilotErrors"]="Auto Pilot not defined"; }  }
            else {
                $this->content["autoPilotErrors"]="Auto Pilot not defined"; }
            return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content); }

    }

    private function loadAutoPilot($autoPilotFile){
        if (file_exists($autoPilotFile)) {
            include_once($autoPilotFile); }
        $autoPilot = (class_exists('\Core\AutoPilot')) ? new \Core\AutoPilot() : null ;
        return $autoPilot;
    }

}