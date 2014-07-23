<?php

Namespace Model;

class PHPStormUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("32", "64") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPStorm";
        $this->installCommands = array (
            array("method"=> array("object" => $this, "method" => "ensureJava", "params" => array()) ),
            array("command" => array(
                    "cd /tmp" ,
                    "git clone https://github.com/phpengine/cleopatra-phpstorm phpstorm",
                    "rm -rf ****PROGDIR****",
                    "mkdir -p ****PROGDIR****",
                    "mv /tmp/phpstorm/* ****PROGDIR****",
                    "chmod -R 777 ****PROGDIR****",
                    "rm -rf /tmp/phpstorm" ) ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command" => array("rm -rf ****PROGDIR****") ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
        );
        $this->programDataFolder = "/opt/phpstorm"; // command and app dir name
        $this->programNameMachine = "phpstorm"; // command and app dir name
        $this->programNameFriendly = "PHP Storm 7"; // 12 chars
        $this->programNameInstaller = "PHP Storm 7";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "phpstorm.sh";
        $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
        $this->statusCommand = "cat /usr/bin/phpstorm > /dev/null 2>&1";
        $this->versionInstalledCommand = 'echo "7"' ;
        $this->versionRecommendedCommand = 'echo "7"' ;
        $this->versionLatestCommand = 'echo "7"' ;
        $this->initialize();
    }

    // todo phpstorm should ensure java
    public function ensureJava() {
		$javaFactory = new \Model\Java();
		$java = $javaFactory->getModel($this->params);
		$java->ensureInstalled();
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 0, 4) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 0, 4) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 0, 4) ;
        return $done ;
    }

}
