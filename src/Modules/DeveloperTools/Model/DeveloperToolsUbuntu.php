<?php

Namespace Model;

class DeveloperToolsUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "DeveloperTools";
    $this->installCommands = array( "apt-get install -y geany bluefish kompozer" );
    $this->uninstallCommands = array( "apt-get remove -y geany bluefish kompozer" );
    $this->programDataFolder = "/opt/DeveloperTools"; // command and app dir name
    $this->programNameMachine = "developertools"; // command and app dir name
    $this->programNameFriendly = "Devel Tools!"; // 12 chars
    $this->programNameInstaller = "Developer Tools";
    $this->initialize();
  }

  public function askStatus() {
      return $this->askStatusByArray(array( "geany", "bluefish", "kompozer" )) ;
  }

}