<?php

Namespace Model;

class PTVirtualizeWindows extends BasePHPWindowsApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("None") ;
    public $distros = array("None") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PTVirtualize";
        $this->fileSources = array(
          array(
            "https://github.com/PharaohTools/ptvirtualize.git",
            "ptvirtualize",
            null // can be null for none
          )
        );
        $this->programNameMachine = "ptvirtualize"; // command and app dir name
        $this->programNameFriendly = " PTVirtualize "; // 12 chars
        $this->programNameInstaller = "PTVirtualize";
        $this->programExecutorTargetPath = 'ptvirtualize/src/Bootstrap.php';
        $this->statusCommand = $this->programNameMachine.' --quiet > /dev/null' ;
        $this->initialize();
    }

}