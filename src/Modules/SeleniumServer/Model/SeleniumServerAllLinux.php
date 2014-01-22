<?php

Namespace Model;

class SeleniumServerAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    // @todo parameterise the selenium version
    // @todo ensure wget is installed
    // @todo ensure java is installed
    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SeleniumServer";
        $this->installCommands = array(
          "cd /tmp" ,
          "mkdir -p /tmp/selenium" ,
          "cd /tmp/selenium" ,
          "wget http://selenium.googlecode.com/files/selenium-server-standalone-2.39.0.jar",
          "mkdir -p ****PROGDIR****",
          "mv /tmp/selenium/* ****PROGDIR****",
          "rm -rf /tmp/selenium/",
          "cd ****PROGDIR****",
          "mv selenium-server-standalone-2.39.0.jar selenium-server.jar",
          "java -jar selenium-server.jar >/dev/null 2>&1 </dev/null &" );
        $this->uninstallCommands = array("rm -rf ****PROGDIR****");
        $this->programDataFolder = "/opt/selenium"; // command and app dir name
        $this->programNameMachine = "selenium"; // command and app dir name
        $this->programNameFriendly = "Selenium Srv"; // 12 chars
        $this->programNameInstaller = "Selenium Server";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "firefox-bin";
        $this->programExecutorCommand = 'java -jar ' . $this->programDataFolder .
          '/selenium-server.jar';
        $this->registeredPostInstallFunctions = array("deleteExecutorIfExists",
          "saveExecutorFile");
        $this->initialize();
      }

}