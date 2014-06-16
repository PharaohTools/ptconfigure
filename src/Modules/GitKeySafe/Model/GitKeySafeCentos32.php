<?php

Namespace Model;

class GitKeySafeCentos32 extends BaseLinuxApp {

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
      $this->autopilotDefiner = "GitKeySafe";
      $this->installCommands = array("yum install -y gitkeysafe");
      $this->uninstallCommands = array("yum remove -y gitkeysafe");
      $this->programDataFolder = "/opt/GitKeySafe"; // command and app dir name
      $this->programNameMachine = "gitkeysafe"; // command and app dir name
      $this->programNameFriendly = "Git Key-Safe Server!"; // 12 chars
      $this->programNameInstaller = "Git Key-Safe Server";
      $this->statusCommand = "gitkeysafe -v" ;
//      $this->versionInstalledCommand = "sudo apt-cache policy httpd" ;
//      $this->versionRecommendedCommand = "sudo apt-cache policy httpd" ;
//      $this->versionLatestCommand = "sudo apt-cache policy httpd" ;
      $this->initialize();
  }

}