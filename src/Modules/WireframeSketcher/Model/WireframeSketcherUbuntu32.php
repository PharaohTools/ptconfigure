<?php

Namespace Model;

class WireframeSketcherUbuntu32 extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("32") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "WireframeSketcher";
        $this->installCommands = array (
            array("method"=> array("object" => $this, "method" => "ensureJava", "params" => array()) ),
            array("command" => array(
                "cd /tmp" ,
                "git clone https://github.com/phpengine/cleopatra-wireframe-sketcher-32.git wireframe-sketcher",
                "sudo dpkg -i wireframe-sketcher/WireframeSketcher-4.3.1_i386.deb",
                "sudo rm WireframeSketcher-4.3.1_i386.deb" ) ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command" => array("rm -rf ****PROGDIR****") ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
        );
        $this->programDataFolder = "/opt/wireframe-sketcher"; // command and app dir name
        $this->programNameMachine = "wireframe-sketcher"; // command and app dir name
        $this->programNameFriendly = "Wireframe Sketcher"; // 12 chars
        $this->programNameInstaller = "Wireframe Sketcher";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "wireframe-sketcher.sh";
        $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
        $this->statusCommand = "cat /usr/bin/wireframe-sketcher > /dev/null 2>&1";
        $this->versionInstalledCommand = 'echo "12.1"' ;
        $this->versionRecommendedCommand = 'echo "12.1"' ;
        $this->versionLatestCommand = 'echo "12.1"' ;
        $this->initialize();
    }

    // todo wireframe-sketcher should ensure java
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
