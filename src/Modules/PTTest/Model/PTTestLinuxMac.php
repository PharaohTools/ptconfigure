<?php

Namespace Model;

class PTTestLinuxMac extends BasePHPApp {

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
        $this->autopilotDefiner = "PTTest";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/pttest.git",
              "pttest",
              null // can be null for none
          )
        );
        $this->programNameMachine = "pttest"; // command and app dir name
        $this->programNameFriendly = " PTTest! "; // 12 chars
        $this->programNameInstaller = "PTTest - Update to latest version";
        $this->programExecutorTargetPath = 'pttest/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

}