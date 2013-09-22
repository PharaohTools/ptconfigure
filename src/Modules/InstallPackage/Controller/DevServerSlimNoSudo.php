<?php

Namespace Controller ;

class DevServerSlimNoSudo extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Development Server - Slim Version no Sudo";

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
        "MediaTools" ,
      );

      $this->checkForRegisteredModels();

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"installPackage", "pageVars"=>$this->content);

    }

}
