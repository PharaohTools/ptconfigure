<?php

Namespace Model;

class PharaohEnterpriseWindows extends BasePHPWindowsApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PharaohEnterprise";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/PharaohEnterprise.git",
              "PharaohEnterprise",
              null // can be null for none
          )
        );
        $this->programNameMachine = "PharaohEnterprise"; // command and app dir name
        $this->programNameFriendly = " PharaohEnterprise! "; // 12 chars
        $this->programNameInstaller = "PharaohEnterprise - Update to latest version";
        $this->programExecutorTargetPath = 'PharaohEnterprise/src/Bootstrap.php';
        $this->initialize();
    }

}