<?php

Namespace Model;

class PythonCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("5.8") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "Python";
    $this->installCommands = array("yum install -y python python-docutils");
    $this->uninstallCommands = array("yum remove -y python python-docutils");
    $this->programDataFolder = "";
    $this->programNameMachine = "python"; // command and app dir name
    $this->programNameFriendly = "!Python!!"; // 12 chars
    $this->programNameInstaller = "Python";
    $this->initialize();
  }

}