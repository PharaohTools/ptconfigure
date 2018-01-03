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

    // @todo needs status and version, or is that extended from basephpapp?
    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Composer";
        $this->fileSources = array(
          array(
              "https://github.com/phpengine/composer-phar.git",
              "composer",
              null // can be null for none
          )
        );
        $this->programNameMachine = "composer"; // command and app dir name
        $this->programNameFriendly = " Composer! "; // 12 chars
        $this->programNameInstaller = "Composer - Update to latest version";
        $this->programExecutorTargetPath = 'composer'.DIRECTORY_SEPARATOR.'composer.phar';
        $this->statusCommand = 'composer -q' ;
        $this->initialize();
    }

    public function setpostinstallCommands() {
        $ray = array( ) ;
        $ray[]["command"][] = SUDOPREFIX." /opt/composer/composer/composer.phar selfupdate" ;
        $this->postinstallCommands = $ray ;
        return $ray ;
    }

}