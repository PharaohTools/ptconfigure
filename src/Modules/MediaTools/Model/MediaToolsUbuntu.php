<?php

Namespace Model;

class MediaToolsUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "MediaTools";
    $this->installCommands = array( "apt-get install -y vlc" );
    $this->uninstallCommands = array( "apt-get remove -y vlc" );
    $this->programDataFolder = "/opt/MediaTools"; // command and app dir name
    $this->programNameMachine = "mediatools"; // command and app dir name
    $this->programNameFriendly = "Media Tools!"; // 12 chars
    $this->programNameInstaller = "Media Tools";
    $this->initialize();
  }

}