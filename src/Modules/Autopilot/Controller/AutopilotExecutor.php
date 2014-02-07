<?php

Namespace Controller ;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot) {
      $this->content["package-friendly"] = "Autopilot";
      $this->registeredModules = $this->getModulesFromSteps($autopilot->steps) ;
      // @todo check below works i added the param no checking
      $this->checkForRegisteredModules($pageVars["route"]["extraParams"], $this->registeredModules);
      $this->executeMyRegisteredModulesAutopilot($autopilot, $pageVars["route"]["extraParams"]);
      return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

    private function getModulesFromSteps($steps) {
      $modules = array();
      foreach ($steps as $step) {
        $stepName = array_keys($step);
        $modules[] = $stepName[0] ; }
      return $modules ;
    }
}
