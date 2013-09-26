<?php

Namespace Controller ;

class DevServerSlimNoSudo extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Development Server - Slim Version no Sudo";

      $this->registeredModels = array (
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "DeveloperTools" ,
        "MediaTools" ,
      );

      $this->checkForRegisteredModels();

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"GCInstallCustomPackage", "pageVars"=>$this->content);

    }

}
