<?php

Namespace Model;

class ChromeDriverAllLinux extends BaseLinuxApp {

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
        $this->autopilotDefiner = "ChromeDriver";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForChromeDriverVersion", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command"=> array("rm -rf {$this->programDataFolder}")));
        $this->programDataFolder = "/opt/chromedriver"; // command and app dir name
        $this->programNameMachine = "chromedriver"; // command and app dir name
        $this->programNameFriendly = "ChromeDriver"; // 12 chars
        $this->programNameInstaller = "ChromeDriver Server";
        $this->statusCommand = "cat /usr/bin/chromedriver > /dev/null 2>&1";
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
                "mkdir -p /tmp/chromedriver" ,
                "cd /tmp/chromedriver" ,
                "wget http://chromedriver.storage.googleapis.com/{$this->sv}/chromedriver_linux{$arch}.zip",
                "mkdir -p {$this->programDataFolder}",
                "mv /tmp/chromedriver/* {$this->programDataFolder}",
                "rm -rf /tmp/chromedriver/",
                "cd {$this->programDataFolder}",
                "unzip -o chromedriver_linux{$arch}.zip",
                SUDOPREFIX."chmod -R u+x {$this->programDataFolder}",
                SUDOPREFIX."chmod -R 777 {$this->programDataFolder}",
                'echo \'PATH=$PATH:/opt/chromedriver\' >> /etc/profile',
                'echo \'export PATH\' >> /etc/profile',
                'echo \'export PATH\' >> /etc/bash.bashrc',
                '. /etc/profile'
              ) ;
        $this->executeAsShell($comms) ;
    }

    protected function askForChromeDriverVersion(){
        $ao = array (
            "2.0", "2.10", "2.1", "2.11", "2.2", "2.3", "2.4", "2.5", "2.6", "2.7",
            "2.8", "2.9", "80.0.3987.106"
        ) ;
        if (isset($this->params["version"]) && in_array($this->params["version"], $ao)) {
            $this->sv = $this->params["version"] ; }
        else if (isset($this->params["guess"])) {
            $count = count($ao)-1 ;
            $this->sv = $ao[$count] ; }
        else {
            $question = 'Enter Chrome Driver Version';
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