<?php

Namespace Model;

class DBInstallAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Installer") ;

    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbName ;
    private $dbRootUser ;
    private $dbRootPass ;

    public function runAutoPilot($autoPilot){
        $this->runAutoPilotDBRemoval($autoPilot);
        $this->runAutoPilotDBInstallation($autoPilot);
        return true;
    }

    public function askWhetherToInstallDB(\Model\DBConfigure $dbConfigObject=null){
        if ($dbConfigObject!=null) { return $this->performDBInstallation($dbConfigObject); }
        else { return $this->performDBInstallation(); }
    }

    public function askWhetherToDropDB(){
        return $this->performDBDrop();
    }

    public function askWhetherToAddUser(){
        return $this->performAddUser();
    }

    public function askWhetherToDropUser(){
        return $this->performDropUser();
    }

    private function performDBInstallation(\Model\DBConfigure $dbConfigObject=null){
        if ($dbConfigObject!==null) {
            return $this->performDBInstallationWithConfig($dbConfigObject) ; }
        else {
            return $this->performDBInstallationWithNoConfig() ; }
    }

    private function performDBInstallationWithConfig(\Model\DBConfigure $dbConfigObject) {
        if ( !$this->askForDBInstall() ) { return false; }
        $this->dbHost = $dbConfigObject->getProperty("dbHost");
        $this->dbUser = $dbConfigObject->getProperty("dbUser");
        $this->dbPass = $dbConfigObject->getProperty("dbPass");
        $this->dbName = $dbConfigObject->getProperty("dbName");
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; }
            if (!$this->useRootToSetUpUserAndDb() ) { return "You declined using root"; }
            $this->dbRootUser = $this->askForRootDBUser();
            $this->dbRootPass = $this->askForRootDBPass();
            $this->databaseCreator();
            $this->userCreator(); }
        $this->sqlInstaller();
        return "Seems Fine...";
    }

    private function performDBInstallationWithNoConfig() {
        if ( !$this->askForDBInstall() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBFreeFormName();
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; }
            if (!$this->useRootToSetUpUserAndDb() ) { return "You declined using root"; }
            $this->databaseCreator();
            $this->userCreator(); }
        $this->sqlInstaller();
        return "Seems Fine...";
    }

    private function performDBDrop() {
        if ( !$this->askForDBDropActions() ) { return false; }
        if ( $this->askForDBDrop() ) {
            $this->loadDBAdminUser();
            $this->dbName = $this->askForDBFixedName();
            $this->dropDB(); }
        if ( $this->askForDBUserDrop() ) {
            if (!isset($this->dbRootUser)) {
              $this->loadDBAdminUser(); }
            $this->dbUser = $this->askForDBUser();
            $this->userDropper(); }
        return "Seems Fine...";
    }

    private function performAddUser() {
        if ( $this->askForDBUserAdd() ) {
            $this->dbUser = $this->askForFreeFormDBUser();
            $this->dbPass = $this->askForDBPass();
            $this->dbName = $this->askForDBFixedName();
            $this->userCreator(); }
        return "Seems Fine...";
    }

    private function performDropUser() {
        if ( $this->askForDBUserDrop() ) {
            $this->dbUser = $this->askForDBUser();
            $this->userDropper(); }
        return "Seems Fine...";
    }

    public function runAutoPilotDBInstallation($autoPilot){
        if ( !isset($autoPilot["dbInstallExecute"]) || $autoPilot["dbInstallExecute"]== false ) { return false; }
        $this->dbHost = $autoPilot["dbInstallDBHost"];
        $this->dbUser = $autoPilot["dbInstallDBUser"];
        $this->dbPass = $autoPilot["dbInstallDBPass"];
        $this->dbName = $autoPilot["dbInstallDBName"];
        $this->dbRootUser = $autoPilot["dbInstallDBRootUser"];
        $this->dbRootPass = $autoPilot["dbInstallDBRootPass"];
        $this->userCreator();
        $this->databaseCreator();
        $this->sqlInstaller();
        return true;
    }

    public function runAutoPilotDBRemoval($autoPilot){
        if ( !isset($autoPilot["dbDropExecute"]) || $autoPilot["dbDropExecute"]==false ) { return false; }
        $this->dbHost = $autoPilot["dbDropDBHost"];
        $this->dbName = $autoPilot["dbDropDBName"];
        $this->dbRootUser = $autoPilot["dbDropDBRootUser"];
        $this->dbRootPass = $autoPilot["dbDropDBRootPass"];
        $this->dropDB();
        if ( isset($autoPilot["dbDropUserExecute"]) && $autoPilot["dbDropUserExecute"] == true ) {
            $this->dbUser = $autoPilot["dbDropDBUser"];
            $this->userDropper(); }
        return true;
    }

    private function askForDBInstall(){
        $question = 'Do you want to install a database?';
        return self::askYesOrNo($question);
    }

    private function askForDBDropActions(){
        $question = 'Do you want to perform drop actions (user/db)?';
        return self::askYesOrNo($question);
    }

    private function askForDBDrop(){
        $question = 'Do you want to drop a database?';
        return self::askYesOrNo($question);
    }

    private function askForDBUserDrop(){
        $question = 'Do you want to drop a user?';
        return self::askYesOrNo($question);
    }

    private function askForDBUserAdd(){
        $question = 'Do you want to add a user?';
        return self::askYesOrNo($question);
    }

    private function verifyContinueWithNonConnectDetails(){
        $question = 'Cannot connect with these details. Sure you want to continue?';
        return self::askYesOrNo($question);
    }

    private function askForDBHost(){
        $question = 'What\'s the Mysql Host? Enter for 127.0.0.1';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1' : $input ;
    }

    private function askForDBUser(){
        $question = 'What\'s the application DB User?';
        $allDbUsers = array_merge(array("**CREATE NEW USER**"), $this->getDbUsers()) ;
        $user = self::askForArrayOption($question, $allDbUsers, true);
        if ($user=="**CREATE NEW USER**") {
          $question = 'Enter New User Name?';
          $user = self::askForInput($question, true); }
        return $user;
    }

    private function askForFreeFormDBUser(){
      $question = 'What\'s the application DB User?';
      return self::askForInput($question, true);
    }

    private function loadDBAdminUser() {
      $confUser = \Model\AppConfig::getAppVariable("mysql-admin-user") ;
      $confPass = \Model\AppConfig::getAppVariable("mysql-admin-pass") ;
      $confHost = \Model\AppConfig::getAppVariable("mysql-admin-host") ;
      if ($confUser != null && $confPass != null && $confHost != null ) {
        $this->dbHost = $confHost;
        $this->dbRootUser = $confUser;
        $this->dbRootPass = $confPass; }
      else {
        $this->dbHost = $this->askForDBHost();
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass(); }
      $canAdminConnect = $this->canAdminConnect();
      if ($canAdminConnect !== true) {
        echo $canAdminConnect;
        die(); }
    }

    private function askForDBPass(){
        $question = 'What\'s the application DB Password?';
        return self::askForInput($question, true);
    }

    private function askForDBFreeFormName(){
        $question = 'What\'s the application DB Name?'."\n";
        $question .= 'Current Db\'s are:'."\n";
        $allDbNames = $this->getDbNameList();
        foreach ($allDbNames as $onedbname) {
            $question .= $onedbname."\n"; }
        return self::askForInput($question, true);
    }

    private function askForDBFixedName(){
        $question = 'What\'s the application DB Name?';
        $allDbNames = $this->getDbNameList();
        return self::askForArrayOption($question, $allDbNames, true);
    }

    private function canIConnect(){
      error_reporting(0);
      $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
      error_reporting(E_ALL ^ E_WARNING);
      if (mysqli_connect_errno($con)) {
        return "Failed to connect to MySQL: " . mysqli_connect_error(); }
      else {
        mysqli_close($con);
        return true;}
    }

    private function canAdminConnect(){
      error_reporting(0);
      $con = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
      error_reporting(E_ALL ^ E_WARNING);
      if (mysqli_connect_errno($con)) {
        return "Admin Failed to connect to MySQL: " . mysqli_connect_error(); }
      else {
        mysqli_close($con);
        return true;}
    }

    private function getDbUsers() {
        $mysqli = new \mysqli($this->dbHost , $this->dbRootUser , $this->dbRootPass );
        $mysqliResult = $mysqli->query('SELECT User from mysql.user;');
        $users = array();
        while ($user = $mysqliResult->fetch_array()) {
            $users[] = $user[0]; }
        $i=0;
        $usersSorted = array();
        foreach ($users as $user) {
            if ( !in_array($user, array("root")) ) {
                $usersSorted[$i] = $user;
                $i++; } }
        return $usersSorted;
    }

    private function getDbNameList() {
        $mysqli = new \mysqli($this->dbHost , $this->dbRootUser , $this->dbRootPass );
        $mysqliResult = $mysqli->query('show databases;');
        $dbs = array();
        while ($db = $mysqliResult->fetch_array()) {
            $dbs[] = $db[0];}
        $dbsTrimmed = array_diff($dbs, array("test", "mysql", "information_schema", "performance_schema"));
        $i=0;
        $dbsSorted = array();
        foreach ($dbsTrimmed as $db) {
            $dbsSorted[$i] = $db;
            $i++; }
        return $dbsSorted;
    }

    private function useRootToSetUpUserAndDb(){
        $question = 'MySQL Admin Details required to setup DB/User - Continue?';
        return self::askYesOrNo($question);
    }

    private function askForRootDBUser(){
        $question = 'What\'s the MySQL Admin User?';
        return self::askForInput($question, true);
    }

    private function askForRootDBPass(){
        $question = 'What\'s the MySQL Admin Password?';
        return self::askForInput($question, true);
    }

    private function databaseCreator() {
        $dbc = mysql_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'create database if not exists '.$this->dbName.';';
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
    }

    private function userCreator() {
        $dbc = mysql_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\' identified by "'.$this->dbPass.'";';
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\' identified by "'.$this->dbPass.'";';
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\'' ;
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\'' ;
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
    }

    private function userDropper() {
        $dbc = mysql_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'DROP USER \''.$this->dbUser.'\'@\'localhost\'; ' ;
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'DROP USER \''.$this->dbUser.'\'@\'%\'; ' ;
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        print "Database User $this->dbUser dropped\n";
    }

    private function dropDB() {
        $dbc = mysql_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        echo (mysql_error($dbc));
        $query = 'DROP DATABASE '.$this->dbName.';';
        mysql_query($query, $dbc) ;
        print "Database $this->dbName dropped\n";
    }

    private function sqlInstaller() {
        $sqlFileToExecute = "db/database.sql" ;
        $command  = 'mysql -h'.$this->dbHost.' -u'.$this->dbUser.' -p'.$this->dbPass.' ';
        $command .= $this->dbName.' < '.$sqlFileToExecute;
        self::executeAndOutput($command);
        print "Database script executed\n";
    }


}