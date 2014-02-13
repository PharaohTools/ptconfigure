<?php

Namespace Model;

class JenkinsUbuntu extends BaseLinuxApp {

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
    $this->autopilotDefiner = "Jenkins";
    $this->installCommands = array(
      "cd /tmp" ,
      "wget -q -O - http://pkg.jenkins-ci.org/debian/jenkins-ci.org.key | sudo apt-key add -",
      "echo deb http://pkg.jenkins-ci.org/debian binary/ > /etc/apt/sources.list.d/jenkins.list",
      "apt-get update -y",
      "apt-get install -y jenkins" );
    $this->uninstallCommands = array( "apt-get remove -y jenkins" );
    $this->programDataFolder = "/var/lib/jenkins"; // command and app dir name
    $this->programNameMachine = "jenkins"; // command and app dir name
    $this->programNameFriendly = " ! Jenkins !"; // 12 chars
    $this->programNameInstaller = "Jenkins";
    $this->initialize();
  }

}