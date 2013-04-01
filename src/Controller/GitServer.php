<?php

Namespace Controller ;

class GitServer extends Base {

    public function execute($pageVars) {

      $this->content["route"] = $pageVars["route"];
      $this->content["messages"] = $pageVars["messages"];

      $boxBossModel = new \Model\BoxBoss();
      $this->content["boxBossInstallResult"] = $boxBossModel->askWhetherToInstallPHPApp();

      $gitToolsModel = new \Model\GitTools();
      $this->content["gitToolsInstallResult"] = $gitToolsModel->askWhetherToInstallLinuxApp();

      $devhelperModel = new \Model\Devhelper();
      $this->content["devhelperInstallResult"] = $devhelperModel->askWhetherToInstallPHPApp();

      return array ("type"=>"view", "view"=>"installGitServer", "pageVars"=>$this->content);

    }

}