<?php

Namespace Model;

class PhlagrantWindows extends BasePHPWindowsApp {

    // Compatibility
    public $os = array("Windows", "WINNT") ;
    public $linuxType = array("None") ;
    public $distros = array("None") ;
    public $versions = array(array("5" => "+")) ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Phlagrant";
        $this->fileSources = array(
          array(
            "http://git.pharaohtools.com/git/phpengine/phlagrant.git",
            "phlagrant",
            null // can be null for none
          )
        );
        $this->programNameMachine = "phlagrant"; // command and app dir name
        $this->programNameFriendly = " Phlagrant "; // 12 chars
        $this->programNameInstaller = "Phlagrant";
        $this->programExecutorTargetPath = 'phlagrant/src/Bootstrap.php';
        $this->initialize();
    }

}