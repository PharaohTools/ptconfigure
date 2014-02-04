<?php

Namespace Controller ;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot) {
      $this->content["package-friendly"] = "Autopilot";
      $this->registeredModels = $autopilot->steps ;
      // @todo check below works i added the param no checking
      $this->checkForRegisteredModels($this->registeredModels);
      $this->executeMyRegisteredModelsAutopilot($autopilot);
      return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

}
