<?php

Namespace Model;

class NginxServerUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "NginxServer";
    $this->installCommands = array("apt-get install -y nginx");
    $this->uninstallCommands = array("apt-get remove -y nginx");
    $this->programDataFolder = "/opt/NginxServer"; // command and app dir name
    $this->programNameMachine = "nginxserver"; // command and app dir name
    $this->programNameFriendly = "Nginx Server!"; // 12 chars
    $this->programNameInstaller = "Nginx Server";
    $this->initialize();
  }

}