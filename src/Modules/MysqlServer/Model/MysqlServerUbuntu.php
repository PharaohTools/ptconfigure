<?php

Namespace Model;

class MysqlServerUbuntu extends BaseLinuxApp {

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
        $newRootPass = $this->getNewRootPass();
        $this->autopilotDefiner = "MysqlServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "debconf-utils")) ),
            array("command"=> array(
                    "echo mysql-server mysql-server/root_password password $newRootPass | sudo debconf-set-selections",
                    "echo mysql-server mysql-server/root_password_again password $newRootPass | sudo debconf-set-selections" ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "mysql-client")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Apt", "mysql-server")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mysql-client")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "mysql-server")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Apt", "debconf-utils")) ),
        );
        $this->programDataFolder = "/opt/MysqlServer"; // command and app dir name
        $this->programNameMachine = "mysqlserver"; // command and app dir name
        $this->programNameFriendly = "MySQL Server!"; // 12 chars
        $this->programNameInstaller = "MySQL Server";
        $this->statusCommand = "mysqld --version" ;
        $this->versionInstalledCommand = "sudo apt-cache policy mysql-server" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy mysql-server" ;
        $this->versionLatestCommand = "sudo apt-cache policy mysql-server" ;
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["mysql-root-pass"])) {
            $newRootPass = $this->params["mysql-root-pass"] ; }
        else if (AppConfig::getProjectVariable("mysql-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("mysql-default-root-pass") ; }
        else {
            $newRootPass = "cleopatra" ; }
        return $newRootPass;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 24, 17) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 47, 17) ;
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 47, 17) ;
        return $done ;
    }

}
