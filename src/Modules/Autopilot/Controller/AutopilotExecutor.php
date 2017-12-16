<?php

Namespace Controller ;

use Core\View;

class AutopilotExecutor extends Base {

    protected $liRay ;

    public function executeAuto($pageVars, $autopilot, $test = false ) {

        $thisModel = $this->getModelAndCheckDependencies("Autopilot", $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $this->content["package-friendly"] = ($test) ? "Autopilot Test Suite" : "Autopilot" ;

        $this->registeredModels = $autopilot->steps ;
        $res1 = $this->checkForRegisteredModels($thisModel->params);
        if ($res1 !== true) {
            $this->content["result"] = false ;
            return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content); }

        $res2 = ($test) ?
            $this->executeMyTestsAutopilot($autopilot, $thisModel->params):
            $this->executeMyRegisteredModelsAutopilot($autopilot, $thisModel->params, $thisModel);

        $this->content["result"] = $res2 ;
        return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

    protected function executeMyRegisteredModelsAutopilot($autoPilot, $autopilotParams, $thisModel) {
        $dataFromThis = array();
        if (isset($autoPilot->steps) && is_array($autoPilot->steps) && count($autoPilot->steps)>0) {
            $steps = $this->orderSteps($autoPilot->steps);
//            var_dump("after order:", $steps) ;
            $steps = $this->expandLoops($steps, $thisModel);
//            var_dump("after expand:", $steps) ;
            $registered_vars = array() ;

            $counter = 0 ;
            foreach ($steps as $modelArray) {

                $logFactory = new \Model\Logging() ;
                $logging = $logFactory->getModel($thisModel->params) ;
                $autoFactory = new \Model\Autopilot() ;
                $mod_ray_is = array_keys($modelArray) ;
                $mod_is = $mod_ray_is[0] ;
                $act_ray_is = array_keys($modelArray[$mod_is]) ;
                $act_is = $act_ray_is[0] ;
                if (count($registered_vars) > 0) {
                    foreach ($registered_vars as $registered_var_key => $registered_var_value) {
                        $thisModel->params[$registered_var_key] = $registered_var_value ;
                        $modelArray[$mod_is][$act_is][$registered_var_key] = $registered_var_value ;
                    }
                }

                $autoModel = $autoFactory->getModel($thisModel->params, "Default") ;
                $name_or_mod = $this->getNameOrMod($modelArray, $autoModel) ;
                $label = (isset($name_or_mod["step-name"])) ? "Label: {$name_or_mod["step-name"]}" : "" ;
                if (strlen($label) > 0) { $logging->log("{$label}", "Autopilot") ; }
                $module = (isset($name_or_mod["module"])) ? "Module: {$name_or_mod["module"]}" : "" ;
                if (strlen($module) > 0) { $logging->log("{$module}", "Autopilot") ; }
                $should_run = $this->onlyRunWhen($modelArray, $autoModel) ;
                if (isset($name_or_mod["step-name"]) || isset($name_or_mod["module"])) { echo "" ; }

                $modParams = $this->getModParamsFromArray($modelArray);
//                var_dump('modray:', $modelArray, $autopilotParams) ;

                if ($should_run["should_run"] == false) {
                    $step_out["status"] = true ;
                    $step_out["out"] = "No need to run this step" ; }
                else {
                    ob_start() ;
                    $step_out = $this->executeStep($modelArray, $autopilotParams) ;
                    $raw_out = ob_get_clean() ;
                    echo $raw_out ;
                    if (isset($modParams["register"])) {
                        $reg = trim($modParams["register"],'"') ;
                        $reg = trim($reg,"'") ;
                        $logging->log("Registering result of step as a new variable, named {$reg}.", "Autopilot") ;
                        $raw_out = trim($raw_out) ;
                        $lines = explode("\n", $raw_out) ;
                        $line_count = count($lines) - 1 ;
                        unset($lines[$line_count]) ;
                        $raw_without_end = implode( "\n", $lines) ;
                        $registered_vars[$reg] = $raw_without_end ;
//                        var_dump('new vo:', $raw_without_end, $registered_vars) ;
                    }
                }

                if (isset($step_out["status"]) && $step_out["status"]==false ) {
                    $step_out["error"] = "Received exit code: ".\Core\BootStrap::getExitCode();
                    if (isset($modParams["ignore_errors"])) {
                        $logging->log("Ignoring errors for this step. Setting Current Runtime Status to OK.", "Autopilot") ;
                        \Core\BootStrap::setExitCode(0) ; }
                    else {
                        $dataFromThis[] = $step_out ;
                        return $dataFromThis ; } }

                $dataFromThis[] = $step_out ;
                echo "\n\n" ;

                $counter ++ ; } }
        else {
            \Core\BootStrap::setExitCode(1);
            $step = array() ;
            $step["out"] = "No Steps defined in autopilot";
            $step["status"] = false ;
            $step["error"] = "Received exit code: 1 " ;
            $dataFromThis[] = $step ;  }
        return $dataFromThis ;
    }

    protected function onlyRunWhen($current_params, $autoModel) {
//        echo "Only running not_" ;
        $mod_ray_is = array_keys($current_params) ;
        $mod_is = $mod_ray_is[0] ;
        $act_ray_is = array_keys($current_params[$mod_is]) ;
        $act_is = $act_ray_is[0] ;
        if (isset($current_params[$mod_is][$act_is]["when"]) && isset($current_params[$mod_is][$act_is]["equals"])) {
            $logFactory = new \Model\Logging() ;
            $logging = $logFactory->getModel(array(), "Default") ;
            $name_or_mod = $this->getNameOrMod($current_params, $autoModel) ;
            $module = (isset($name_or_mod["module"])) ? " Module: {$name_or_mod["module"]}" : "" ;
            $name_text = (isset($name_or_mod["step-name"])) ? " Name: {$name_or_mod["step-name"]}" : "" ;
            $logging->log("When Equals Condition found for Step {$module}{$name_text}", "Autopilot") ;
            $when_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["when"]) ;
            $equals_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["equals"]) ;
//            var_dump($when_result, $equals_result) ;
            $when_text = ( ($when_result == $equals_result) && ($when_result != "") ) ? "Do Run" : "Don't Run" ;
            $when_bool = ( ($when_result == $equals_result) && ($when_result != "") ) ? true : false ;
            $logging->log("When Equals Condition evaluated to {$when_text}", "Autopilot") ;
            $return_stat["should_run"] = $when_bool ; }
        else if (isset($current_params[$mod_is][$act_is]["when"])) {
            $logFactory = new \Model\Logging() ;
            $logging = $logFactory->getModel(array(), "Default") ;
            $name_or_mod = $this->getNameOrMod($current_params, $autoModel) ;
            $module = (isset($name_or_mod["module"])) ? " Module: {$name_or_mod["module"]}" : "" ;
            $name_text = (isset($name_or_mod["step-name"])) ? " Name: {$name_or_mod["step-name"]}" : "" ;
            $logging->log("When Condition found for Step {$module}{$name_text}", "Autopilot") ;
            $when_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["when"]) ;
            $when_text = ( ($when_result == true) && ($when_result != "")) ? "Do Run" : "Don't Run" ;
            $logging->log("When Condition evaluated to {$when_text}", "Autopilot") ;
            $return_stat["should_run"] = $when_result ; }
        else if ((isset($current_params[$mod_is][$act_is]["not_when"])) && (isset($current_params[$mod_is][$act_is]["equals"]))) {
            $logFactory = new \Model\Logging() ;
            $logging = $logFactory->getModel(array(), "Default") ;
            $name_or_mod = $this->getNameOrMod($current_params, $autoModel) ;
            $module = (isset($name_or_mod["module"])) ? " Module: {$name_or_mod["module"]}" : "" ;
            $name_text = (isset($name_or_mod["step-name"])) ? " Name: {$name_or_mod["step-name"]}" : "" ;
            $logging->log("Not When Equals Condition found for Step {$module}{$name_text}", "Autopilot") ;
            $not_when_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["not_when"]) ;
            $equals_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["equals"]) ;
