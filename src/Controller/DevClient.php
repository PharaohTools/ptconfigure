<?php

Namespace Controller ;

class DevClient extends Base {

    public function execute($pageVars) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $gitToolsModel = new \Model\GitTools();
      $this->content["gitToolsInstallResult"] = $gitToolsModel->askWhetherToInstallLinuxApp();

      $devhelperModel = new \Model\Devhelper();
      $this->content["devhelperInstallResult"] = $devhelperModel->askWhetherToInstallPHPApp();

      $phpUnitModel = new \Model\PHPUnit();
      $this->content["phpUnitInstallResult"]= $phpUnitModel->askWhetherToInstallPHPApp();

      $phpCSModel = new \Model\PHPCS();
      $this->content["phpCSInstallResult"] = $phpCSModel->askWhetherToInstallPHPApp();

      $phpMDModel = new \Model\PHPMD();
      $this->content["phpMDInstallResult"] = $phpMDModel->askWhetherToInstallPHPApp();

      $javaModel = new \Model\Java();
      $this->content["javaInstallResult"] = $javaModel->askWhetherToInstallLinuxApp();

      $jenkinsModel = new \Model\Jenkins();
      $this->content["jenkinsInstallResult"] = $jenkinsModel->askWhetherToInstallLinuxApp();

      $rubyRVMModel = new \Model\RubyRVM();
      $this->content["rubyRVMInstallResult"] = $rubyRVMModel->askWhetherToInstallLinuxApp();

      $seleniumModel = new \Model\SeleniumServer();
      $this->content["seleniumInstallResult"] = $seleniumModel->askWhetherToInstallLinuxApp();

      $firefox14Model = new \Model\Firefox14();
      $this->content["firefox14InstallResult"] = $firefox14Model->askWhetherToInstallLinuxApp();

      return array ("type"=>"view", "view"=>"installDevClient", "pageVars"=>$this->content);

    }

}