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
    public $sv ;

    // @todo ensure wget is installed
    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "SeleniumServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForSeleniumVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
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

    public function doInstallCommands() {
        $comms = array(
                "cd /tmp" ,
                "mkdir -p /tmp/selenium" ,
                "cd /tmp/selenium" ,
                "wget http://selenium-release.storage.googleapis.com/{$this->sv}/selenium-server-standalone-{$this->sv}.0.jar",
                "mkdir -p {$this->programDataFolder}",
                "mv /tmp/selenium/* {$this->programDataFolder}",
                "rm -rf /tmp/selenium/",
                "cd {$this->programDataFolder}",
                "mv selenium-server-standalone-{$this->sv}.0.jar selenium-server.jar" ) ;
        $this->executeAsShell($comms) ;
    }

    protected function askForSeleniumVersion(){
        $ao = array("2.39", "2.40", "2.41", "2.42", "2.43") ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->sv = $this->params["version"] ; }
        else if (isset($this->params["guess"])) {
            $count = count($ao)-1 ;
            $this->sv = $ao[$count] ; }
        else {
            $question = 'Enter Selenium Version';
            return self::askForArrayOption($question, $ao, true); }
    }

}