<?php

Namespace Controller ;

use Core\BootStrap;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot) {
      $params = $pageVars["route"]["extraParams"];
      $this->content["package-friendly"] = "Autopilot";
      $this->registeredModels = $autopilot->steps ;
      $this->checkForRegisteredModels($params);
      $this->executeMyRegisteredModelsAutopilot($autopilot, $params);
      return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

    protected function executeMyRegisteredModelsAutopilot($autoPilot) {
        foreach ($autoPilot->steps as $modelArray) {
            $currentControls = array_keys($modelArray) ;
            $currentControl = $currentControls[0] ;
            $currentActions = array_keys($modelArray[$currentControl]) ;
            $currentAction = $currentActions[0] ;
            $params = array() ;
            $params["route"] =
            array(
                "extraParams" => $this->formatParams($modelArray[$currentControl][$currentAction]) ,
                "control" => $currentControl ,
                "action" => $currentAction ,
            ) ;
            $bootStrap = new BootStrap();
            // @todo instead of using the bootstrap version, maybe we can get an array of controller results
            // without parsed views it looks nicer
            $bootStrap->executeControl($currentControl, $params);
        }
    }

    private function formatParams($params) {
        $newParams = array();
        foreach($params as $origParamKey => $origParamVal) {
            $newParams[] = '--'.$origParamKey.'='.$origParamVal ; }
        $newParams[] = '--yes' ;
        return $newParams ;
    }

}
