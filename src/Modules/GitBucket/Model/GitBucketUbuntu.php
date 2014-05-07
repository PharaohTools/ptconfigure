<?php

Namespace Model;

class GitBucketUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "GitBucket";
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
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "gitlab"; // command and app dir name
        $this->programNameFriendly = "!Git Lab!!"; // 12 chars
        $this->programNameInstaller = "Git Lab";
        $this->initialize();
    }

    public function executeDependencies() {
        $gitToolsFactory = new \Model\GitTools($this->params);
        $gitTools = new $gitToolsFactory->getModel($this->params);
        $gitTools->ensureInstalled();
        $javaFactory = new \Model\Java();
        $java = new $javaFactory->getModel($this->params);
        $java->ensureInstalled();
    }

}