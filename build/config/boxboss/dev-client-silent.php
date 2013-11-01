<?php

Namespace Core ;

class AutoPilotConfigured extends AutoPilot {

    public function __construct () {

      // Git, gitk, git-cola and git core
      $this->GitToolsInstallExecute = true;

      // Devhelper
      $this->DevhelperInstallExecute = true;

      // PHP Unit 3.5 (for php 5.3)
      $this->PHPUnit35InstallExecute = true;

      // PHP Codesniffer
      $this->PHPCSInstallExecute = true;

      // PHP Mess Detector
      $this->PHPMDInstallExecute = true;

      // Oracle Java 7 JDK
      $this->JavaJDKInstallExecute = false;

      // Jenkins
      $this->JenkinsInstallExecute = true;

      // Ruby RVM
      $this->RubyRVMInstallExecute = true;

      // Selenium Server
      $this->SeleniumServerInstallExecute = true;

      // Firefox 14
      $this->Firefox14InstallExecute = true;

      // Firefox 17
      $this->Firefox17InstallExecute = true;

    }

}

?>
