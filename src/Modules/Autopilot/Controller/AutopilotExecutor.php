<?php

Namespace Controller ;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot) {
      $this->content["package-friendly"] = "Autopilot";
      $this->registeredModels = $autopilot->steps ;
      $this->checkForRegisteredModels();
      $this->executeMyRegisteredModelsAutopilot($autopilot);
      return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

}
