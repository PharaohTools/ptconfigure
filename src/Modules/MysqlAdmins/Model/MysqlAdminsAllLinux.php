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
            array("command"=> $this->getInstallCommands() ),
        );
        $this->uninstallCommands = array(
            array("method"=> array("object" => $this, "method" => "askForMysqlRootUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlRootPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlNewAdminUserName", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlNewAdminPass", "params" => array()) ),
            array("method"=> array("object" => $this, "method" => "askForMysqlHost", "params" => array()) ),
            array("command"=> $this->getInstallCommands() ),
        );
        $this->programDataFolder = "";
        $this->programNameMachine = "mysqladmins"; // command and app dir name
        $this->programNameFriendly = "MySQL Admins!"; // 12 chars
        $this->programNameInstaller = "Admin User for MySQL";
        $this->initialize();
    }

    protected function getInstallCommands() {
        $command  = 'mysql -h'.$this->dbHost.' -u'.$this->mysqlRootUser.' ';
        if (strlen($this->mysqlRootPass) > 0) {$command .= '-p'.$this->mysqlRootPass.' '; }
        $command .= ' < /tmp/mysql-adminshcript.sql' ;
        $sqlCommand = 'GRANT ALL PRIVILEGES ON *.* TO \''.$this->mysqlNewAdminUser.'\'@\''.$this->dbHost.'\' ';
        $sqlCommand .= 'IDENTIFIED BY \''.$this->mysqlNewAdminPass.'\' WITH GRANT OPTION;';
        return array(
            'echo "'.$sqlCommand.'" > /tmp/mysql-adminshcript.sql ',
            $command,
            'rm /tmp/mysql-adminshcript.sql'
        );
    }

    public function askForMysqlNewAdminUserName($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlNewAdminUser"} ) {
            $this->mysqlNewAdminUser = $autoPilot->{$this->autopilotDefiner."MysqlNewAdminUser"}; }
        else {
            $question = "Enter MySQL New Admin User:";
            $this->mysqlNewAdminUser = self::askForInput($question, true); }
    }

    public function askForMysqlNewAdminPass($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlNewAdminPass"} ) {
            $this->mysqlNewAdminPass = $autoPilot->{$this->autopilotDefiner."MysqlNewAdminPass"}; }
        else {
            $question = "Enter MySQL New Admin Pass:";
            $this->mysqlNewAdminPass = self::askForInput($question, true); }
    }

    public function askForMysqlRootUserName($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlRootUser"} ) {
            $this->mysqlRootUser = $autoPilot->{$this->autopilotDefiner."MysqlRootUser"}; }
        else {
            $question = "Enter MySQL Root User:";
            $this->mysqlRootUser = self::askForInput($question, true); }
    }

    public function askForMysqlRootPass($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlRootPass"} ) {
            $this->mysqlRootPass = $autoPilot->{$this->autopilotDefiner."MysqlRootPass"}; }
        else {
            $question = "Enter MySQL Root Pass:";
            $this->mysqlRootPass = self::askForInput($question, true); }
    }

    public function askForMysqlHost($autoPilot=null){
        if (isset($autoPilot) &&
            $autoPilot->{$this->autopilotDefiner."MysqlHost"} ) {
            $this->dbHost = $autoPilot->{$this->autopilotDefiner."MysqlHost"}; }
        else {
            $question = 'Enter MySQL Host: Enter nothing for 127.0.0.1';
            $input = self::askForInput($question) ;
            $this->dbHost = ($input=="") ? '127.0.0.1' : $input ; }
    }

}