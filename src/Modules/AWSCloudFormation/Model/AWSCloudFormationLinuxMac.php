<?php

Namespace Model;

class AWSCloudFormationLinuxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "AWSCloudFormation";
        $this->installCommands = array(
          "cd /tmp" ,
          "git clone https://github.com/phpengine/aws-cloudformation aws-cloudformation",
          "mkdir -p ****PROGDIR****",
          "mv /tmp/aws-cloudformation/* ****PROGDIR****",
          "rm -rf /tmp/aws-cloudformation/",
          // "cd ****PROGDIR****",
          // "java -jar selenium-server.jar >/dev/null 2>&1 </dev/null &"
        );
        $this->uninstallCommands = array("rm -rf ****PROGDIR****");
        $this->programDataFolder = "/opt/aws-cloudformation"; // command and app dir name
        $this->programNameMachine = "aws-cloudformation"; // command and app dir name
        $this->programNameFriendly = "AWS Cld Formation"; // 12 chars
        $this->programNameInstaller = "AWSCloudFormation";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "";
        $this->programExecutorCommand = 'java -jar ' . $this->programDataFolder . '/bin/aws-cloudformation.jar';
        $this->registeredPostInstallFunctions = array("deleteExecutorIfExists", "saveExecutorFile");
        $this->initialize();
      }

}