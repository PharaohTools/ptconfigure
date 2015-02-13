<?php

Namespace Controller ;

class ProductionServer extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Production Server";

      $this->registeredModels = array (
        "PTConfigure" ,
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "PTDeploy" ,
        "JRush" ,
        "MysqlServer" ,
        "MysqlAdmins" ,
        "SudoNoPass"
      );

      $this->checkForRegisteredModels($pageVars["route"]["extraParams"]);

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"installPackage", "pageVars"=>$this->content);

    }

}
