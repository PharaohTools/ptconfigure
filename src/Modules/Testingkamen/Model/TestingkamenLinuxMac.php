<?php

Namespace Model;

class TestingkamenLinuxMac extends BasePHPApp {

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
        $this->autopilotDefiner = "Testingkamen";
        $this->fileSources = array(
          array(
              "http://git.pharoah-tools.org.uk/git/phpengine/testingkamen.git",
              "testingkamen",
              null // can be null for none
          )
        );
        $this->programNameMachine = "testingkamen"; // command and app dir name
        $this->programNameFriendly = " Testingkamen! "; // 12 chars
        $this->programNameInstaller = "Testingkamen - Update to latest version";
        $this->programExecutorTargetPath = 'testingkamen/src/Bootstrap.php';
        $this->initialize();
    }

}