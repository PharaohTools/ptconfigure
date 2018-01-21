<?php

Namespace Model;

class PTVGUILinux extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PTVGUI";
        $this->installCommands = array(
//            array("method"=> array("object" => $this, "method" => "askForPTVGUIVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
        $this->programDataFolder = "/opt/ptvgui"; // command and app dir name
        $this->programNameMachine = "ptvgui"; // command and app dir name
        $this->programNameFriendly = "PTV GUI"; // 12 chars
        $this->programNameInstaller = "Pharaoh Vitualize GUI";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "ptvgui";
        $this->programExecutorCommand = $this->getExecutorCommand();
        $this->statusCommand = "cat /usr/bin/ptvgui > /dev/null 2>&1";
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

        // http://41aa6c13130c155b18f6-e732f09b5e2f2287aef1580c786eed68.r92.cf3.rackcdn.com/pharaohinstaller-darwin-x64.zip


        $comms = array(
            "cd /tmp" ,
            "mkdir -p /tmp/ptvgui" ,
            "cd /tmp/ptvgui" ,
            "wget http://ptvgui-release.storage.googleapis.com/{$this->sv}/ptvgui-server-standalone-{$this->sv}.0.jar",
            "mkdir -p {$this->programDataFolder}",
            "mv /tmp/ptvgui/* {$this->programDataFolder}",
            "rm -rf /tmp/ptvgui/",
            "cd {$this->programDataFolder}",
            "mv ptvgui-server-standalone-{$this->sv}.0.jar ptvgui-server.jar" ) ;
        $this->executeAsShell($comms) ;
    }

    public function startPTVGUI() {
        $silentFlag = (isset($this->params["silent"])) ? " &" : "" ;
        if (isset($this->params["with-chrome-driver"])) {
            $cdsPath = (isset($this->params["guess"])) ? "/opt/chromedriver/chromedriver" : "" ;
            $cdsPath = (isset($this->params["chrome-driver-path"])) ? $this->params["chrome-driver-path"] : "$cdsPath" ;
            if ($cdsPath == "") { $cdsPath = $this->askForChromeDriverPath() ; }
            $cdFlag = "-Dwebdriver.chrome.driver=$cdsPath" ; }
        else {
            $cdFlag = "" ; }
        $comms = array(
            'java -jar ' . $this->programDataFolder . "/ptvgui-server.jar {$cdFlag}{$silentFlag}") ;
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
        return 'java -jar ' . $this->programDataFolder . "/ptvgui-server.jar {$cdFlag}" ;
    }

//    protected function askForPTVGUIVersion(){
//        $ao = array("2.39", "2.40", "2.41", "2.42", "2.43", "2.44") ;
//        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
//            $this->sv = $this->params["version"] ; }
//        else if (isset($this->params["guess"])) {
//            $count = count($ao)-1 ;
//            $this->sv = $ao[$count] ; }
//        else {
//            $question = 'Enter PTVGUI Version';
//            return self::askForArrayOption($question, $ao, true); }
//    }

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