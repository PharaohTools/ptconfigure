<?php

Namespace Model;

class VirtualboxMac extends BaseLinuxApp {

    // Compatibility
    public $os = array("Darwin") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Virtualbox";
        $this->installCommands = $this->getInstallCommands() ;
        // http://download.virtualbox.org/virtualbox/4.3.28/VirtualBox-4.3.28-100309-OSX.dmg
        $this->uninstallCommands = array(
            array("command" => array( SUDOPREFIX."apt-get remove -y virtualbox") ) ) ;
        $this->programDataFolder = "/var/lib/virtualbox"; // command and app dir name
        $this->programNameMachine = "virtualbox"; // command and app dir name
        $this->programNameFriendly = " ! Virtualbox !"; // 12 chars
        $this->programNameInstaller = "Virtualbox";
        $this->statusCommand = "command vboxmanage" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy virtualbox" ;
        $this->initialize();
    }

    // @todo this should definitely be using a package manager module
    protected function getInstallCommands() {
        $dmgFile = BASE_TEMP_DIR."virtualbox.dmg" ;
        $ray = array(
            array("command" => array( 'curl "http://download.virtualbox.org/virtualbox/4.3.28/VirtualBox-4.3.28-100309-OSX.dmg" -o "'.$dmgFile.'"') ),
            array("command" => array( SUDOPREFIX."hdiutil attach $dmgFile") ),
            array("command" => array( 'sudo installer -pkg /Volumes/VirtualBox/VirtualBox.pkg -target /Volumes/Macintosh\ HD') ),
        ) ;
        if (isset($this->params["with-guest-additions"]) && $this->params["with-guest-additions"]==true) {
            array_push($ray, array("command" => array( SUDOPREFIX."apt-get install -y virtualbox-guest-additions-iso") ) ) ; }
        return $ray ;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 23, 15) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 42, 23) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 42, 23) ;
        return $done ;
    }

}