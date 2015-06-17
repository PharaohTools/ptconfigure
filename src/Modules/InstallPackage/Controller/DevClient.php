<?php

Namespace Controller ;

class DevClient extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Development Client";

      $this->registeredModels = array (
        "PTConfigure" ,
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "JRush" ,
        "PHPUnit" ,
        "PHPCS" ,
        "PHPMD" ,
        "Java" ,
        "Jenkins" ,
        "JenkinsPlugins" ,
        "JenkinsSudoNoPass" ,
        /* "VNCServer", */
        "RubyRVM" ,
        "SeleniumServer" ,
        "Firefox14" ,
        "Firefox17" ,
        "DeveloperTools" ,
        "IntelliJ" ,
        "MysqlServer" ,
        "MysqlTools" ,
        "MysqlAdmins" ,
        "SudoNoPass" ,
        "MediaTools" ,
        "PharaohTools" ,
      );

      $this->checkForRegisteredModels($pageVars["route"]["extraParams"]);

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"installPackage", "pageVars"=>$this->content);

    }

}
