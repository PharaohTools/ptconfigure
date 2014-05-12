<?php

Namespace Model;

class MysqlAdminsAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("Linux") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    private $mysqlNewAdminUser;
    private $mysqlNewAdminPass;
    private $mysqlRootUser;
    private $mysqlRootPass;
    private $dbHost;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "MysqlAdmins";
        $this->installCommands = array(
            array("method"=> array("object" => $this, "method" => "askForMysqlRootUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlRootPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlNewAdminUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlNewAdminPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlHost", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doInstallCommands", "params" => array()) ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForMysqlRootUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlRootPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlNewAdminUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlNewAdminPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlHost", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "doUninstallCommands", "params" => array()) ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "mysqladmins"; // command and app dir name
        $this->programNameFriendly = "MySQL Admins!"; // 12 chars
        $this->programNameInstaller = "Admin User for MySQL";
        $this->initialize();
    }

    protected function doInstallCommands() {
        $command  = 'mysql -h'.$this->dbHost.' -u'.$this->mysqlRootUser.' ';
        if (strlen($this->mysqlRootPass) > 0) {$command .= '-p'.$this->mysqlRootPass.' '; }
        $command .= ' < /tmp/mysql-adminshcript.sql' ;
        $sqlCommand = 'GRANT ALL PRIVILEGES ON *.* TO \''.$this->mysqlNewAdminUser.'\'@\''.$this->dbHost.'\' ';
        $sqlCommand .= 'IDENTIFIED BY \''.$this->mysqlNewAdminPass.'\' WITH GRANT OPTION;';
        $comms = array(
            'echo "'.$sqlCommand.'" > /tmp/mysql-adminshcript.sql ',
            $command,
            'rm /tmp/mysql-adminshcript.sql' );
        $this->executeAsShell($comms) ;
    }

    protected function doUninstallCommands() {
        $command  = 'mysql -h'.$this->dbHost.' -u'.$this->mysqlRootUser.' ';
        if (strlen($this->mysqlRootPass) > 0) {$command .= '-p'.$this->mysqlRootPass.' '; }
        $command .= ' < /tmp/mysql-adminshcript.sql' ;
        $sqlCommand = 'DROP USER \''.$this->mysqlNewAdminUser.'\'@\''.$this->dbHost.'\';';
        $comms = array(
            'echo "'.$sqlCommand.'" > /tmp/mysql-adminshcript.sql ',
            $command,
            'rm /tmp/mysql-adminshcript.sql' );
        $this->executeAsShell($comms) ;
    }

    public function askForMysqlNewAdminUserName() {
        if (isset($this->params["new-user"])) {
            $this->mysqlNewAdminUser = $this->params["new-user"]; }
        else {
            $question = "Enter MySQL New Admin User:";
            $this->mysqlNewAdminUser = self::askForInput($question, true); }
    }

    public function askForMysqlNewAdminPass() {
        if (isset($this->params["new-pass"])) {
            $this->mysqlNewAdminPass = $this->params["new-pass"]; }
        else {
            $question = "Enter MySQL New Admin Pass:";
            $this->mysqlNewAdminPass = self::askForInput($question, true); }
    }

    public function askForMysqlRootUserName(){
        if (isset($this->params["root-user"])) {
            $this->mysqlRootUser = $this->params["root-user"]; }
        else {
            $question = "Enter MySQL Root User:";
            $this->mysqlRootUser = self::askForInput($question, true); }
    }

    public function askForMysqlRootPass(){
        if (isset($this->params["root-pass"])) {
            $this->mysqlRootPass = $this->params["root-pass"]; }
        else {
            $question = "Enter MySQL Root Pass:";
            $this->mysqlRootPass = self::askForInput($question, true); }
    }

    public function askForMysqlHost(){
        if (isset($this->params["mysql-host"])) {
            $this->dbHost = $this->params["mysql-host"]; }
        else {
            $question = 'Enter MySQL Host: Enter nothing for 127.0.0.1';
            $input = self::askForInput($question) ;
            $this->dbHost = ($input=="") ? '127.0.0.1' : $input ; }
    }

}