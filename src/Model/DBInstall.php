<?php

Namespace Model;

class DBInstall extends Base {

    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbName ;
    private $dbRootUser ;
    private $dbRootPass ;

    public function askWhetherToInstallDB(){
        return $this->performDBInstallation();
    }

    private function performDBInstallation(){
        if ( !$this->askForDBConfig() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBName();

        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {

        }

        return true;
    }

    private function askForDBConfig(){
        $question = 'Do you want to install a database?';
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
        $con = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
        if (mysqli_connect_errno($con)) {
            mysqli_close($con);
            return "Failed to connect to MySQL: " . mysqli_connect_error(); }
        else {
            mysqli_close($con);
            return true;}
    }


    private function askForRootDBUser(){
        $question = 'What\'s the MySQL Root DB User?';
        return self::askForInput($question, true);
    }

    private function askForRootDBPass(){
        $question = 'What\'s the MySQL Root DB Password?';
        return self::askForInput($question, true);
    }

}