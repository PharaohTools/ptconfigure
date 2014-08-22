<?php

Namespace Model;

//@todo if we can use a wget/binary method like selenium or gitbucket then we can easily use across other linux os
class PHPCIUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "PHPCI";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands = array( "apt-get remove -y jenkins" );
        $this->programDataFolder = "/var/lib/jenkins"; // command and app dir name
        $this->programNameMachine = "jenkins"; // command and app dir name
        $this->programNameFriendly = " ! PHPCI !"; // 12 chars
        $this->programNameInstaller = "PHPCI";
        $this->statusCommand = "sudo jenkins -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy jenkins" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy jenkins" ;
        $this->versionLatestCommand = "sudo apt-cache policy jenkins" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $ray = array(
            array("command" => array(
                "cd /tmp" ,
                "wget -q -O - http://pkg.jenkins-ci.org/debian/jenkins-ci.org.key | sudo apt-key add -",
                "echo deb http://pkg.jenkins-ci.org/debian binary/ > /etc/apt/sources.list.d/jenkins.list",
                "apt-get update -y",
                "apt-get install -y jenkins") )
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