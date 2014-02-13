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
    public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "MediaTools";
    $this->installCommands = array(
        "apt-get install -y vlc libdvdread4",
        "sh /usr/share/doc/libdvdread4/install-css.sh",
    );
    $this->uninstallCommands = array( "apt-get remove -y vlc libdvdread4" );
    $this->programDataFolder = "/opt/MediaTools"; // command and app dir name
    $this->programNameMachine = "mediatools"; // command and app dir name
    $this->programNameFriendly = "Media Tools!"; // 12 chars
    $this->programNameInstaller = "Media Tools";
    $this->initialize();
  }

}