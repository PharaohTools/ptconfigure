<?php

Namespace Model;

class MysqlServerCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $newRootPass = $this->getNewRootPass();
        $this->autopilotDefiner = "MysqlServer";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "debconf-utils")) ),
            // @todo silent password
//            array("command"=> array(
//                    "echo mysql-server mysql-server/root_password password $newRootPass | ".SUDOPREFIX." debconf-set-selections",
//                    "echo mysql-server mysql-server/root_password_again password $newRootPass | ".SUDOPREFIX." debconf-set-selections" ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "mysql-client")) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "mysql-server")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "mysql-client")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "mysql-server")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "debconf-utils")) ),
        );
        $this->programDataFolder = "/opt/MysqlServer"; // command and app dir name
        $this->programNameMachine = "mysqlserver"; // command and app dir name
        $this->programNameFriendly = "MySQL Server!"; // 12 chars
        $this->programNameInstaller = "MySQL Server";
        $this->statusCommand = "mysqld --version" ;
        $this->versionInstalledCommand = SUDOPREFIX."apt-cache policy mysql-server" ;
        $this->versionRecommendedCommand = SUDOPREFIX."apt-cache policy mysql-server" ;
        $this->versionLatestCommand = SUDOPREFIX."apt-cache policy mysql-server" ;
        $this->initialize();
    }

    private function getNewRootPass() {
        if (isset($this->params["mysql-root-pass"])) {
            $newRootPass = $this->params["mysql-root-pass"] ; }
        else if (AppConfig::getProjectVariable("mysql-default-root-pass") != "") {
            $newRootPass = AppConfig::getProjectVariable("mysql-default-root-pass") ; }
        else {
            $newRootPass = "ptconfigure" ; }
        return $newRootPass;
    }

    public function versionInstalledCommandTrimmer($text) {
        $done = substr($text, 27, 23) ;
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        if ($this->askStatus() == true) { $done = substr($text, 64, 23) ; }
        else { $done = substr($text, 47, 23) ;}
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        if ($this->askStatus() == true) { $done = substr($text, 64, 23) ; }
        else { $done = substr($text, 47, 23) ;}
        return $done ;
    }

}
