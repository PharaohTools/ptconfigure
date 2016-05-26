<?php

Namespace Controller ;

class Autopilot extends Base {

    public function execute($pageVars, $autopilot, $test = false ) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];
        if ( in_array($action, array("install", "execute", "x", "test") ) ) {
            if ( isset($thisModel->params["autopilot-file"]) && strlen($thisModel->params["autopilot-file"]) > 0 ) {
                $autoPilot = $this->loadAutoPilot($thisModel->params, $pageVars);
                if ( $autoPilot!==null ) {
//                    echo "1" ;
                    $autoPilotExecutor = new \Controller\AutopilotExecutor();
                    // get params from the base model to inject into the loaded autopilot object
                    $autoPilot->params = $thisModel->params ;
                    if ($action =="test" || (isset($thisModel->params["test"]) && $thisModel->params["test"]==true) ) { return $autoPilotExecutor->execute($pageVars, $autoPilot, true); }
                    return $autoPilotExecutor->execute($pageVars, $autoPilot); }
                else {
//                    echo "2" ;
                    \Core\BootStrap::setExitCode(1);
                    $this->content["messages"][] = "There was a problem with the autopilot file specified";
                    $this->content["messages"][] = "Attempted specifying {$thisModel->params["autopilot-file"]}" ;
                    return array ("type"=>"control", "control"=>"index", "pageVars"=>array_merge($pageVars, $this->content)); } }
            else {
//                echo "3" ;
                \Core\BootStrap::setExitCode(1);
                $this->content["messages"][] = "Parameter --autopilot-file is required";
                return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content); } }

        else if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        else {
            \Core\BootStrap::setExitCode(1);
            $this->content["messages"][] = "Invalid Action - Action does not Exist for Autopilot"; }

        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

    private function loadAutoPilot($params, $pageVars){
        $autoPilotFileName = escapeshellcmd($params["autopilot-file"]);
        $autoPilotFilePath = getcwd().DS.$autoPilotFileName;
        $defaultFolderToCheck = str_replace("src".DS."Controller",
            "build".DS."config".DS.PHARAOH_APP, dirname(__FILE__));
        $defaultName = $defaultFolderToCheck.DS.$autoPilotFileName.".php";
        if (file_exists($autoPilotFileName)) {
            $dsl_ext = substr($autoPilotFileName, -7) ;
            if ($dsl_ext=="dsl.php") {
                $dsl_au = $this->loadDSLAutoPilot($autoPilotFileName, $pageVars) ;
                if (is_object($dsl_au)) {
                    return $dsl_au ; }
                else {
                    $loggingFactory = new \Model\Logging();
                    $logging = $loggingFactory->getModel($params);
                    $logging->log("Unable to build object from DSL", "AutopilotDSL", LOG_FAILURE_EXIT_CODE) ;
                    return false ; } }
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
        if ($fname != "Autopilot" && $fname != "autopilot" && class_exists($c2c)) {
            $autoPilot = new $c2c($params) ;
            return $autoPilot; }
        // else use default
        $autoPilot = (class_exists('\Core\AutoPilotConfigured')) ?
            new \Core\AutoPilotConfigured($params) : null ;
        return $autoPilot;
    }

    private function loadDSLAutoPilot($filename, $pageVars){
        $dslModel = $this->getModelAndCheckDependencies("AutopilotDSL", $pageVars) ;
        $autoPilotReturn = $dslModel->loopOurDSLFile($filename) ;
        $autoPilotData = $this->transformData($autoPilotReturn["steps"]);
        $auto = new \StdClass() ;
        $auto->vars = $autoPilotReturn["vars"] ;
        $auto->steps = $autoPilotData ;
        return $auto ;
    }

    public function transformData($array_to_transform) {
        $all_steps = array() ;
        foreach ($array_to_transform as $single_step) {
            if (isset($single_step["params"]) && is_array($single_step["params"])) {
                $all_steps[] = array( "{$single_step["module"]}" =>
                array( "{$single_step["action"]}" => $single_step["params"] )) ; }
            else {
                $all_steps[] = array( "{$single_step["module"]}" => array( "{$single_step["action"]}")) ; } }
        return $all_steps ;
    }

}
