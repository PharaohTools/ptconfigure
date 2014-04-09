<?php

Namespace Controller ;

class Autopilot extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

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

      else if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

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
        if (file_exists($autoPilotFileName)) {
            require_once($autoPilotFileName); }
        else if (file_exists($defaultName)) {
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
