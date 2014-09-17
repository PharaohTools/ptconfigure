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
        $sv = $this->askForSeleniumVersion();
        $this->autopilotDefiner = "SeleniumServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("command"=> array(
                "cd /tmp" ,
                "mkdir -p /tmp/selenium" ,
                "cd /tmp/selenium" ,
                "wget http://selenium-release.storage.googleapis.com/$sv/selenium-server-standalone-$sv.0.jar",
                "mkdir -p ****PROGDIR****",
                "mv /tmp/selenium/* ****PROGDIR****",
                "rm -rf /tmp/selenium/",
                "cd ****PROGDIR****",
                "mv selenium-server-standalone-$sv.0.jar selenium-server.jar" ) ) ,
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
        $this->versionInstalledCommand = 'echo "2.41.0"' ;
        $this->versionRecommendedCommand = 'echo "2.41.0"' ;
        $this->versionLatestCommand = 'echo "2.41.0"' ;
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

    protected function askForSeleniumVersion(){
        $ao = array("2.39", "2.40", "2.41", "2.42", "2.43") ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            return $this->params["version"] ; }
        if (isset($this->params["guess"])) {
            return array_pop($ao) ; }
        $question = 'Enter Selenium Version';
        return self::askForArrayOption($question, $ao, true);
    }

}