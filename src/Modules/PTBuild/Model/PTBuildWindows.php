<?php

Namespace Model;

class PTBuildWindows extends BasePHPWindowsApp {

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
        $this->autopilotDefiner = "PTBuild";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/ptbuild.git",
              "ptbuild",
              null // can be null for none
          )
        );
        $this->programNameMachine = "ptbuild"; // command and app dir name
        $this->programNameFriendly = " PTBuild! "; // 12 chars
        $this->programNameInstaller = "PTBuild - Update to latest version";
        $this->programExecutorTargetPath = 'ptbuild/src/Bootstrap.php';
        $this->initialize();
    }

}