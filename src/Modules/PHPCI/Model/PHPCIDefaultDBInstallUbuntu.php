<?php

Namespace Model;

//@todo if we can use a wget/binary method like selenium or gitbucket then we can easily use across other linux os
class PHPCIDefaultDBInstallUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("Debian") ;
    public $distros = array("Ubuntu") ;
    public $versions = array("11.04", "11.10", "12.04", "12.10", "13.04") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("DefaultDBInstall") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPCI";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands = array( "apt-get remove -y phpci" );
        $this->programDataFolder = "/opt/phpci"; // command and app dir name
        $this->programNameMachine = "phpci"; // command and app dir name
        $this->programNameFriendly = " ! PHPCI !"; // 12 chars
        $this->programNameInstaller = "PHPCI";
        $this->statusCommand = "sudo phpci -v" ;
        $this->versionInstalledCommand = "sudo apt-cache policy phpci" ;
        $this->versionRecommendedCommand = "sudo apt-cache policy phpci" ;
        $this->versionLatestCommand = "sudo apt-cache policy phpci" ;
        $this->initialize();
    }

    protected function getInstallCommands() {
        $ray = array(
            array("method"=> array( "object" => $this, "method" => "packageAdd", "params" => array("Apt", array("php5-mcrypt"))) ),
            array("method"=> array( "object" => $this, "method" => "getDBAdminUser", "params" => array("Apt", array("php5-mcrypt"))) ),
            array("method"=> array( "object" => $this, "method" => "getDBAdminPass", "params" => array("Apt", array("php5-mcrypt"))) ),
            array("method"=> array( "object" => $this, "method" => "packageAdd", "params" => array("Apt", array("php5-mcrypt"))) ),
        ) ;
        return $ray ;
    }

    public function ensureMySQL() {
        // @todo add logging
        $mysqlFactory = new \Model\MysqlServer();
        $mysql = $mysqlFactory->getModel($this->params);
        $mysql->ensureInstalled();
    }

    public function getDBAdminUser() {
        // @todo add logging
        $mysqlFactory = new \Model\MysqlServer();
        $mysql = $mysqlFactory->getModel($this->params);
        $mysql->ensureInstalled();
    }

    public function getDBAdminPass() {
        // @todo add logging
        $mysqlFactory = new \Model\MysqlServer();
        $mysql = $mysqlFactory->getModel($this->params);
        $mysql->ensureInstalled();
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