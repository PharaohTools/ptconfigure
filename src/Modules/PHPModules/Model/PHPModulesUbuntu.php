<?php

Namespace Model;

class PHPModulesUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "PHPModules";
    $this->installCommands = array( "apt-get install -y php5-gd php5-imagick php5-curl php5-mysql" );
    $this->uninstallCommands = array( "apt-get remove -y php5-gd php5-imagick php5-curl php5-mysql" );
    $this->programDataFolder = "/opt/PHPModules"; // command and app dir name
    $this->programNameMachine = "phpmodules"; // command and app dir name
    $this->programNameFriendly = "PHP Mods!"; // 12 chars
    $this->programNameInstaller = "PHP Modules";
    $this->initialize();
  }

}
