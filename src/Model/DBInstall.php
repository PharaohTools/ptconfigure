<?php

Namespace Model;

class DBInstall extends Base {

    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbName ;
    private $dbRootUser ;
    private $dbRootPass ;

    public function askWhetherToInstallDB(\Model\DBConfigure $dbConfigObject=null){
        if ($dbConfigObject) { return $this->performDBInstallation($dbConfigObject); }
        else { return $this->performDBInstallation(); }
    }

    public function askWhetherToDropDB(){
        return $this->performDBDrop();
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
            $this->databaseAndUserCreator(); }
        $this->sqlInstaller();
        return "Seems Fine...";
    }

    private function performDBInstallationWithNoConfig() {
        if ( !$this->askForDBInstall() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBName();
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; }
            if (!$this->useRootToSetUpUserAndDb() ) { return "You declined using root"; }
            $this->dbRootUser = $this->askForRootDBUser();
            $this->dbRootPass = $this->askForRootDBPass();
            $this->databaseAndUserCreator(); }
        $this->sqlInstaller();
        return "Seems Fine...";
    }

    private function performDBDrop() {
        if ( !$this->askForDBDrop() ) { return false; }
        if (!$this->useRootToDropDb() ) { return "You declined using root"; }
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass();
        $this->dbName = $this->askForDBName();
        $this->dropDB();
        return "Seems Fine...";
    }

    public function runAutoPilotDBInstallation($autoPilot){
        if ( !$autoPilot->dbInstallExecute ) { return false; }
        $this->dbHost = $autoPilot->dbInstallDBHost;
        $this->dbUser = $autoPilot->dbInstallDBUser;
        $this->dbPass = $autoPilot->dbInstallDBPass;
        $this->dbName = $autoPilot->dbInstallDBName;
        $this->dbRootUser = $autoPilot->dbInstallDBRootUser;
        $this->dbRootPass = $autoPilot->dbInstallDBRootPass;
        $this->databaseAndUserCreator();
        $this->sqlInstaller();
        return true;
    }

    public function runAutoPilotDBRemoval($autoPilot){
        if ( !$autoPilot->dbDropExecute ) { return false; }
        $this->dbHost = $autoPilot->dbDropDBHost;
        $this->dbName = $autoPilot->dbDropDBName;
        $this->dbRootUser = $autoPilot->dbDropDBRootUser;
        $this->dbRootPass = $autoPilot->dbDropDBRootPass;
        $this->dropDB();
        return true;
    }

    private function askForDBInstall(){
        $question = 'Do you want to install a database?';
        return self::askYesOrNo($question);
    }

    private function askForDBDrop(){
        $question = 'Do you want to drop a database?';
        return self::askYesOrNo($question);
    }

    private function verifyContinueWithNonConnectDetails(){
        $question = 'Cannot connect with these details. Sure you want to continue? (Y/N)';
        return self::askYesOrNo($question);
    }

    private function askForDBHost(){
        $question = 'What\'s the Mysql Host? Enter for 127.0.0.1';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1' : $input ;
    }

    private function askForDBUser(){
        $question = 'What\'s the application DB User?';
        return self::askForInput($question, true);
    }

    private function askForDBPass(){
        $question = 'What\'s the application DB Password?';
        return self::askForInput($question, true);
    }

    private function askForDBName(){
        $question = 'What\'s the application DB Name?';
        return self::askForInput($question, true);
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

    private function useRootToSetUpUserAndDb(){
        $question = 'MySQL Root Details required to setup DB/User - Continue?';
        return self::askYesOrNo($question);
    }

    private function useRootToDropDb(){
        $question = 'MySQL Root Details required to Drop DB - Continue? ';
        return self::askYesOrNo($question);
    }

    private function askForRootDBUser(){
        $question = 'What\'s the MySQL Root DB User?';
        return self::askForInput($question, true);
    }

    private function askForRootDBPass(){
        $question = 'What\'s the MySQL Root DB Password?';
        return self::askForInput($question, true);
    }

    private function databaseAndUserCreator() {
        $dbc = mysql_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'create database if not exists '.$this->dbName.';';
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'grant usage on *.* to '.$this->dbUser.'@\'%\' identified by "'.$this->dbPass.'";';
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'grant usage on *.* to '.$this->dbUser.'@\'localhost\' identified by "'.$this->dbPass.'";';
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
        $query = 'grant all privileges on \''.$this->dbName.'\'.* to '.$this->dbUser.'@\'%\'' ;
        echo "$query\n";
        mysql_query($query, $dbc) or var_dump (mysql_error($dbc));
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