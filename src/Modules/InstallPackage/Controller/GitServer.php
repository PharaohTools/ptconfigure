<?php

Namespace Controller ;

class GitServer extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Git Server";

      $this->registeredModels = array (
        "Cleopatra" ,
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "Dapperstrano" ,
        "SudoNoPass" ,
      );

      $this->checkForRegisteredModels($pageVars["route"]["extraParams"]);

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"installPackage", "pageVars"=>$this->content);
    }

}
