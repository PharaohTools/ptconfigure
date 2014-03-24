<?php

Namespace Model;

class MediaToolsUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "MediaTools";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", array("vlc", "libdvdread4"))) ),
            array("command"=> "sh /usr/share/doc/libdvdread4/install-css.sh")
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "vlc", "libdvdread4")) ),
        );
        $this->programDataFolder = "/opt/MediaTools"; // command and app dir name
        $this->programNameMachine = "mediatools"; // command and app dir name
        $this->programNameFriendly = "Media Tools!"; // 12 chars
        $this->programNameInstaller = "Media Tools";
        $this->initialize();
    }

    public function askStatus() {
        $stat1 = $this->askStatusByArray( array("vlc") ) ;
        $aptFactory = new Apt();
        $aptModel = $aptFactory->getModel($this->params) ;
        $stat2 = $aptModel->isInstalled("libdvdread4") ;
        return ($stat1==true && $stat2==true) ? true : false ;
    }

}