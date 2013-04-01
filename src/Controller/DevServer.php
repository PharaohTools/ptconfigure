<?php

Namespace Controller ;

class DevServer extends Base {

    public function execute($pageVars) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $boxBossModel = new \Model\BoxBoss();
      $this->content["boxBossInstallResult"] = $boxBossModel->askWhetherToInstallPHPApp();

      $gitToolsModel = new \Model\GitTools();
      $this->content["gitToolsInstallResult"] = $gitToolsModel->askWhetherToInstallLinuxApp();

      $devToolsModel = new \Model\DeveloperTools();
      $this->content["devToolsInstallResult"] = $devToolsModel->askWhetherToInstallLinuxApp();

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

      $fireFox14Model = new \Model\Firefox14();
      $this->content["fireFox14InstallResult"] = $fireFox14Model->askWhetherToInstallLinuxApp();

      $fireFox17Model = new \Model\Firefox17();
      $this->content["fireFox17InstallResult"] = $fireFox17Model->askWhetherToInstallLinuxApp();

      $mysqlToolsModel = new \Model\MysqlTools();
      $this->content["mysqlToolsInstallResult"] = $mysqlToolsModel->askWhetherToInstallLinuxApp();

      $mediaToolsModel = new \Model\MediaTools();
      $this->content["mediaToolsInstallResult"] = $mediaToolsModel->askWhetherToInstallLinuxApp();

      return array ("type"=>"view", "view"=>"installDevServer", "pageVars"=>$this->content);

    }

}