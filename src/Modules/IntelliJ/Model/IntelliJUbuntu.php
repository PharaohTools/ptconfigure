<?php

Namespace Model;

class IntelliJUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "IntelliJ";
        $this->installCommands = array (
            array("method"=> array("object" => $this, "method" => "ensureJava", "params" => array()) ),
            array("command" => array(
                    "cd /tmp" ,
                    "git clone https://github.com/phpengine/cleopatra-intellij intellij",
                    "rm -rf ****PROGDIR****",
                    "mkdir -p ****PROGDIR****",
                    "mv /tmp/intellij/* ****PROGDIR****",
                    "chmod -R 777 ****PROGDIR****",
                    "rm -rf /tmp/intellij" ) ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("command" => array(
                    "rm -rf ****PROGDIR****",
                    "rm -rf ****PROG EXECUTOR****" ) )
            );
        $this->programDataFolder = "/opt/intellij"; // command and app dir name
        $this->programNameMachine = "intellij"; // command and app dir name
        $this->programNameFriendly = "Intelli J 12"; // 12 chars
        $this->programNameInstaller = "Intelli J 12";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "intellij.sh";
        $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
        $this->initialize();
    }

    // todo intellij should ensure java
    public function ensureJava() {
		$javaFactory = new \Model\Java();
		$java = $javaFactory->getModel($this->params);
		$java->ensureInstalled();
    }

}
