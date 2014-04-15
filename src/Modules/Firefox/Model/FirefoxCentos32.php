<?php

Namespace Model;

class FirefoxCentos32 extends BaseLinuxApp {

  // Compatibility
  public $os = array("Linux") ;
  public $linuxType = array("Redhat") ;
  public $distros = array("CentOS") ;
  public $versions = array("5.9", "6.4") ;
  public $architectures = array("32") ;

  // Model Group
  public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "Firefox";
    $this->installCommands = array("yum install -y httpd");
    $this->uninstallCommands = array("yum remove -y httpd");
    $this->programDataFolder = "/opt/Firefox"; // command and app dir name
    $this->programNameMachine = "firefox"; // command and app dir name
    $this->programNameFriendly = "Firefox!"; // 12 chars
    $this->programNameInstaller = "Firefox";
    $this->initialize();
  }

}