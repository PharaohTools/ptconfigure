<?php

Namespace Model;

class GeckoDriverAllLinux extends BaseLinuxApp {

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
        $this->autopilotDefiner = "GeckoDriver";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForGeckoDriverVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
        $this->programDataFolder = "/opt/geckodriver"; // command and app dir name
        $this->programNameMachine = "geckodriver"; // command and app dir name
        $this->programNameFriendly = "GeckoDriver"; // 12 chars
        $this->programNameInstaller = "GeckoDriver Server";
        $this->statusCommand = "cat /usr/bin/geckodriver > /dev/null 2>&1";
        // @todo dont hardcode the installed version
        $this->versionInstalledCommand = 'echo "2.9"' ;
        $this->versionRecommendedCommand = 'echo "2.9"' ;
        $this->versionLatestCommand = 'echo "2.9"' ;
        $this->initialize();
    }

    public function executeDependencies() {
        $tempVersion = $this->params["version"] ;
        unset($this->params["version"]) ;
        $javaFactory = new \Model\Java();
        $java = $javaFactory->getModel($this->params);
        $java->ensureInstalled();
        $this->params["version"] = $tempVersion ;
    }

    public function doInstallCommands() {
        $system = new \Model\SystemDetectionAllOS() ;
        $arch = $system->architecture ;
        $comms = array(
                "cd /tmp" ,
                "mkdir -p /tmp/geckodriver" ,
                "cd /tmp/geckodriver" ,
                "wget https://github.com/mozilla/geckodriver/releases/download/v{$this->sv}/geckodriver-v{$this->sv}-linux{$arch}.tar.gz",
                "mkdir -p {$this->programDataFolder}",
                "mv /tmp/geckodriver/* {$this->programDataFolder}",
                "rm -rf /tmp/geckodriver/",
                "cd {$this->programDataFolder}",
                "tar -xvf geckodriver-v{$this->sv}-linux{$arch}.tar.gz",
                SUDOPREFIX."chmod -R u+x {$this->programDataFolder}",
                SUDOPREFIX."chmod -R 777 {$this->programDataFolder}",
                'echo \'PATH=$PATH:/opt/geckodriver\' >> /etc/profile',
                'echo \'export PATH\' >> /etc/profile',
                'echo \'export PATH\' >> /etc/bash.bashrc',
                '. /etc/profile'
              ) ;
        $this->executeAsShell($comms) ;
    }

    protected function askForGeckoDriverVersion(){
        $ao = array (
            "0.26.0","0.25.0","0.24.0","0.23.0","0.22.0","0.21.0","0.20.0",
            "0.19.0","0.18.0","0.17.0","0.16.0","0.15.0","0.14.0","0.13.0",
            "0.12.0","0.11.0","0.10.0"
        ) ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->sv = $this->params["version"] ; }
        else if (isset($this->params["guess"])) {
            $count = count($ao)-1 ;
            $this->sv = $ao[$count] ; }
        else {
            $question = 'Enter Gecko Driver Version';
            return self::askForArrayOption($question, $ao, true); }
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