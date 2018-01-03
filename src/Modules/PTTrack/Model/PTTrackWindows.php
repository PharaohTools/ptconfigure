<?php

Namespace Model;

class PTTrackWindows extends BasePHPWindowsApp {

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
        $this->autopilotDefiner = "PTTrack";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/pttrack.git",
              "pttrack",
              null // can be null for none
          )
        );
        $this->programNameMachine = "pttrack"; // command and app dir name
        $this->programNameFriendly = " PTTrack! "; // 12 chars
        $this->programNameInstaller = "PTTrack - Update to latest version";
        $this->programExecutorTargetPath = 'pttrack/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

}