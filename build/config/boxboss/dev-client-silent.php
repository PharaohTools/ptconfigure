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

      // Selenium Server
      $this->SeleniumServerInstallExecute = true;

    }

}

?>
