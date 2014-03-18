<?php

Namespace Model;

class ApacheServerUbuntu extends BaseLinuxApp {

  // Compatibility
  public $os = array("Linux") ;
  public $linuxType = array("Debian") ;
  public $distros = array("Ubuntu") ;
  public $versions = array("12.04", "12.10") ;
  public $architectures = array("any") ;

  // Model Group
  public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "ApacheServer";
    $this->installCommands = array("apt-get install -y apache2");
    $this->uninstallCommands = array("apt-get remove -y apache2");
    $this->programDataFolder = "/opt/ApacheServer"; // command and app dir name
    $this->programNameMachine = "apacheserver"; // command and app dir name
    $this->programNameFriendly = "Apache Server!"; // 12 chars
    $this->programNameInstaller = "Apache Server";
    $this->statusCommand = "apache2 -v" ;
    $this->initialize();
  }

}