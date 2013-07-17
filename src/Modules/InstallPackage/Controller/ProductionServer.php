<?php

Namespace Controller ;

class ProductionServer extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Development Server";

      $this->registeredModels = array (
        "Cleopatra" ,
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "Dapperstrano" ,
        "JRush" ,
        "MysqlServer" ,
        "MysqlAdmins" ,
        "SudoNoPass"
      );

      $this->checkForRegisteredModels();

      $this->executeMyRegisteredModels();

      return array ("type"=>"view", "view"=>"installPackage", "pageVars"=>$this->content);

    }

}
