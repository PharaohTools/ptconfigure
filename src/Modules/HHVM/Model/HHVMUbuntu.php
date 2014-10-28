<?php

Namespace Model;

//@todo if we can use a wget/binary method like selenium or gitbucket then we can easily use across other linux os
class HHVMUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("12.04", "14.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "HHVM";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands =
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "hhvm")) ) ;
        $this->programDataFolder = "/var/lib/hhvm"; // command and app dir name
        $this->programNameMachine = "hhvm"; // command and app dir name
        $this->programNameFriendly = " ! HHVM !"; // 12 chars
        $this->programNameInstaller = "HHVM";
        $this->statusCommand = "sudo hhvm --version" ;
        $this->versionInstalledCommand = "sudo apt-cache policy hhvm" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy hhvm" ;
        $this->versionLatestCommand = "sudo apt-cache policy hhvm" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $sys = new \Model\SystemDetectionAllOS();
        if (in_array($sys->version, array("14.04", "14.10"))) {
            $ray = array(
                array("command" => array(
                    "cd /tmp" ,
                    "wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | sudo apt-key add -",
                    "echo deb http://dl.hhvm.com/ubuntu trusty main | sudo tee /etc/apt/sources.list.d/hhvm.list",
                    "apt-get update -y" ) ),
                array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "hhvm")) ),
            ) ; }
        else {
            $ray = array(
                array("command" => array(
                    "cd /tmp" ,
                    "sudo add-apt-repository ppa:mapnik/boost",
                    "wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | sudo apt-key add -",
                    "echo deb http://dl.hhvm.com/ubuntu precise main | sudo tee /etc/apt/sources.list.d/hhvm.list",
                    "apt-get update -y" ) ),
                array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "hhvm")) ),
            ) ; }
        return $ray ;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 19, 12) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 45, 12) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 45, 12) ;
        return $done ;
    }

}