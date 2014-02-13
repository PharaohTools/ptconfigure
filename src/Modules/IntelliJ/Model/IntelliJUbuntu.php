<?php

Namespace Model;

class IntelliJUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("64") ;

    // Model Group
    public $modelGroup = array("Default") ;

  public function __construct($params) {
    parent::__construct($params);
    $this->autopilotDefiner = "IntelliJ";
    $this->installCommands = array(
      "cd /tmp" ,
      "git clone https://github.com/phpengine/cleopatra-intellij intellij",
      "rm -rf ****PROGDIR****",
      "mkdir -p ****PROGDIR****",
      "mv /tmp/intellij/* ****PROGDIR****",
      "chmod -R 777 ****PROGDIR****",
      "rm -rf /tmp/intellij" );
    $this->uninstallCommands = array(
      "rm -rf ****PROGDIR****",
      "rm -rf ****PROG EXECUTOR****", );
    $this->programDataFolder = "/opt/intellij"; // command and app dir name
    $this->programNameMachine = "intellij"; // command and app dir name
    $this->programNameFriendly = "Intelli J 12"; // 12 chars
    $this->programNameInstaller = "Intelli J 12";
    $this->programExecutorFolder = "/usr/bin";
    $this->programExecutorTargetPath = "intellij.sh";
    $this->programExecutorCommand = $this->programDataFolder.'/'.$this->programExecutorTargetPath;
    $this->registeredPostInstallFunctions = array("deleteExecutorIfExists",
      "saveExecutorFile");
    $this->initialize();
  }

}
