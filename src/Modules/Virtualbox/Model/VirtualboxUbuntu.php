<?php

Namespace Model;

//@todo if we can use a wget/binary method like selenium or gitbucket then we can easily use across other linux os
class VirtualboxUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Virtualbox";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands = array( "apt-get remove -y virtualbox" );
        $this->programDataFolder = "/var/lib/virtualbox"; // command and app dir name
        $this->programNameMachine = "virtualbox"; // command and app dir name
        $this->programNameFriendly = " ! Virtualbox !"; // 12 chars
        $this->programNameInstaller = "Virtualbox";
        $this->statusCommand = "sudo virtualbox -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy virtualbox" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy virtualbox" ;
        $this->versionLatestCommand = "sudo apt-cache policy virtualbox" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $ray = array(
            array("command" => array( "apt-get install -y virtualbox") )
        ) ;
        if (isset($this->params["with-http-port-proxy"]) && $this->params["with-http-port-proxy"]==true) {
            $dapperAuto = $this->getDapperAutoPath() ;
            $ray[0]["command"][5] = "sudo dapperstrano autopilot execute --autopilot-file=$dapperAuto" ; }
        return $ray ;
    }

    private function getDapperAutoPath() {
        $path = dirname(dirname(__FILE__)).'/Autopilots/Dapperstrano/proxy-8080-to-80.php' ;
        return $path ;
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