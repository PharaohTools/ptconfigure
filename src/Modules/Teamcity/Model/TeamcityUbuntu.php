<?php

Namespace Model;

//@todo if we can use a wget/binary method like selenium or gitbucket then we can easily use across other linux os
class TeamcityUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "Teamcity";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "executeDependencies", "params" => array()) ),
            array("command"=> array(
                "cd /tmp",
                "wget http://download.jetbrains.com/teamcity/TeamCity-8.1.3.tar.gz",
                "tar -xvf TeamCity-8.1.3.tar.gz",
            ) ),
        );
        $this->uninstallCommands = array( "apt-get remove -y teamcity" );
        $this->programDataFolder = "/var/lib/teamcity"; // command and app dir name
        $this->programNameMachine = "teamcity"; // command and app dir name
        $this->programNameFriendly = " ! Teamcity !"; // 12 chars
        $this->programNameInstaller = "Teamcity";
        $this->statusCommand = SUDOPREFIX."teamcity -v" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy teamcity" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy teamcity" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy teamcity" ;
        $this->initialize();
    }

    public function executeDependencies() {
        $tempVersion = $this->params["version"] ;
        unset($this->params["version"]) ;
        $javaFactory = new \Model\Java();
        $java = $javaFactory->getModel($this->params);
        $java->ensureInstalled();
        $this->params["version"] = $tempVersion ;
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