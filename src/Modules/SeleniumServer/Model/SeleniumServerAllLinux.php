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
        $this->programExecutorCommand = $this->getExecutorCommand();
        $this->statusCommand = "cat /usr/bin/selenium > /dev/null 2>&1";
        // @todo dont hardcode the installed version
        $this->versionInstalledCommand = 'echo "2.44.0"' ;
        $this->versionRecommendedCommand = 'echo "2.44.0"' ;
        $this->versionLatestCommand = 'echo "2.44.0"' ;
        $this->initialize();
    }

    public function executeDependencies() {
        if (isset($this->params["no-dependencies"])) {
            return;
        }
        $tempVersion = isset($this->params["version"]) ? $this->params["version"] : null ;
        unset($this->params["version"]) ;
        $gitToolsFactory = new \Model\GitTools($this->params);
        $gitTools = $gitToolsFactory->getModel($this->params);
        $gitTools->ensureInstalled();
        $javaFactory = new \Model\Java();
        $java = $javaFactory->getModel($this->params);
        $java->ensureInstalled();
        $this->params["version"] = $tempVersion ;
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

    public function startSelenium() {
        $silentFlag = (isset($this->params["silent"])) ? " &" : "" ;
        if (isset($this->params["with-chrome-driver"])) {
            $cdsPath = (isset($this->params["guess"])) ? "/opt/chromedriver/chromedriver" : "" ;
            $cdsPath = (isset($this->params["chrome-driver-path"])) ? $this->params["chrome-driver-path"] : "$cdsPath" ;
            if ($cdsPath == "") { $cdsPath = $this->askForChromeDriverPath() ; }
            $cdFlag = "-Dwebdriver.chrome.driver=$cdsPath" ; }
        else {
            $cdFlag = "" ; }
        $comms = array(
            'java -jar ' . $this->programDataFolder . "/selenium-server.jar {$cdFlag}{$silentFlag}") ;
        $this->executeAsShell($comms) ;
    }

    public function getExecutorCommand() {
        if (isset($this->params["with-chrome-driver"])) {
            $cdsPath = (isset($this->params["guess"])) ? "/opt/chromedriver/chromedriver" : "" ;
            $cdsPath = (isset($this->params["chrome-driver-path"])) ? $this->params["chrome-driver-path"] : "$cdsPath" ;
            if ($cdsPath == "") { $cdsPath = $this->askForChromeDriverPath() ; }
            $cdFlag = "-Dwebdriver.chrome.driver=$cdsPath" ; }
        else {
            $cdFlag = "" ; }
        return 'java -jar ' . $this->programDataFolder . "/selenium-server.jar {$cdFlag}" ;
    }

    protected function askForSeleniumVersion(){
        $ao = array(
            "2.39","2.40","2.41","2.42","2.43","2.44","2.45","2.46","2.47","2.48","2.49","2.50","2.51","2.52",
            "2.53","3.0","3.1","3.2","3.3","3.4","3.5","3.6","3.7","3.8"
        ) ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->sv = $this->params["version"] ; }
        else if (isset($this->params["guess"])) {
            $count = count($ao)-1 ;
            $this->sv = $ao[$count] ; }
        else {
            $question = 'Enter Selenium Version';
            return self::askForArrayOption($question, $ao, true); }
    }

    protected function askForChromeDriverPath(){
        $question = 'Enter Chrome Driver Version';
        return self::askForInput($question, true);
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = str_replace("\n", "", $text) ;
        $done = str_replace("\r", "", $done) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = str_replace("\n", "", $text) ;
        $done = str_replace("\r", "", $done) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = str_replace("\n", "", $text) ;
        $done = str_replace("\r", "", $done) ;
        return $done ;
    }

}