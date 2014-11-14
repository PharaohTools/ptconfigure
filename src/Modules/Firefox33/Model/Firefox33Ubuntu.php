<?php

Namespace Model;

class Firefox33Ubuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array(array("11.04" => "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "Firefox33";
    $this->installCommands = array(
        array( "command" => array(
            "cd /tmp" ,
            "git clone https://github.com/phpengine/firefox33 firefox33",
            "rm -rf ****PROGDIR****",
            "mkdir -p ****PROGDIR****",
            "mv /tmp/firefox33/* ****PROGDIR****",
            "rm -rf /tmp/firefox33" ) ),
        array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
        array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
    );
    $this->uninstallCommands = array(
        array( "command" =>  array(
            "rm -rf ****PROGDIR****",
            "rm -rf ****PROG EXECUTOR****" ) )
    );
    $this->programDataFolder = "/opt/firefox33"; // command and app dir name
    $this->programNameMachine = "firefox"; // command and app dir name
    $this->programNameFriendly = " Firefox 33 "; // 12 chars
    $this->programNameInstaller = "Firefox 33";
    $this->programExecutorFolder = "/usr/bin";
    $this->programExecutorTargetPath = "firefox-bin";
    $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
    $this->initialize();
  }

}