<?php

Namespace Controller ;

class DevServerSlim extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Development Server - Slim Version";

      $this->registeredModels = array (
        "Cleopatra" ,
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "Dapperstrano" ,
        "JRush" ,
        "PHPUnit" ,
        "PHPCS" ,
        "PHPMD" ,
        "Java" ,
        "DeveloperTools" ,
        "SudoNoPass" ,
        "MediaTools" ,
      );

      $this->checkForRegisteredModels();

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"GCInstallCustomPackage", "pageVars"=>$this->content);

    }

}
