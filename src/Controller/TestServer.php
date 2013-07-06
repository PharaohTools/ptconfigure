<?php

Namespace Controller ;

class TestServer extends Base {

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

      $jenkinsPluginsModel = new \Model\JenkinsPlugins();
      $this->content["jenkinsPluginsInstallResult"] = $jenkinsPluginsModel->askWhetherToInstallLinuxApp();

      $jenkinsSudoModel = new \Model\JenkinsSudoNoPass();
      $this->content["jenkinsSudoInstallResult"] = $jenkinsSudoModel->askWhetherToInstallLinuxApp();

      $vncServerModel = new \Model\VNCServer();
      $this->content["vncServerInstallResult"] = $vncServerModel->askWhetherToInstallLinuxApp();

      $rubyRVMModel = new \Model\RubyRVM();
      $this->content["rubyRVMInstallResult"] = $rubyRVMModel->askWhetherToInstallLinuxApp();

      $seleniumModel = new \Model\SeleniumServer();
      $this->content["seleniumInstallResult"] = $seleniumModel->askWhetherToInstallLinuxApp();

      $fireFox14Model = new \Model\Firefox14();
      $this->content["fireFox14InstallResult"] = $fireFox14Model->askWhetherToInstallLinuxApp();

      $fireFox17Model = new \Model\Firefox17();
      $this->content["fireFox17InstallResult"] = $fireFox17Model->askWhetherToInstallLinuxApp();

      $mysqlServerModel = new \Model\MysqlServer();
      $this->content["mysqlServerInstallResult"] = $mysqlServerModel->askWhetherToInstallLinuxApp();

      $mysqlToolsModel = new \Model\MysqlTools();
      $this->content["mysqlToolsInstallResult"] = $mysqlToolsModel->askWhetherToInstallLinuxApp();

      $mysqlAdminsModel = new \Model\MysqlAdmins();
      $this->content["mysqlAdminsInstallResult"] = $mysqlAdminsModel->askWhetherToInstallLinuxApp();

      $sudoNoPassModel = new \Model\SudoNoPass();
      $this->content["sudoNoPassInstallResult"] = $sudoNoPassModel->askWhetherToInstallLinuxApp();

      return array ("type"=>"view", "view"=>"installTestServer", "pageVars"=>$this->content);

    }

}
