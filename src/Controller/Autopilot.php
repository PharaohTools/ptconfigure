<?php

Namespace Controller ;

class Autopilot extends Base {

    public function execute($pageVars, $autoPilot) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $boxBossModel = new \Model\DeveloperTools();
      $this->content["boxBossInstallResult"]
        = $boxBossModel->runAutoPilotLinuxAppInstall($autoPilot);

      $gitToolsModel = new \Model\GitTools();
      $this->content["gitToolsInstallResult"]
        = $gitToolsModel->runAutoPilotLinuxAppInstall($autoPilot);

      $devToolsModel = new \Model\DeveloperTools();
      $this->content["devToolsInstallResult"]
        = $devToolsModel->runAutoPilotLinuxAppInstall($autoPilot);

      $devhelperModel = new \Model\Devhelper();
      $this->content["devhelperInstallResult"]
        = $devhelperModel->runAutoPilotPHPAppInstall($autoPilot);

      $phpUnit35Model = new \Model\PHPUnit();
      $this->content["phpUnit35InstallResult"]
        = $phpUnit35Model->runAutoPilotPHPAppInstall($autoPilot);

      $phpCSModel = new \Model\PHPCS();
      $this->content["phpCSInstallResult"]
        = $phpCSModel->runAutoPilotPHPAppInstall($autoPilot);

      $phpMDModel = new \Model\PHPMD();
      $this->content["phpMDInstallResult"]
        = $phpMDModel->runAutoPilotPHPAppInstall($autoPilot);

      $javaModel = new \Model\Java();
      $this->content["javaInstallResult"]
        = $javaModel->runAutoPilotLinuxAppInstall($autoPilot);

      $jenkinsModel = new \Model\Jenkins();
      $this->content["jenkinsInstallResult"]
        = $jenkinsModel->runAutoPilotLinuxAppInstall($autoPilot);

      $rubyRVMModel = new \Model\RubyRVM();
      $this->content["rubyRVMInstallResult"]
        = $rubyRVMModel->runAutoPilotLinuxAppInstall($autoPilot);

      $seleniumModel = new \Model\SeleniumServer();
      $this->content["seleniumInstallResult"]
        = $seleniumModel->runAutoPilotLinuxAppInstall($autoPilot);

      $firefox14Model = new \Model\Firefox14();
      $this->content["firefox14InstallResult"]
        = $firefox14Model->runAutoPilotLinuxAppInstall($autoPilot);

      $firefox17Model = new \Model\Firefox17();
      $this->content["firefox17InstallResult"]
        = $firefox17Model->runAutoPilotLinuxAppInstall($autoPilot);

      $mysqlToolsModel = new \Model\MysqlTools();
      $this->content["mysqlToolsInstallResult"]
        = $mysqlToolsModel->runAutoPilotLinuxAppInstall($autoPilot);

      $mediaToolsModel = new \Model\MediaTools();
      $this->content["mediaToolsInstallResult"]
        = $mediaToolsModel->runAutoPilotLinuxAppInstall($autoPilot);

      return array ("type"=>"view", "view"=>"installAutopilot",
        "pageVars"=>$this->content);

    }

}