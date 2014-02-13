<?php

Namespace Model;

class GitToolsUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "GitTools";
    $this->installCommands = array("apt-get install -y git git-core gitk git-cola");
    $this->uninstallCommands = array("apt-get remove -y git git-core gitk git-cola");
    $this->programDataFolder = "";
    $this->programNameMachine = "gittools"; // command and app dir name
    $this->programNameFriendly = "!Git Tools!!"; // 12 chars
    $this->programNameInstaller = "Git Tools";
    $this->initialize();
  }

}