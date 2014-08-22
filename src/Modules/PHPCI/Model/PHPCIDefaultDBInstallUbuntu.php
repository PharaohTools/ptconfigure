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

    public $dbRootUser ;
    public $dbRootPass ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "PHPCI";
        $this->installCommands = $this->getInstallCommands();
        $this->uninstallCommands = array( "apt-get remove -y phpci" );
        $this->programDataFolder = "/opt/phpci"; // command and app dir name
        $this->programNameMachine = "phpci"; // command and app dir name
        $this->programNameFriendly = " ! PHPCI !"; // 12 chars
        $this->programNameInstaller = "PHPCI";
        $this->initialize();
    }

    protected function getInstallCommands() {
        $ray = array(
            array("method"=> array( "object" => $this, "method" => "askForRootDBUser", "params" => array()) ),
            array("method"=> array( "object" => $this, "method" => "askForRootDBPass", "params" => array()) ),
            array("method"=> array( "object" => $this, "method" => "doDbInstall", "params" => array()) ),
        ) ;
        return $ray ;
    }

    public function doDBInstall() {
        $command = 'sudo dapperstrano dbinstall install --yes --mysql-host="127.0.0.1" --mysql-admin-user="'.$this->dbRootUser.'"' .
            ' --mysql-admin-pass="'.$this->dbRootPass.'" --mysql-user="phpci" --mysql-pass="phpci_pass" --mysql-db="phpci"' .
            ' --parent-path="/opt/cleopatra/cleopatra/src/Modules/PHPCI/" --db-file-path="db/database.sql"' ;
        self::executeAndOutput($command);
    }

    public function askForRootDBUser(){
        if (isset($this->params["mysql-admin-user"])) { $this->dbRootUser = $this->params["mysql-admin-user"] ; }
        if (isset($this->params["guess"])) {
            $this->dbRootUser = "root" ;
            return ; }
        $question = 'What\'s the MySQL Admin User?';
        $this->dbRootUser = self::askForInput($question, true);
    }

    public function askForRootDBPass(){
        if (isset($this->params["mysql-admin-pass"])) { $this->dbRootPass = $this->params["mysql-admin-pass"] ; }
        if (isset($this->params["guess"])) {
            $this->dbRootPass = "cleopatra" ;
            return ; }
        $question = 'What\'s the MySQL Admin Password?';
        $this->dbRootPass = self::askForInput($question, true);
    }


}