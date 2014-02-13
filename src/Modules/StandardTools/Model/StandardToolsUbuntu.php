<?php

Namespace Model;

class StandardToolsUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "StandardTools";
    $this->installCommands = array( "apt-get clean", "apt-get update", "apt-get install -y curl vim drush zip" );
    $this->uninstallCommands = array( "apt-get clean", "apt-get update", "apt-get remove -y curl vim drush zip" );
    $this->programDataFolder = "/opt/StandardTools"; // command and app dir name
    $this->programNameMachine = "standardtools"; // command and app dir name
    $this->programNameFriendly = "Std. Tools!!"; // 12 chars
    $this->programNameInstaller = "Standard Tools";
    $this->initialize();
  }

}
