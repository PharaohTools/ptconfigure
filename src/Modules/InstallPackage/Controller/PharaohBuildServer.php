<?php

Namespace Controller ;

class PharaohBuildServer extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Jenkins Build Server";

      $this->registeredModels = array (
        "PTConfigure" ,
        "StandardTools" ,
        "GitTools" ,
        "PHPModules" ,
        "ApacheModules" ,
        "PTDeploy" ,
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
        "MysqlServer" ,
        "MysqlTools" ,
        "MysqlAdmins" ,
        "SudoNoPass"

      );

      $this->checkForRegisteredModels($pageVars["route"]["extraParams"]);

      $this->executeMyRegisteredModels($pageVars["route"]["extraParams"]);

      return array ("type"=>"view", "view"=>"installPackage", "pageVars"=>$this->content);

    }

}
