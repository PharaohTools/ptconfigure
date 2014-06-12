<?php

Namespace Model;

class ThoughtWorksGoCentos extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Redhat") ;
    public $distros = array("CentOS") ;
    public $versions = array("5.9", "6.4", "6.5", "6.6", "6.7", "6.8", "6.9") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $newRootPass = $this->getNewRootPass();
        $this->autopilotDefiner = "ThoughtWorksGo";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "mysql-server")) ),
            array("command"=> array(
                "echo mysql-server mysql-server/root_password password $newRootPass | sudo debconf-set-selections",
                "echo mysql-server mysql-server/root_password_again password $newRootPass | sudo debconf-set-selections" ) ),
            array("command"=> array(
                "cd /tmp",
                "wget https://launchpad.net/codership-mysql/5.6/5.6.16-25.5/+download/mysql-server-wsrep-5.6.16-25.5-amd64.deb",
                "dpkg -i mysql-server-wsrep-5.6.16-25.5-amd64.deb" ) ),
            array("method"=> array("object" => $this, "method" => "packageAdd", "params" => array("Yum", "mysql-client")) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "mysql-client")) ),
            array("method"=> array("object" => $this, "method" => "packageRemove", "params" => array("Yum", "mysql-server")) ),
        );
        $this->programDataFolder = "/opt/ThoughtWorksGo"; // command and app dir name
        $this->programNameMachine = "mysqlservergalera"; // command and app dir name
        $this->programNameFriendly = "MySQL Server!"; // 12 chars
        $this->programNameInstaller = "MySQL Server";
        $this->statusCommand = "mysql --version" ;
        $this->versionInstalledCommand = "sudo apt-cache policy mysql-server" ; // @todo this is not centos compatible
        $this->versionRecommendedCommand = "sudo apt-cache policy mysql-server" ; // @todo this is not centos compatible
        $this->versionLatestCommand = "sudo apt-cache policy mysql-server" ; // @todo this is not centos compatible
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
        $done = substr($text, 27, 17) ; // @todo this work in ubuntu propbably not in centos
        return $done ;
    }

    public function versionLatestCommandTrimmer($text) {
        $done = substr($text, 64, 17) ; // @todo this work in ubuntu propbably not in centos
        return $done ;
    }

    public function versionRecommendedCommandTrimmer($text) {
        $done = substr($text, 64, 17) ; // @todo this work in ubuntu propbably not in centos
        return $done ;
    }

}