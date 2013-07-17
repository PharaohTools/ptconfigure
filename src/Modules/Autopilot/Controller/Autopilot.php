<?php

Namespace Controller ;

class Autopilot extends Base {

    public function execute($pageVars) {

      $isHelp = parent::checkForHelp($pageVars) ;
      if ( is_array($isHelp) ) {
        return $isHelp; }

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];
      $action = $pageVars["route"]["action"];

      if ($action=="install" || $action=="execute") {
        $autoPilotFileName= (isset($pageVars["route"]["extraParams"][0]))
          ? $pageVars["route"]["extraParams"][0]
          : null;
        if (isset($autoPilotFileName) && strlen($autoPilotFileName)>0 ) {
          $autoPilot = $this->loadAutoPilot($autoPilotFileName);
          if ( $autoPilot!==null ) {
            $autoPilotExecutor = new AutopilotExecutor();
            return $autoPilotExecutor->execute($pageVars, $autoPilot); }
          else {
            $this->content["messages"][] = "Auto Pilot couldn't load"; } }
        else {
          $this->content["messages"][] = "Auto Pilot not defined"; } }
      else {
        $this->content["messages"][] = "Invalid Action - Action does not Exist for Autopilot"; }

      return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

    private function loadAutoPilot($autoPilotFileName){
        $autoPilotFileName = escapeshellcmd($autoPilotFileName);
        $autoPilotFilePath = getcwd().'/'.$autoPilotFileName;
        $defaultFolderToCheck = str_replace("src/Controller",
          "build/config/cleopatra", dirname(__FILE__));
        $defaultName = $defaultFolderToCheck.'/'.$autoPilotFileName.".php";
        if (file_exists($defaultName)) {
          include_once($defaultName); }
        else if (file_exists("autopilot-".$defaultName)) {
          include_once("autopilot-".$defaultName); }
        else if (file_exists($autoPilotFilePath)) {
          require_once($autoPilotFilePath); }
        $autoPilot = (class_exists('\Core\AutoPilotConfigured')) ?
          new \Core\AutoPilotConfigured() : null ;
        return $autoPilot;
    }

}
