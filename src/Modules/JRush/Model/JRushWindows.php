<?php

Namespace Model;

class JRushWindows extends BasePHPWindowsApp {

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
        $this->autopilotDefiner = "JRush";
        $this->fileSources = array(
          array(
              "https://github.com/PharaohTools/jrush.git",
              "jrush",
              null // can be null for none
          )
        );
        $this->programNameMachine = "jrush"; // command and app dir name
        $this->programNameFriendly = " JRush! "; // 12 chars
        $this->programNameInstaller = "JRush - Update to latest version";
        $this->programExecutorTargetPath = 'jrush/src/Bootstrap.php';
        $this->initialize();
    }

}