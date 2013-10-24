<?php

Namespace Model;

class LigHTTPDServerUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "LigHTTPDServer";
    $this->installCommands = array("apt-get install -y lighttpd");
    $this->uninstallCommands = array("apt-get remove -y lighttpd");
    $this->programDataFolder = "/opt/LigHTTPDServer"; // command and app dir name
    $this->programNameMachine = "lighttpdserver"; // command and app dir name
    $this->programNameFriendly = "LigHTTPD Server!"; // 12 chars
    $this->programNameInstaller = "LigHTTPD Server";
    $this->initialize();
  }

}