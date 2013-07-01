<?php

Namespace Model;

class DBConfigure extends Base {

    private $platform;
    private $platformVars;
    private $settingsFileData;
    private $currentExtraSettingsFileData;
    private $dbHost ;
    private $dbUser ;
    private $dbPass ;
    private $dbRootUser ;
    private $dbRootPass ;
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
        $this->dbRootUser = $this->askForRootDBUser();
        if ($this->dbRootUser != "") {
            $this->dbRootPass = $this->askForRootDBPass(); }
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
        echo "DB Config Setup:\n";
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
        if (is_array($this->platformVars->getProperty("extraConfigFiles")) &&
            count($this->platformVars->getProperty("extraConfigFiles"))>0) {
            $this->doExtraSettingsFilesDataChanges(); }
        return true;
    }

    public function runAutoPilotDBReset($autoPilot){
        if ( !$autoPilot->dbResetExecute ) { return false; }
        echo "DB Config Reset:\n";
        $this->platform = $autoPilot->dbResetPlatform;
        $this->setPlatformVars();
        $this->loadCurrentSettingsFile();
        $this->settingsFileReverseDataChange();
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        if (is_array($this->platformVars->getProperty("extraConfigFiles")) &&
            count($this->platformVars->getProperty("extraConfigFiles"))>0) {
            $this->doExtraSettingsFilesReverseDataChanges(); }
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
        if (in_array($this->platform, array("joomla", "j15", "joomla15"))) {
          $this->platformVars = new \Model\DBConfigureDataJoomla15();
          return; }
        if (in_array($this->platform, array("j30", "joomla30"))) {
          $this->platformVars = new \Model\DBConfigureDataJoomla30();
          return; }
        if ($this->platform != "") {
          $platformClassName = '\Model\DBConfigureData'.$this->platform ;
          $this->platformVars = new $platformClassName();
          return; }
        $this->platformVars = new \Model\DBConfigureDataGCFW2();
        return;
    }

    private function askForPlatform(){
        $availablePlats = array("drupal7", "php" , "gcfw" , "gcfw2", "joomla15", "joomla30");
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
        $question = 'What\'s the application DB User?'."\n";
        if ($this->dbRootUser != "") {
          $allDbUsers = array_merge(array("**ENTER PLAIN TEXT**"), $this->getDbUsers()) ;
          $user = self::askForArrayOption($question, $allDbUsers, true);
          if ($user=="**ENTER PLAIN TEXT**") {
            $question = 'Enter DB Username Text?';
            $user = self::askForInput($question, true); } }
        $user = (isset($user)) ? $user : self::askForInput($question, true);
        return $user ;
    }

    private function askForRootDBUser(){
      $question = 'What\'s the MySQL Admin User? (Enter nothing to skip loading current users to help config)';
      return self::askForInput($question, true);
    }

    private function askForRootDBPass(){
      $question = 'What\'s the MySQL Admin Password?';
      return self::askForInput($question, true);
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

    private function askForDBPass(){
        $question = 'What\'s the application DB Password?';
        return self::askForInput($question, true);
    }

    private function askForDBName(){
        $question = 'What\'s the application DB Name?'."\n";
        if ($this->dbRootUser != "") {
          $allDbNames = array_merge(array("**ENTER PLAIN TEXT**"), $this->getDbNameList()) ;
          $dbName = self::askForArrayOption($question, $allDbNames, true);
          if ($dbName=="**ENTER PLAIN TEXT**") {
            $question = 'Enter DB Name Text?';
            $dbName = self::askForInput($question, true); } }
        $dbName = (isset($dbName)) ? $dbName : self::askForInput($question, true);
        return $dbName ;
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

    private function doExtraSettingsFilesDataChanges() {
        foreach ($this->platformVars->getProperty("extraConfigFiles") as $settingsFile) {
            echo "Loading Extra settings file $settingsFile to configure values...\n" ;
            $command  = 'cat '.$settingsFile;
            $this->currentExtraSettingsFileDat = self::executeAndLoad($command);
            $replacements =  array('****DB USER****'=>$this->dbUser, '****DB NAME****'=>$this->dbName,
                '****DB PASS****'=>$this->dbPass, '****DB HOST****'=>$this->dbHost, );
            $this->currentExtraSettingsFileData = strtr($this->currentExtraSettingsFileData, $replacements);
            echo "Moving Extra settings file in...\n" ;
            return file_put_contents($settingsFile, $this->currentExtraSettingsFileData);}
    }

    private function doExtraSettingsFilesReverseDataChanges() {
        foreach ($this->platformVars->getProperty("extraConfigFiles") as $settingsFile) {
            echo "Loading Extra settings file $settingsFile to reset values...\n" ;
            $command  = 'cat '.$settingsFile;
            $this->currentExtraSettingsFileData = self::executeAndLoad($command);
            $settingsFileLines = explode("\n", $this->currentExtraSettingsFileData);
            $replacements = $this->platformVars->getProperty("extraConfigFileReplacements") ;
            foreach ( $settingsFileLines as &$settingsFileLine ) {
                foreach ( $replacements as $searchFor=>$replaceWith ) {
                    if (strpos($settingsFileLine, $searchFor)) {
                        $settingsFileLine = $replaceWith; } } }
            $this->currentExtraSettingsFileData = implode("\n", $settingsFileLines);
            echo "Moving Extra settings file in...\n" ;
            return file_put_contents($settingsFile, $this->currentExtraSettingsFileData); }
    }

    private function settingsFileDataChange(){
        $replacements =  array('****DB USER****'=>$this->dbUser, '****DB NAME****'=>$this->dbName,
            '****DB PASS****'=>$this->dbPass, '****DB HOST****'=>$this->dbHost, );
        $this->settingsFileData = strtr($this->settingsFileData, $replacements);
    }

    private function settingsFileReverseDataChange(){
        $settingsFileLines = explode("\n", $this->settingsFileData);
        $replacements = $this->platformVars->getProperty("settingsFileReplacements") ;
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
        (strlen($this->platformVars->getProperty("settingsFileLocation"))>0)
          ? $location = $this->platformVars->getProperty("settingsFileLocation").'/'
          : $location = "" ;
        $location .= $this->platformVars->getProperty("settingsFileName");
        echo "Moving new settings file ".$this->platformVars->getProperty("settingsFileName")." in...\n" ;
        return file_put_contents($location, $this->settingsFileData);
    }

    private function removeOldSettingsFile(){
        $command    = 'rm '.$this->platformVars->getProperty("settingsFileLocation").'/';
        $command .= $this->platformVars->getProperty("settingsFileName");
        self::executeAndOutput($command, "Removing old settings file ".$this->platformVars->getProperty("settingsFileName")."...\n");
    }

}