<?php

Namespace Model;

class DapperstranoAllLinux extends BasePHPApp {

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
        $this->autopilotDefiner = "Dapperstrano";
        $this->fileSources = array(
          array(
              "http://git.pharaohtools.com/git/phpengine/dapperstrano.git",
              "dapperstrano",
              null // can be null for none
          )
        );
        $this->programNameMachine = "dapperstrano"; // command and app dir name
        $this->programNameFriendly = " Dapperstrano "; // 12 chars
        $this->programNameInstaller = "Dapperstrano";
        $this->programExecutorTargetPath = 'dapperstrano/src/Bootstrap.php';
        $this->initialize();
    }

}