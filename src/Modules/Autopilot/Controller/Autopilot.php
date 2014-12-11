<?php

Namespace Controller ;

class Autopilot extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];
        if ( in_array($action, array("install", "execute", "x", "test") ) ) {
            if ( isset($thisModel->params["autopilot-file"]) && strlen($thisModel->params["autopilot-file"]) > 0 ) {
                $autoPilot = $this->loadAutoPilot($thisModel->params);
                if ( $autoPilot!==null ) {
                    $autoPilotExecutor = new \Controller\AutopilotExecutor();
                    // get params from the base model to inject into the loaded autopilot object
                    $autoPilot->params = $thisModel->params ;
                    if ($action =="test") { return $autoPilotExecutor->execute($pageVars, $autoPilot, true); }
                    return $autoPilotExecutor->execute($pageVars, $autoPilot); }
                else {
                    \Core\BootStrap::setExitCode(1);
                    $this->content["messages"][] = "There was a problem with the autopilot file specified"; } }
            else {
                \Core\BootStrap::setExitCode(1);
                $this->content["messages"][] = "Parameter --autopilot-file is required"; } }

        else if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        else {
            \Core\BootStrap::setExitCode(1);
            $this->content["messages"][] = "Invalid Action - Action does not Exist for Autopilot"; }

        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

    private function loadAutoPilot($params){
        $autoPilotFileName = escapeshellcmd($params["autopilot-file"]);
        $autoPilotFilePath = getcwd().DS.$autoPilotFileName;
        $defaultFolderToCheck = str_replace("src/Controller",
          "build/config/cleopatra", dirname(__FILE__));
        $defaultName = $defaultFolderToCheck.DS.$autoPilotFileName.".php";
        if (file_exists($autoPilotFileName)) {
            require_once($autoPilotFileName); }
        else if (file_exists($defaultName)) {
          include_once($defaultName); }
        else if (file_exists("autopilot-".$defaultName)) {
          include_once("autopilot-".$defaultName); }
        else if (file_exists($autoPilotFilePath)) {
            require_once($autoPilotFilePath); }
        // if a class exists by the name of the file use the name
        $bn = basename( $autoPilotFileName ) ;
        $fname = str_replace(".php", "", $bn);
        $c2c = '\Core\\'.$fname;
        if (class_exists($c2c)) {
            $autoPilot = new $c2c($params) ;
            return $autoPilot; }
        // else use default
        $autoPilot = (class_exists('\Core\AutoPilotConfigured')) ?
          new \Core\AutoPilotConfigured($params) : null ;
        return $autoPilot;
    }

}
