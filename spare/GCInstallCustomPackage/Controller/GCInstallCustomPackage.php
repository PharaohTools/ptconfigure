<?php

Namespace Controller ;

class GCInstallCustomPackage extends Base {

    public function execute($pageVars) {

      $isDefaultAction = parent::checkDefaultActions($pageVars) ;
      if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];
      $action = $pageVars["route"]["action"];

      $actionsToClasses = array(
        "dev-server-slim" => "DevServerSlim",
        "devserverslim" => "DevServerSlim",
        "devserverslimnosudo" => "DevServerSlimNoSudo",
        "dev-server-slim-nosudo" => "DevServerSlimNoSudo",
        "prod-server-slim" => "ProductionServerSlim" );

      if (array_key_exists($action, $actionsToClasses)) {
        $className = '\\Controller\\'.$actionsToClasses[$action];
        $installPackageController = new $className();
        return $installPackageController->execute($pageVars); }

      $this->content["messages"][] = "Invalid Action - Package does not Exist";
      return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}
