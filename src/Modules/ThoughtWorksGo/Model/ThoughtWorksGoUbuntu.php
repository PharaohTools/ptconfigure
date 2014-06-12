<?php

Namespace Model;

class ThoughtWorksGoUbuntu extends BaseLinuxApp {

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
        $this->autopilotDefiner = "ThoughtWorksGo";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mysql-client")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mysql-server")) ),
        );
        $this->programDataFolder = "/opt/ThoughtWorksGo"; // command and app dir name
        $this->programNameMachine = "thoughtworksgo"; // command and app dir name
        $this->programNameFriendly = "ThoughtWorks Go!"; // 12 chars
        $this->programNameInstaller = "ThoughtWorks Go";
        // @todo none of the below will work
        $this->statusCommand = "mysql --version" ;
        $this->versionInstalledCommand = "sudo apt-cache policy mysql-server" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy mysql-server" ;
        $this->versionLatestCommand = "sudo apt-cache policy mysql-server" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $installCommands = array() ;

        if (isset($this->params["install-server"])) {
            $installCommands = array_merge($installCommands,  array(
                array("command"=> array(
                    "cd /tmp",
                    "wget http://download01.thoughtworks.com/go/14.1.0/ga/go-server-14.1.0-18882.deb",
                    "dpkg -i go-server-14.1.0-18882.deb",
                    "rm go-server-14.1.0-18882.deb"
                ) ),
            ) );
        }
        if (isset($this->params["install-agent"])) {
            $installCommands = array_merge($installCommands,  array(
                array("command"=> array(
                    "cd /tmp",
                    "wget http://download01.thoughtworks.com/go/14.1.0/ga/go-agent-14.1.0-18882.deb",
                    "dpkg -i agent-14.1.0-18882.deb",
                    "rm agent-14.1.0-18882.deb"
                ) ),
            ) );
        }
        return $installCommands;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 27, 17) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 64, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 64, 17) ;
        return $done ;
    }

}