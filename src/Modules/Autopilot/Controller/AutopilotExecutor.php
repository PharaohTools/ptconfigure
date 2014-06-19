<?php

Namespace Controller ;

use Core\View;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot) {
        $params = $pageVars["route"]["extraParams"];
        $this->content["package-friendly"] = "Autopilot";
        $this->registeredModels = $autopilot->steps ;
        $this->checkForRegisteredModels($params);
        $this->content["autoExec"] = $this->executeMyRegisteredModelsAutopilot($autopilot, $params);
        return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

    protected function executeMyRegisteredModelsAutopilot($autoPilot) {
        $dataFromThis = "";
        foreach ($autoPilot->steps as $modelArray) {
            $currentControls = array_keys($modelArray) ;
            $currentControl = $currentControls[0] ;
            $currentActions = array_keys($modelArray[$currentControl]) ;
            $currentAction = $currentActions[0] ;
            $modParams = $modelArray[$currentControl][$currentAction] ;
            $modParams = $this->formatParams($modParams) ;
            $params = array() ;
            $params["route"] =
            array(
                "extraParams" => $modParams ,
                "control" => $currentControl ,
                "action" => $currentAction ,
            ) ;
            $dataFromThis .= $this->executeControl($currentControl, $params);  }
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
