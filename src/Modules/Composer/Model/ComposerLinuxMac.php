<?php

Namespace Model;

class ComposerLinuxMac extends BasePHPApp {

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
        $this->autopilotDefiner = "Composer";
        $this->fileSources = array(
          array(
              "http://git.pharoah-tools.org.uk/git/phpengine/cleopatra.git",
              "cleopatra",
              null // can be null for none
          )
        );
        $this->programNameMachine = "cleopatra"; // command and app dir name
        $this->programNameFriendly = " Composer! "; // 12 chars
        $this->programNameInstaller = "Composer - Update to latest version";
        $this->programExecutorTargetPath = 'cleopatra/src/Bootstrap.php';
        $this->initialize();
    }

}