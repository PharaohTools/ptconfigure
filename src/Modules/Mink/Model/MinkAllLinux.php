<?php

Namespace Model;

class MinkAllLinux extends BasePHPApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian", "Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Mink";
        $this->fileSources = array(
          array(
            "https://github.com/Behat/Mink",
            "mink",
            null // can be null for none
          )
        );

        $this->setExtraCommands();
        $this->programNameMachine = "mink"; // command and app dir name
        $this->programNameFriendly = " Mink "; // 12 chars
        $this->programNameInstaller = "Mink";
        $this->programExecutorTargetPath = 'mink/bin/release';
        $this->initialize();
    }

    protected function setExtraCommands() {
        $templateComposerJson = str_replace("Model", "Templates", dirname(__FILE__)) ;
        $templateComposerJson .= DIRECTORY_SEPARATOR . "mink-browser-drivers-composer.json.php" ;
        $this->extraCommandsArray = array(
            "rm ****PROGDIR****/mink/composer.lock",
            "cd ****PROGDIR****/mink",
            "cp $templateComposerJson ****PROGDIR****/mink/composer.json",
            "curl -s http://getcomposer.org/installer | php" ,
            "php composer.phar install" );
    }

}