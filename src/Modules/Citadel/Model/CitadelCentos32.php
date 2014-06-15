<?php

Namespace Model;

class CitadelCentos32 extends BaseLinuxApp {

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
      $this->autopilotDefiner = "Citadel";
      $this->installCommands = array("yum install -y citadel");
      $this->uninstallCommands = array("yum remove -y citadel");
      $this->programDataFolder = "/opt/Citadel"; // command and app dir name
      $this->programNameMachine = "citadel"; // command and app dir name
      $this->programNameFriendly = "Citadel Server!"; // 12 chars
      $this->programNameInstaller = "Citadel Server";
      $this->statusCommand = "citadel -v" ;
//      $this->versionInstalledCommand = "sudo apt-cache policy httpd" ;
//      $this->versionRecommendedCommand = "sudo apt-cache policy httpd" ;
//      $this->versionLatestCommand = "sudo apt-cache policy httpd" ;
      $this->initialize();
  }

}