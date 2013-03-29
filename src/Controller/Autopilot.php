<?php

Namespace Controller ;

class Autopilot extends Base {

    public function execute($pageVars, $autoPilot) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $gitToolsModel = new \Model\GitTools();
      $this->content["gitToolsInstallResult"]
        = $gitToolsModel->runAutoPilotLinuxAppInstall($autoPilot);

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

      return array ("type"=>"view", "view"=>"installAutopilot",
        "pageVars"=>$this->content);

    }

}