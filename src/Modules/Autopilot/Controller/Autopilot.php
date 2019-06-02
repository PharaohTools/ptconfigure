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
                $autoPilot = $this->loadAutoPilot($thisModel->params, $pageVars);
                if ( $autoPilot!==null && $autoPilot!==false ) {
//                    echo "1" ;
                    $autoPilotExecutor = new \Controller\AutopilotExecutor();
                    // get params from the base model to inject into the loaded autopilot object
                    $autoPilot->params = $thisModel->params ;
                    if ($action =="test" || (isset($thisModel->params["test"]) && $thisModel->params["test"]==true) ) {
                        return $autoPilotExecutor->executeAuto($pageVars, $autoPilot, true); }
                    return $autoPilotExecutor->executeAuto($pageVars, $autoPilot, false); }
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
        $autoPilotFileRawPath = $autoPilotFileName;
        $defaultFolderToCheck = str_replace("src".DS."Controller",
            "build".DS."config".DS.PHARAOH_APP, dirname(__FILE__));
        $defaultName = $defaultFolderToCheck.DS.$autoPilotFileName.".php";
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($params);

        $paths = array(
            $autoPilotFileRawPath,
            $defaultName,
            "autopilot-".$defaultName,
            $autoPilotFilePath
        ) ;

        if (in_array(PHP_OS, array('Windows', 'WINNT'))) {
            $wpath1 = addslashes($autoPilotFilePath) ;
            $wpath1 = str_replace('^\\', '\\', $wpath1) ;
            $paths[] = $wpath1 ;
            $wpath2 = addslashes($autoPilotFileRawPath) ;
            $wpath2 = str_replace('^\\', '\\', $wpath2) ;
            $paths[] = $wpath2 ;
        }

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $logging->log("File $path found", "Autopilot") ;
//                $logging->log('$autoPilotFileName: '." $autoPilotFileName", "Autopilot") ;

                if (substr($autoPilotFileName, -4) == ".yml" || substr($autoPilotFileName, -5) == ".yaml") {
                    $logging->log('Loading YAML Autopilot from '." $autoPilotFileName", "Autopilot") ;
                    $dsl_au = $this->loadYAMLAutoPilot($autoPilotFileName, $pageVars) ;
                    if (is_object($dsl_au)) {
                        return $dsl_au ; }
                    else {
                        $logging->log("Unable to build object from YAML", "Autopilot", LOG_FAILURE_EXIT_CODE) ;
                        return false ; } }
                else if (substr($autoPilotFileName, -7) == "dsl.php") {
                    $dsl_au = $this->loadDSLAutoPilot($autoPilotFileName, $pageVars) ;
                    if (is_object($dsl_au)) {
                        return $dsl_au ; }
                    else {
                        $logging->log("Unable to build object from DSL", "Autopilot", LOG_FAILURE_EXIT_CODE) ;
                        return false ; } }
                else if (substr($autoPilotFileName, -4) == ".php") {
                    $au = $this->apLoader($path, $params);
                    if (is_object($au)) {
                        return $au ; }
                    else {
                        $logging->log("Unable to build object from PHP File", "Autopilot", LOG_FAILURE_EXIT_CODE) ;
                        return false ; } }
                else  {
                    $logging->log("Unable to use file at path: $path", "AutopilotDSL") ; } }
            else {

                $logging->log("File $path does not exist", "Autopilot", LOG_FAILURE_EXIT_CODE) ; }

        }

        $logging->log("No more paths to attempt to load", "Autopilot") ;
        $logging->log("Unable to find Default Autopilot Class", "Autopilot", LOG_FAILURE_EXIT_CODE) ;
        return false ;
    }

    protected function apLoader($autoPilotFileName, $params) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($params);

        $logging->log("Looking for Default Autopilot file $autoPilotFileName", "Autopilot") ;
        require_once $autoPilotFileName;
        $logging->log("Looking for Default Autopilot Class", "Autopilot") ;
        if (class_exists('\Core\AutoPilotConfigured')) {
            $autoPilot = new \Core\AutoPilotConfigured($params) ;
            return $autoPilot ; }

        $bn = basename( $autoPilotFileName ) ;
        $fname = str_replace(".php", "", $bn);
        $c2c = '\Core\\'.$fname;

        if ($fname != "Autopilot" && $fname != "autopilot" && class_exists($c2c)) {
            $autoPilot = new $c2c($params) ;
            return $autoPilot; }
        return false ;
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

    private function loadYAMLAutoPilot($filename, $pageVars){
        $dslModel = $this->getModelAndCheckDependencies("AutopilotYAML", $pageVars) ;
        $autoPilotReturn = $dslModel->loopOurYAMLFile($filename) ;
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