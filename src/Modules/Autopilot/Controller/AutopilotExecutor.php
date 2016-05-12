<?php

Namespace Controller ;

use Core\View;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot, $test = false ) {
        $params = $pageVars["route"]["extraParams"];

        $thisModel = $this->getModelAndCheckDependencies("Autopilot", $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $this->content["package-friendly"] = ($test) ? "Autopilot Test Suite" : "Autopilot" ;
        $this->registeredModels = $autopilot->steps ;
        $res1 = $this->checkForRegisteredModels($params);
        if ($res1 !== true) {
            $this->content["result"] = false ;
            return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content); }

        $res2 = ($test) ?
            $this->executeMyTestsAutopilot($autopilot, $thisModel->params):
            $this->executeMyRegisteredModelsAutopilot($autopilot, $thisModel->params);

        $this->content["result"] = $res2 ;
        return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

    protected function executeMyRegisteredModelsAutopilot($autoPilot, $autopilotParams) {
        $dataFromThis = array();
        if (isset($autoPilot->steps) && is_array($autoPilot->steps) && count($autoPilot->steps)>0) {
            $steps = $this->orderSteps($autoPilot->steps);
            foreach ($steps as $modelArray) {
                $should_run = $this->onlyRunWhen($modelArray) ;
//                var_dump("should run is:", $should_run) ;
                if ($should_run["should_run"] != true) {
                    $step_out["status"] = true ;
                    $step_out["out"] = "No need to run this step" ; }
                else {
                    $step_out = $this->executeStep($modelArray, $autopilotParams) ; }
                if ($step_out["status"]==false ) {
                    $step_out["error"] = "Received exit code: ".\Core\BootStrap::getExitCode();
                    $dataFromThis[] = $step_out ;
                    return $dataFromThis ;  }
                $dataFromThis[] = $step_out ; } }
        else {
            \Core\BootStrap::setExitCode(1);
            $step = array() ;
            $step["out"] = "No Steps defined in autopilot";
            $step["status"] = false ;
            $step["error"] = "Received exit code: 1 " ;
            $dataFromThis[] = $step ;  }
        return $dataFromThis ;
    }

    protected function onlyRunWhen($current_params) {
//        echo "Only running when" ;
        $mod_ray_is = array_keys($current_params) ;
        $mod_is = $mod_ray_is[0] ;
        $act_ray_is = array_keys($current_params[$mod_is]) ;
        $act_is = $act_ray_is[0] ;
//        var_dump($current_params) ;
        if (isset($current_params[$mod_is][$act_is]["when"])) {
            $logFactory = new \Model\Logging() ;
            $logging = $logFactory->getModel(array(), "Default") ;
            $logging->log("When Condition found for Step", "Autopilot") ;
            $autoFactory = new \Model\Autopilot() ;
            $autoModel = $autoFactory->getModel(array(), "Default") ;
            $when_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["when"]) ;
            $logging->log("When Condition evaluated to {$when_result}", "Autopilot") ;

            $return_stat["should_run"] = $when_result ; }
        else {
            $return_stat["should_run"] = true ;  }
        return $return_stat ;
    }

    protected function orderSteps($steps) {
        $new_steps = array() ;
        // add pre
        foreach ($steps as $step) {
            if ($this->isPreRequisite($step)) {
                $new_steps[] = $step ; } }
        // add run
        foreach ($steps as $step) {
            if (!$this->isPreRequisite($step) && !$this->isPostRequisite($step)) {
                $new_steps[] = $step ; } }
        // add post
        foreach ($steps as $step) {
            if ($this->isPostRequisite($step)) {
                $new_steps[] = $step ; } }
        return $new_steps ;
    }

    protected function isPreRequisite($step) {
        if (isset($step["pre"]) && $step["pre"] == true) { return true ; }
        if (isset($step["prerequisite"]) && $step["prerequisite"] == true) { return true ; }
        return false ;
    }

    protected function isPostRequisite($step) {
        if (isset($step["post"]) && $step["post"] == true) { return true ; }
        if (isset($step["postrequisite"]) && $step["postrequisite"] == true) { return true ; }
        return false ;
    }

    protected function executeStep($modelArray, $autopilotParams) {

        $currentControls = array_keys($modelArray) ;
        $currentControl = $currentControls[0] ;
        $currentActions = array_keys($modelArray[$currentControl]) ;
        $currentAction = $currentActions[0] ;
        $modParams = $modelArray[$currentControl][$currentAction] ;
        $modParams["layout"] = "blank" ;
        $modParams = $this->formatParams(array_merge($modParams, $autopilotParams)) ;

        $params = array() ;
        $params["route"] =
            array(
                "extraParams" => $modParams ,
                "control" => $currentControl ,
                "action" => $currentAction ) ;
        $step = array() ;
        $step["out"] = $this->executeControl($currentControl, $params);
        $step["status"] = true ;
        $step["params"] = $params;

        if ( \Core\BootStrap::getExitCode() !== 0 ) {
            $step["status"] = false ;
            $step["error"] = "Received exit code: ".\Core\BootStrap::getExitCode();
            return $step ;  }

        return $step ;

    }

    protected function executeMyTestsAutopilot($autoPilot, $autopilotParams) {
        $dataFromThis = array() ;
        if (isset($autoPilot->tests) && is_array($autoPilot->tests) && count($autoPilot->tests)>0) {
            foreach ($autoPilot->tests as $modelArray) {
                $currentControls = array_keys($modelArray) ;
                $currentControl = $currentControls[0] ;
                $currentActions = array_keys($modelArray[$currentControl]) ;
                $currentAction = $currentActions[0] ;
                $modParams = $modelArray[$currentControl][$currentAction] ;
                $of = array("output-format" => "AUTO") ;
                $modParams = $this->formatParams(array_merge($modParams, $autopilotParams, $of)) ;
                $params = array() ;
                $params["route"] = array(
                    "extraParams" => $modParams ,
                    "control" => $currentControl ,
                    "action" => $currentAction ) ;
                $dataFromThis .= $this->executeControl($currentControl, $params);
                if ( \Core\BootStrap::getExitCode() !== 0 ) {
                        $dataFromThis .= "Received exit code: ".\Core\BootStrap::getExitCode();
                        break ; }
                $step = array() ;
                $step["out"] = $this->executeControl($currentControl, $params);
                $step["status"] = true ;
                $step["params"] = $params;
                if ( \Core\BootStrap::getExitCode() !== 0 ) {
                    $step["status"] = false ;
                    $step["error"] = "Received exit code: ".\Core\BootStrap::getExitCode();
                    $dataFromThis[] = $step ;
                    return $dataFromThis ;  }
                $dataFromThis[] = $step ; } }
        else {
            \Core\BootStrap::setExitCode(1);
            $step = array() ;
            $step["out"] = "No Tests defined in autopilot";
            $step["status"] = false ;
            $step["error"] = "Received exit code: 1 " ;
            $dataFromThis[] = $step ;  }
        return $dataFromThis ;
    }


    private function formatParams($params) {
        $newParams = array();
        foreach($params as $origParamKey => $origParamVal) {
            $newParams[] = '--'.$origParamKey.'='.$origParamVal ; }
        $newParams[] = '--yes' ;
        $newParams[] = "--hide-title=yes";
        $newParams[] = "--hide-completion=yes";
        return $newParams ;
    }

    public function executeControl($controlToExecute, $pageVars=null) {
        $control = new \Core\Control();
        $controlResult = $control->executeControl($controlToExecute, $pageVars);
        if ($controlResult["type"]=="view") {
            return $this->executeView( $controlResult["view"], $controlResult["pageVars"] ); }
        else if ($controlResult["type"]=="control") {
            $this->executeControl( $controlResult["control"], $controlResult["pageVars"] ); }
    }

    public function executeView($view, Array $viewVars) {
        $viewObject = new View();
        $templateData = $viewObject->loadTemplate ($view, $viewVars) ;
//        @todo this should parse layouts properly but doesnt. so, templates only for autos for now
//        if ($view == "parallaxCli") {
//            var_dump("tdata: ", $templateData) ;
//            die() ;
//        }
//        $data = $viewObject->loadLayout ( "blank", $templateData, $viewVars) ;
        return $templateData ;
    }

}
