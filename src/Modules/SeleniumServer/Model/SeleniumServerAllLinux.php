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
    public $modelGroup = array("Default") ;

    // @todo parameterise the selenium version
    // @todo ensure wget is installed
    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SeleniumServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("command"=> array(
                "cd /tmp" ,
                "mkdir -p /tmp/selenium" ,
                "cd /tmp/selenium" ,
                "wget http://selenium.googlecode.com/files/selenium-server-standalone-2.39.0.jar",
                "mkdir -p ****PROGDIR****",
                "mv /tmp/selenium/* ****PROGDIR****",
                "rm -rf /tmp/selenium/",
                "cd ****PROGDIR****",
                "mv selenium-server-standalone-2.39.0.jar selenium-server.jar" ) ) ,
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf ****PROGDIR****")));
        $this->programDataFolder = "/opt/selenium"; // command and app dir name
        $this->programNameMachine = "selenium"; // command and app dir name
        $this->programNameFriendly = "Selenium Srv"; // 12 chars
        $this->programNameInstaller = "Selenium Server";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "selenium";
        $this->programExecutorCommand = 'java -jar ' . $this->programDataFolder . '/selenium-server.jar';
        $this->statusCommand = "cat /usr/bin/selenium > /dev/null 2>&1";
        $this->versionInstalledCommand = 'echo "2.39.0"' ;
        $this->versionRecommendedCommand = 'echo "2.39.0"' ;
        $this->versionLatestCommand = 'echo "2.39.0"' ;
        $this->initialize();
    }

    public function executeDependencies() {
        $gitToolsFactory = new \Model\GitTools($this->params);
        $gitTools = $gitToolsFactory->getModel($this->params);
        $gitTools->ensureInstalled();
        $javaFactory = new \Model\Java();
        $java = $javaFactory->getModel($this->params);
        $java->ensureInstalled();
    }

}