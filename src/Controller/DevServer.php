<?php

Namespace Controller ;

class DevServer extends Base {

    public function execute($pageVars) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];
      $action = $pageVars["route"]["action"];

      $phpUnitModel = new \Model\PHPUnit();
      $this->content["phpUnitInstallResult"] = $phpUnitModel->askWhetherToInstallPHPApp();

      $phpCSModel = new \Model\PHPCS();
      $this->content["phpCSInstallResult"] = $phpCSModel->askWhetherToInstallPHPApp();

      $phpMDModel = new \Model\PHPMD();
      $this->content["phpCSInstallResult"] = $phpMDModel->askWhetherToInstallPHPApp();

      return array ("type"=>"view", "view"=>"install", "pageVars"=>$this->content);

    }

}