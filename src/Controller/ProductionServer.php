<?php

Namespace Controller ;

class ProductionServer extends Base {

    public function execute($pageVars) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $boxBossModel = new \Model\BoxBoss();
      $this->content["boxBossInstallResult"] = $boxBossModel->askWhetherToInstallPHPApp();

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

      $jRushModel = new \Model\JRush();
      $this->content["jrushInstallResult"] = $jRushModel->askWhetherToInstallPHPApp();

      $mysqlServerModel = new \Model\MysqlServer();
      $this->content["mysqlServerInstallResult"] = $mysqlServerModel->askWhetherToInstallLinuxApp();

      $mysqlAdminsModel = new \Model\MysqlAdmins();
      $this->content["mysqlAdminsInstallResult"] = $mysqlAdminsModel->askWhetherToInstallLinuxApp();

      $sudoNoPassModel = new \Model\SudoNoPass();
      $this->content["sudoNoPassInstallResult"] = $sudoNoPassModel->askWhetherToInstallLinuxApp();

      return array ("type"=>"view", "view"=>"installProductionServer", "pageVars"=>$this->content);

    }

}
