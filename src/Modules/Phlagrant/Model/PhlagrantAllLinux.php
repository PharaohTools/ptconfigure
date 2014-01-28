<?php

Namespace Model;

class PhlagrantAllLinux extends BasePHPApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Phlagrant";
        $this->fileSources = array(
          array(
            "https://github.com/phpengine/phlagrant.git",
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