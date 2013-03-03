<?php

Namespace Model;

class DBConfigure extends Base {

    private $settingsFileData;
    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbName ;

    public function askWhetherToConfigureDB(){
        return $this->performDBConfiguration();
    }

    private function performDBConfiguration(){
        if ( !$this->askForDBConfig() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBName();
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyInstallWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; } }
        $this->loadCurrentSettingsFile();
        $this->settingsFileDataChange();
        $this->checkSettingsFileOkay();
        $this->createSettingsFile();
        $this->removeOldSettingsFile();
        $this->moveSettingsFile();
        return true;
    }

    private function askForDBConfig(){
        $question = 'Do you want to configure a database?';
        return self::askYesOrNo($question);
    }

    private function verifyInstallWithNonConnectDetails(){
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
        $con = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
        if (mysqli_connect_errno($con)) {
            mysqli_close($con);
            return "Failed to connect to MySQL: " . mysqli_connect_error(); }
        else {
            mysqli_close($con);
            return true;}
    }

    private function loadCurrentSettingsFile() {
        $command = 'cat src/sites/default/settings.php';
        $this->settingsFileData = self::executeAndLoad($command);
    }

    private function settingsFileDataChange(){
        $replacements =  array('****DB USER****'=>$this->dbUser, '****DB NAME****'=>$this->dbName,
            '****DB PASS****'=>$this->dbPass, '****DB HOST****'=>$this->dbHost, );
        $this->settingsFileData = strtr($this->settingsFileData, $replacements);
    }

    private function checkSettingsFileOkay(){
        $question = 'Please check Drupal Settings file: '.$this->settingsFileData."\n\nIs this Okay? (Y/N)";
        return self::askYesOrNo($question);
    }

    private function createSettingsFile() {
        $tmpDir = '/tmp/cukefile/';
        if (!file_exists($tmpDir)) { mkdir ($tmpDir); }
        return file_put_contents($tmpDir.'/env.rb', $this->settingsFileData);
    }

    private function removeOldSettingsFile(){
        $command = 'rm build/tests/features/support/env.rb';
        self::executeAndOutput($command);
    }

    private function moveSettingsFile(){
        $command = 'mv /tmp/cukefile/env.rb build/tests/features/support/env.rb';
        self::executeAndOutput($command);
    }


}