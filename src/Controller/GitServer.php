<?php

Namespace Controller ;

class GitServer extends Base {

    public function execute($pageVars) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $cleopatraModel = new \Model\Cleopatra();
      $this->content["cleopatraInstallResult"] = $cleopatraModel->askWhetherToInstallPHPApp();

      $stToolsModel = new \Model\StandardTools();
      $this->content["stToolsInstallResult"] = $stToolsModel->askWhetherToInstallLinuxApp();

      $gitToolsModel = new \Model\GitTools();
      $this->content["gitToolsInstallResult"] = $gitToolsModel->askWhetherToInstallLinuxApp();

      $phpModulesModel = new \Model\PHPModules();
      $this->content["phpModulesInstallResult"] = $phpModulesModel->askWhetherToInstallLinuxApp();

      $apacheModulesModel = new \Model\ApacheModules();
      $this->content["apacheModulesInstallResult"] = $apacheModulesModel->askWhetherToInstallLinuxApp();

      $dapperstranoModel = new \Model\Dapperstrano();
      $this->content["dapperstranoInstallResult"] = $dapperstranoModel->askWhetherToInstallPHPApp();

      $sudoNoPassModel = new \Model\SudoNoPass();
      $this->content["sudoNoPassInstallResult"] = $sudoNoPassModel->askWhetherToInstallLinuxApp();

      return array ("type"=>"view", "view"=>"installGitServer", "pageVars"=>$this->content);

    }

}
