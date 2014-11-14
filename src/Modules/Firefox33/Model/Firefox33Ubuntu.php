<?php

Namespace Model;

class Firefox24Ubuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "Firefox24";
    $this->installCommands = array(
        array( "command" => array(
            "cd /tmp" ,
            "git clone https://github.com/phpengine/Firefox24 firefox24",
            "rm -rf ****PROGDIR****",
            "mkdir -p ****PROGDIR****",
            "mv /tmp/firefox24/* ****PROGDIR****",
            "rm -rf /tmp/firefox24" ) ),
        array("method"=> array("object" => $this, "method" => "deleteExecutorIfExists", "params" => array()) ),
        array("method"=> array("object" => $this, "method" => "saveExecutorFile", "params" => array()) ),
    );
    $this->uninstallCommands = array(
        array( "command" =>  array(
            "rm -rf ****PROGDIR****",
            "rm -rf ****PROG EXECUTOR****" ) )
    );
    $this->programDataFolder = "/opt/firefox24"; // command and app dir name
    $this->programNameMachine = "firefox"; // command and app dir name
    $this->programNameFriendly = " Firefox 24 "; // 12 chars
    $this->programNameInstaller = "Firefox 24";
    $this->programExecutorFolder = "/usr/bin";
    $this->programExecutorTargetPath = "firefox-bin";
    $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
    $this->initialize();
  }

}