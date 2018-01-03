<?php

Namespace Model;

class PTDeployLinuxMac extends BasePHPApp {

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
        $this->autopilotDefiner = "PTDeploy";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptdeploy.git",
              "ptdeploy",
              null // can be null for none
          )
        );
        $this->programNameMachine = "ptdeploy"; // command and app dir name
        $this->programNameFriendly = " PTDeploy "; // 12 chars
        $this->programNameInstaller = "PTDeploy";
        $this->programExecutorTargetPath = 'ptdeploy/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

}