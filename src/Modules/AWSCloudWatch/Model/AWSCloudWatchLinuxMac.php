<?php

Namespace Model;

class AWSCloudWatchLinuxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux", "Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "AWSCloudWatch";
        $this->installCommands = array(
          "cd /tmp" ,
          "git clone https://github.com/phpengine/cleopatra-aws-cloudwatch aws-cloudwatch",
          "mkdir -p ****PROGDIR****",
          "mv /tmp/aws-cloudwatch/* ****PROGDIR****",
          "rm -rf /tmp/aws-cloudwatch/",
          // "cd ****PROGDIR****",
          // "java -jar selenium-server.jar >/dev/null 2>&1 </dev/null &"
        );
        $this->uninstallCommands = array("rm -rf ****PROGDIR****");
        $this->programDataFolder = "/opt/aws-cloudwatch"; // command and app dir name
        $this->programNameMachine = "aws-cloudwatch"; // command and app dir name
        $this->programNameFriendly = "AWS Cld Watch"; // 12 chars
        $this->programNameInstaller = "AWSCloudWatch";
        $this->programExecutorFolder = "/usr/bin";
        $this->programExecutorTargetPath = "";
        $this->programExecutorCommand = 'java -jar ' . $this->programDataFolder . '/bin/aws-cloudwatch.jar';
        $this->registeredPostInstallFunctions = array("deleteExecutorIfExists", "saveExecutorFile");
        $this->initialize();
      }

}