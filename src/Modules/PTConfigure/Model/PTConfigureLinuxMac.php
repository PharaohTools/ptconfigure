<?php

Namespace Model;

class PTConfigureLinuxMac extends BasePHPApp {

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
        $this->autopilotDefiner = "PTConfigure";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptconfigure.git",
              "ptconfigure",
              null // can be null for none
          )
        );
        $this->programNameMachine = "ptconfigure"; // command and app dir name
        $this->programNameFriendly = " PTConfigure! "; // 12 chars
        $this->programNameInstaller = "PTConfigure - Update to latest version";
        $this->programExecutorTargetPath = 'ptconfigure/src/Bootstrap.php';
        $this->initialize();
    }

}