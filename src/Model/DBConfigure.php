<?php

Namespace Model;

class DBConfigure extends Base {

    private $platform;
    private $platformVars;
    private $settingsFileData;
    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbName ;
    private $tmpDir = '/tmp'; // no trailing slash

    public function __construct() {
        //do stuff
    }

    public function askWhetherToConfigureDB(){
        return $this->performDBConfiguration();
    }

    public function askWhetherToResetDBConfiguration(){
        return $this->performDBConfigurationReset();
    }

    private function performDBConfiguration(){
        if ( !$this->askForDBConfig() ) { return false; }
        // @todo $this->tryToDetectPlatform() ; try to autodetect the platform from the proj file before asking for it
        $this->platform = ($this->platform==null) ? $this->platform = $this->askForPlatform() : $this->platform ;
        $this->setPlatformVars();
        $this->dbHost = $this->askForDBHost();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBName();
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; } }
        $this->loadCurrentSettingsFile();
        $this->settingsFileDataChange();
        if ( !$this->checkSettingsFileOkay() ) { return false; }
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        return true;
    }

    private function performDBConfigurationReset(){
        if ( !$this->askForDBConfigReset() ) { return false; }
        // @todo $this->tryToDetectPlatform() ; try to autodetect the platform from the proj file before asking for it
        $this->platform = ($this->platform==null) ? $this->platform = $this->askForPlatform() : $this->platform ;
        $this->setPlatformVars();
        $this->loadCurrentSettingsFile();
        $this->settingsFileReverseDataChange();
        if ( !$this->checkSettingsFileOkay() ) { return false; }
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        return true;
    }

    public function runAutoPilotDBConfiguration($autoPilot){
        if ( !$autoPilot->dbConfigureExecute ) { return false; }
        $this->dbHost = $autoPilot->dbConfigureDBHost;
        $this->dbUser = $autoPilot->dbConfigureDBUser;
        $this->dbPass = $autoPilot->dbConfigureDBPass;
        $this->dbName = $autoPilot->dbConfigureDBName;
        $this->platform = $autoPilot->dbConfigurePlatform;
        $this->setPlatformVars();
        $this->loadCurrentSettingsFile();
        $this->settingsFileDataChange();
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        return true;
    }

    public function runAutoPilotDBReset($autoPilot){
        if ( !$autoPilot->dbResetExecute ) { return false; }
        $this->platform = $autoPilot->dbResetPlatform;
        $this->setPlatformVars();
        $this->loadCurrentSettingsFile();
        $this->settingsFileReverseDataChange();
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        return true;
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function setPlatformVars() {
        if (in_array($this->platform, array("d7", "drupal7" , "drupal"))) {
            $this->platformVars = new \Model\DBConfigureDataDrupal70();
            return; }
        if (in_array($this->platform, array("php", "gcfw", "gcfw2"))) {
            $this->platformVars = new \Model\DBConfigureDataGCFW2();
            return; }
        $this->platformVars = new \Model\DBConfigureDataGCFW2();
        return;
    }

    private function askForPlatform(){
        $availablePlats = array("drupal7", "php" , "gcfw" , "gcfw2");
        $question = "Please Choose Project Platform:\n";
        $i=0;
        foreach ($availablePlats as $plat) {
            $question .= "($i) $plat\n";
            $i++; }
        $validChoice = false;
        $i=0;
        while ($validChoice == false) {
            if ($i==1) { $question = "That's not a valid option, ".$question; }
            $input = self::askForDigit($question) ;
            if ( array_key_exists($input, $availablePlats) ){
                $validChoice = true;}
            $i++; }
        return $availablePlats[$input] ;
    }

    private function askForDBConfig(){
        $question = 'Do you want to configure a database?';
        return self::askYesOrNo($question);
    }

    private function askForDBConfigReset(){
        $question = 'Do you want to reset a database configuration?';
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

    private function loadCurrentSettingsFile() {
        $command  = 'cat '.$this->platformVars->getProperty("settingsFileLocation").'/';
        $command .= $this->platformVars->getProperty("settingsFileName");
        $this->settingsFileData = self::executeAndLoad($command);
    }

    private function settingsFileDataChange(){
        $replacements =  array('****DB USER****'=>$this->dbUser, '****DB NAME****'=>$this->dbName,
            '****DB PASS****'=>$this->dbPass, '****DB HOST****'=>$this->dbHost, );
        $this->settingsFileData = strtr($this->settingsFileData, $replacements);
    }

    private function settingsFileReverseDataChange(){
        $settingsFileLines = explode("\n", $this->settingsFileData);
        $replacements =  array(
            "'database'"=>"      'database' => '****DB NAME****',",
            "'username'"=>"      'username' => '****DB USER****',",
            "'password'"=>"      'password' => '****DB PASS****',",
            "'host'"=>"      'host' => '****DB HOST****'," );
        foreach ( $settingsFileLines as &$settingsFileLine ) {
            foreach ( $replacements as $searchFor=>$replaceWith ) {
                if (strpos($settingsFileLine, $searchFor)) {
                    $settingsFileLine = $replaceWith; } } }
        $this->settingsFileData = implode("\n", $settingsFileLines);
    }

    private function checkSettingsFileOkay(){
        $question = 'Please check '.$this->platform.' Settings file: '.$this->settingsFileData."\n\nIs this Okay?";
        return self::askYesOrNo($question);
    }

    private function createSettingsFile() {
        $location  = $this->platformVars->getProperty("settingsFileLocation").'/';
        $location .= $this->platformVars->getProperty("settingsFileName");
        echo "Moving new settings file in...\n" ;
        return file_put_contents($location, $this->settingsFileData);
    }

    private function removeOldSettingsFile(){
        $command    = 'rm '.$this->platformVars->getProperty("settingsFileLocation").'/';
        $command .= $this->platformVars->getProperty("settingsFileName");
        self::executeAndOutput($command, "Removing old settings file...\n");
    }

}