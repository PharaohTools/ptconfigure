<?php

Namespace Controller ;

class Install extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];

        $actionsToClasses = array(
          "dev-client" => "DevClient",
          "dev-server" => "DevServer",
          "test-server" => "TestServer",
          "git-server" => "GitServer",
          "production" => "ProductionServer" );

        if (array_key_exists($action, $actionsToClasses)) {
          $className = '\\Controller\\'.$actionsToClasses[$action];
          $install = new $className();
          return $install->execute($pageVars);}

        if ($action=="autopilot") {
            $autoPilotFileName= (isset($pageVars["route"]["extraParams"][0]))
              ? $pageVars["route"]["extraParams"][0]
              : null;
            if (isset($autoPilotFileName) && strlen($autoPilotFileName)>0 ) {
              $autoPilot = $this->loadAutoPilot($autoPilotFileName);
                if ( $autoPilot!==null ) {
                  $install = new Autopilot();
                  return $install->execute($pageVars, $autoPilot); }
                else {
                    $this->content["autoPilotErrors"]="Auto Pilot couldn't load"; } }
            else {
                $this->content["autoPilotErrors"]="Auto Pilot not defined";  } }
        else {
            $this->content["autoPilotErrors"]="No Action"; }
        return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);
    }

    private function loadAutoPilot($autoPilotFileName){
        $autoPilotFile = getcwd().'/'.escapeshellcmd($autoPilotFileName);
        $defaultFolderToCheck = str_replace("src/Controller",
          "build/config/boxboss", dirname(__FILE__));
        $defaultName = $defaultFolderToCheck.'/'.$autoPilotFileName.".php";
        if (file_exists($autoPilotFile)) {
          require_once($autoPilotFile); }
        else if (file_exists($defaultName)) {
          include_once($defaultName); }
        $autoPilot = (class_exists('\Core\AutoPilotConfigured')) ?
          new \Core\AutoPilotConfigured() : null ;
        return $autoPilot;
    }

}