//            var_dump($not_when_result, $equals_result) ;
            $not_when_text = ( $not_when_result != $equals_result ) ? "Do Run" : "Don't Run" ;
            $logging->log("Not When Equals Condition evaluated to {$not_when_text}", "Autopilot") ;
//            $not_when_text = ($not_when_result == true) ? "Do Run" : "Don't Run" ;
            $not_when_bool = ( $not_when_result != $equals_result ) ? true : false ;
            $return_stat["should_run"] = $not_when_bool ; }
        else if (isset($current_params[$mod_is][$act_is]["not_when"])) {
            $logFactory = new \Model\Logging() ;
            $logging = $logFactory->getModel(array(), "Default") ;
            $name_or_mod = $this->getNameOrMod($current_params, $autoModel) ;
            $module = (isset($name_or_mod["module"])) ? " Module: {$name_or_mod["module"]}" : "" ;
            $name_text = (isset($name_or_mod["step-name"])) ? " Name: {$name_or_mod["step-name"]}" : "" ;
            $logging->log("Not When Condition found for Step {$module}{$name_text}", "Autopilot") ;
            $not_when_result = $autoModel->transformParameterValue($current_params[$mod_is][$act_is]["not_when"]) ;
            if (is_bool($not_when_result)) {
//               var_dump("one") ;
            }
            else {
//                var_dump("nwr1", $not_when_result) ;
                if (strlen($not_when_result)>0) {
//                    var_dump("two") ;
                    $not_when_result = false ; }
                else {
//                    var_dump("three") ;
                    $not_when_result = true ; }
            }
            $not_when_text = ($not_when_result == true) ? "Do Run" : "Don't Run" ;
            $logging->log("Not When Condition evaluated to {$not_when_text}", "Autopilot") ;

            $return_stat["should_run"] = $not_when_result ; }
        else {
            $return_stat["should_run"] = true ;  }
        return $return_stat ;
    }

    protected function getNameOrMod($stepDetails, $autoModel) {
        $name_or_mod = array() ;
        $currentControls = array_keys($stepDetails) ;
        $currentControl = $currentControls[0] ;
        $currentActions = array_keys($stepDetails[$currentControl]) ;
        $currentAction = $currentActions[0] ;
        $modParams = $stepDetails[$currentControl][$currentAction] ;
        $name_or_mod["module"] = $currentControl ;
        if (isset($modParams["step-name"])) {
            $name_or_mod["step-name"] = $autoModel->transformParameterValue($modParams["step-name"]) ; }
        if (isset($modParams["label"])) {
            $name_or_mod["step-name"] = $autoModel->transformParameterValue($modParams["label"]) ; }
        return $name_or_mod ;
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

    protected function shouldRegister($step) {
        if ( isset($step["register"]) ) { return $step["register"] ; }
        return false ;
    }

    protected function isPreRequisite($step) {
        if (isset($step["pre"]) && $step["pre"] == true) { return true ; }
        if (isset($step["prerequisite"]) && $step["prerequisite"] == true) { return true ; }
        return false ;
    }

    protected function isPostRequisite($step) {
        if (isset($step["post"]) && $step["post"] == true) { return true ; }
        if (isset($step["postrequisite"]) && $step["postrequisite"] == true) { return true ; }
        if (isset($step["handler"]) && $step["handler"] == true) { return true ; }
        return false ;
    }

    protected function expandLoops($steps, $thisModel) {
        $new_steps = array() ;
        foreach ($steps as $step) {
            $loopExpanded = $this->getLoopRay($step, $thisModel) ;
            $new_steps = array_merge($new_steps, $loopExpanded) ; }
        return $new_steps ;
    }

    private function getModParamsFromArray($modelArray) {
        $currentControls = array_keys($modelArray) ;
        $currentControl = $currentControls[0] ;
        $currentActions = array_keys($modelArray[$currentControl]) ;
        $currentAction = $currentActions[0] ;
        $modParams = $modelArray[$currentControl][$currentAction] ;
        return $modParams ;
    }

    protected function executeStep($modelArray, $autopilotParams) {
        $modParams = $this->getModParamsFromArray($modelArray);
        $modParams["layout"] = "blank" ;

        unset($autopilotParams["af"]) ;
        unset($autopilotParams["autopilot-file"]) ;

        $modParams = array_merge($modParams, $autopilotParams) ;

        $currentControls = array_keys($modelArray) ;
        $currentControl = $currentControls[0] ;
        $currentActions = array_keys($modelArray[$currentControl]) ;
        $currentAction = $currentActions[0] ;

        $modParams = $this->formatParams($modParams) ;
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

    protected function getLoopRay($modelArray, $thisModel) {
        $newParams = array();
        $currentControls = array_keys($modelArray) ;
        $currentControl = $currentControls[0] ;
        $currentActions = array_keys($modelArray[$currentControl]) ;
        $currentAction = $currentActions[0] ;
        $modParams = $modelArray[$currentControl][$currentAction] ;

        $resRay = array() ;
        foreach($modParams as $origParamKey => $origParamVal) {
            $resRay[] = $this->findLoopInParameterValue($origParamVal) ;
        }

        $logFactory = new \Model\Logging() ;
        $logging = $logFactory->getModel(array(), "Default") ;
        if (in_array(true, $resRay)) {
            $logging->log("Found loop for parameters in step", "Autopilot") ;
            if (!isset($this->liRay) || !is_array($this->liRay)) {
                $logging->log("Processing Loop Values", "Autopilot");
                $liRay = $this->getArrayOfLoopItems($modParams, $thisModel);
                $this->liRay = $liRay;
            }
            foreach ($this->liRay as $loop_iteration) {
                $logging->log("Adding loop with value {$loop_iteration}", "Autopilot") ;
                $tempParams = $modParams ;
                foreach($tempParams as $origParamKey => $origParamVal) {
                    $tempParams[$origParamKey] = $this->swapLoopPlaceholder($origParamVal, $loop_iteration) ;
                }
                $newParams[][$currentControl][$currentAction] = $tempParams ;
            }
        } else {
//            $logging->log("Found no loops for parameters in this step", "Autopilot") ;
            return array($modelArray) ;
        }

        if (count($newParams)>0) {
//            var_dump("np", $newParams) ;
            return $newParams ;
//            return $newParams ;
        } ;
//            var_dump("ma", array($modelArray)) ;
//        return $modelArray ;
        return array($modelArray) ;
    }

    protected function getArrayOfLoopItems($modParams, $thisModel) {
        if (isset($modParams["loop"])) {
            $autoFactory = new \Model\Autopilot() ;
            $autoModel = $autoFactory->getModel($thisModel->params, "Default") ;
            $loop_value = $modParams["loop"] ;
            $loop_value = $autoModel->transformParameterValue($loop_value) ;
            $litems =  explode(",", $loop_value) ;
            return $litems ; }
        $logFactory = new \Model\Logging() ;
        $logging = $logFactory->getModel(array(), "Default") ;
        $logging->log("Empty array of Loop items specified", "Autopilot", LOG_FAILURE_EXIT_CODE) ;
        return array() ;
    }

    public function findLoopInParameterValue($paramValue) {
        if (is_array($paramValue))  {
            var_dump($paramValue) ; }
        if ( (strpos($paramValue, '{{ loop }}') !== false) || (strpos($paramValue, '{{loop}}') !== false) ) {
            return true ;}
        return false ;
    }

    public function swapLoopPlaceholder($paramValue, $newVal) {
        $paramValue = str_replace('{{ loop }}', $newVal, $paramValue) ;
        $paramValue = str_replace('{{loop}}', $newVal, $paramValue) ;
        return $paramValue ;
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
//                $dataFromThis .= $this->executeControl($currentControl, $params);
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

    protected function formatParams($params) {
//        var_dump("pars:", $params) ;
//        $currentControls = array_keys($params) ;
//        $currentControl = $currentControls[0] ;
//        $currentActions = array_keys($params) ;
//        $currentAction = $currentActions[0] ;
//        $modParams = $params[$currentAction] ;
//        var_dump("mp:", $params) ;
        $newParams = array();
        foreach($params as $origParamKey => $origParamVal) {
//            var_dump('fp:',  $origParamKey , $origParamVal) ;
//            if (!is_array($origParamVal)) {
            $newParams[] = '--'.$origParamKey.'='.$origParamVal ;
//        }
//            else {
//                $a = $origParamVal;
//                $r=array();
//                array_walk($a, create_function('$b, $c', 'global $r; $r[]="$c:$b";'));
//                $newParamVal = implode(', ', $r);
//                $curp ='--'.$origParamKey.'='.$newParamVal ;
//                $newParams[] =  $curp ;
//                var_dump($curp); }
        }
        $newParams[] = '--yes' ;
        $newParams[] = "--hide-title=yes";
        $newParams[] = "--hide-completion=yes";
        return $newParams ;
    }

    public function executeControl($controlToExecute, $pageVars=null) {
        $control = new \Core\Control();
        $controlResult = $control->executeControl($controlToExecute, $pageVars);
//        var_dump("xc: ",  $controlResult) ;
        if ($controlResult["type"]=="view") {
            return $this->executeView( $controlResult["view"], $controlResult["pageVars"] ); }
        else if ($controlResult["type"]=="control") {
            $this->executeControl( $controlResult["control"], $controlResult["pageVars"] ); }
    }

    public function executeView($view, Array $viewVars) {
        $viewObject = new View();
        $templateData = $viewObject->loadTemplate ($view, $viewVars) ;
//        var_dump('td:', $view, $viewVars, $templateData) ;

//        @todo this should parse layouts properly but doesnt. so, templates only for autos for now
//        if ($view == "parallaxCli") {
//            var_dump("tdata: ", $templateData) ;
//            die() ;
//        }
//        $data = $viewObject->loadLayout ( "blank", $templateData, $viewVars) ;
        return $templateData ;
    }

}
