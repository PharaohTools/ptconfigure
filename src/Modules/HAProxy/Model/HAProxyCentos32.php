<?php

Namespace Model;

class HAProxyCentos32 extends BaseLinuxApp {

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
      $this->autopilotDefiner = "HAProxy";
      $this->installCommands = array("yum install -y haproxy");
      $this->uninstallCommands = array("yum remove -y haproxy");
      $this->programDataFolder = "/opt/HAProxy"; // command and app dir name
      $this->programNameMachine = "haproxy"; // command and app dir name
      $this->programNameFriendly = "HA Proxy Server!"; // 12 chars
      $this->programNameInstaller = "HA Proxy Server";
      $this->statusCommand = "haproxy -v" ;
//      $this->versionInstalledCommand = "sudo apt-cache policy httpd" ;
//      $this->versionRecommendedCommand = "sudo apt-cache policy httpd" ;
//      $this->versionLatestCommand = "sudo apt-cache policy httpd" ;
      $this->initialize();
  }

}