<?php

Namespace Controller ;

class AutopilotExecutor extends Base {

    public function execute($pageVars, $autopilot) {
      $params = $pageVars["route"]["extraParams"];
      $this->content["package-friendly"] = "Autopilot";
      $this->registeredModels = $autopilot->steps ;
      $this->checkForRegisteredModels($params);
      $this->executeMyRegisteredModelsAutopilot($autopilot, $params);
      return array ("type"=>"view", "view"=>"autopilot", "pageVars"=>$this->content);
    }

}
