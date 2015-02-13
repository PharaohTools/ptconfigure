<?php

Namespace Controller ;

class TestServer extends Base {

    public function execute($pageVars) {

      $this->content["package-friendly"] = "Testing Server";

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
