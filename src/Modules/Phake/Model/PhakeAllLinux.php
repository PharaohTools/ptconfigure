<?php

Namespace Model;

class PhakeAllLinux extends BasePHPApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Phake";
        $this->fileSources = array(
          array(
              "http://github.com/jaz303/phake.git",
              "phake",
              null // can be null for none
          )
        );
        $this->programNameMachine = "phake"; // command and app dir name
        $this->programNameFriendly = " Phake "; // 12 chars
        $this->programNameInstaller = "Phake";
        $this->programExecutorTargetPath = 'phake/bin/phake';
        $this->initialize();
    }

}