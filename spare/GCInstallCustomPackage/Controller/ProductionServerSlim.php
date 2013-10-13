<?php

Namespace Controller ;

class ProductionServerSlim extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Production Server Slim Version - From Custom Package";

      $this->registeredModels = array (
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "Dapperstrano" ,
        "MysqlServer" ,
      );

      $this->checkForRegisteredModels();

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"GCInstallCustomPackage", "pageVars"=>$this->content);

    }

}
