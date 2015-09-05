<?php

Namespace Model;

class ApacheServerMac extends ApacheServerCentos {

  // Compatibility
  public $os = array("Darwin") ;
  public $linuxType = array("any") ;
  public $distros = array("any") ;
  public $versions = array( array("10.5", "+")) ;
  public $architectures = array("any") ;

  // Model Group
  public $modelGroup = array("Default") ;

  public $packageName = "httpd" ;

  public function __construct($params) {
      parent::__construct($params);
      $this->autopilotDefiner = "ApacheServer";
      $this->installCommands = array(
          array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("MacPorts", "httpd")) ),
          array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
      $this->uninstallCommands = array(
          array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("MacPorts", "httpd")) ),
          array("method"=> array("object" => $this, "method" => "apacheRestart", "params" => array())) );
      $this->programDataFolder = "/opt/ApacheServer"; // command and app dir name
      $this->programNameMachine = "apacheserver"; // command and app dir name
      $this->programNameFriendly = "Apache Server!"; // 12 chars
      $this->programNameInstaller = "Apache Server";
      $this->statusCommand = "httpd -v" ;
      $this->versionInstalledCommand = SUDOPREFIX."httpd -v" ;
      $this->versionRecommendedCommand = SUDOPREFIX."httpd -v" ;
      $this->versionLatestCommand = SUDOPREFIX."httpd -v" ;
      $this->initialize();
  }

  public function apacheRestart() {
      $comm = $this->packageName.' -k restart' ;
      $this->executeAndOutput($comm, "Restarted Apache, httpd");
  }

    public function versionInstalledCommandTrimmer($text) {
        $lines = explode("\n", $text) ;
        foreach ($lines as $line) {
            if (substr($line, 0, 7)=="Version") {
                $colon = strpos($line, ":");
                $version = substr($line, $colon+2, strlen($line)-1) ;
                return $version ; } }
        return null ;
    }


}