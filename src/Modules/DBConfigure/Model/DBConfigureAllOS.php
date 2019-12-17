<?php

Namespace Model;

class DBConfigureAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    protected $platform;
    public $platformVars;
    protected $settingsFileData;
    protected $currentExtraSettingsFileData;
    public $dbHost ;
    public $dbUser ;
    public $dbPass ;
    public $dbRootUser ;
    public $dbRootPass ;
    public $dbName ;

    public function askWhetherToConfigureDB(){
        return $this->performDBConfiguration();
    }

    public function askWhetherToResetDBConfiguration(){
        return $this->performDBConfigurationReset();
    }

    protected function performDBConfiguration(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( !$this->askForDBConfig() ) { return false; }
        // @todo $this->tryToDetectPlatform() ; try to autodetect the platform from the proj file before asking for it
        $this->setPlatformVars();
        $this->dbRootUser = $this->askForRootDBUser();
        if ($this->dbRootUser != "") { $this->dbRootPass = $this->askForRootDBPass(); }
        $this->dbHost = $this->askForDBHost();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBName();

        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) {
                $logging->log("Not continuing database configuration, connection details not working", $this->getModuleName());
                return false; }
            else {
                $logging->log("Attempting to execute anyway, with connection details not working", $this->getModuleName());
            } }
        else {
            $logging->log("Database Connection working", $this->getModuleName());  }

        $this->loadCurrentSettingsFile();
        $this->settingsFileDataChange();
        if ( !$this->checkSettingsFileOkay() ) { return false; }
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        return true;
    }

    protected function performDBConfigurationReset(){
        if ( !$this->askForDBConfigReset() ) { return false; }
        // @todo $this->tryToDetectPlatform() ; try to autodetect the platform from the proj file before asking for it
        $this->setPlatformVars();
        $this->loadCurrentSettingsFile();
        $this->settingsFileReverseDataChange();
        if ( !$this->checkSettingsFileOkay() ) { return false; }
        $this->removeOldSettingsFile();
        $this->createSettingsFile();
        return true;
    }

    public function getProperty($property) {
        return $this->$property;
    }

    public function setPlatformVars($platformVars = null) {
        if ($platformVars != null) {
            $this->platformVars = $platformVars; }
        else if ($this->platformVars == null) {
            $this->platformVars = new \Model\DBConfigureDataGCFW2(); }
        return;
    }

    protected function askForPlatform(){
        if (isset($this->params["platform"])) { return $this->params["platform"] ; }
        $question = "Please Enter Project Platform:\n";
        $input = self::askForInput($question, true) ;
        return $input ;
    }

    protected function askForDBConfig(){
        $question = 'Do you want to configure a database?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBConfigReset(){
        $question = 'Do you want to reset a database configuration?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function verifyContinueWithNonConnectDetails(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $question = 'Cannot connect with these details. Sure you want to continue?';
        if (isset($this->params["guess"])) {
            $logging->log("Guessing that we should not attempt to continue with connection details that don't work.", $this->getModuleName());
            return false ;}
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBHost(){
        if (isset($this->params["mysql-host"])) { return $this->params["mysql-host"] ; };
        if (isset($this->params["host"])) { return $this->params["host"] ; };
        $question = 'What\'s the Mysql Host? Enter for 127.0.0.1';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1' : $input ;
    }

    protected function askForDBUser(){
        if (isset($this->params["mysql-user"])) { return $this->params["mysql-user"] ; }
        if (isset($this->params["mysql-user-name"])) { return $this->params["mysql-user-name"] ; };
        if (isset($this->params["mysql-username"])) { return $this->params["mysql-username"] ; };
        if (isset($this->params["username"])) { return $this->params["username"] ; };
        if (isset($this->params["user"])) { return $this->params["user"] ; }
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

    protected function askForRootDBUser(){
        if (isset($this->params["mysql-admin-user"])) { return $this->params["mysql-admin-user"] ; }
        if (isset($this->params["admin-user"])) { return $this->params["admin-user"] ; }
        $question = 'What\'s the MySQL Admin User? (Enter nothing to skip loading current users to choose from)';
        return self::askForInput($question, true);
    }

    protected function askForRootDBPass(){
        if (isset($this->params["mysql-admin-pass"])) { return $this->params["mysql-admin-pass"] ; }
        if (isset($this->params["admin-pass"])) { return $this->params["admin-pass"] ; }
        $question = 'What\'s the MySQL Admin Password?';
        return self::askForInput($question, true);
    }

    protected function getDbUsers() {
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

    protected function getDbNameList() {
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

    protected function askForDBPass(){
        if (isset($this->params["mysql-password"])) { return $this->params["mysql-password"] ; }
        if (isset($this->params["mysql-pass"])) { return $this->params["mysql-pass"] ; }
        if (isset($this->params["password"])) { return $this->params["password"] ; }
        if (isset($this->params["pass"])) { return $this->params["pass"] ; }
        $question = 'What\'s the application DB Password?';
        return self::askForInput($question, true);
    }

    protected function askForDBName(){
        if (isset($this->params["mysql-database"])) { return $this->params["mysql-database"] ; }
        if (isset($this->params["mysql-db"])) { return $this->params["mysql-db"] ; }
        if (isset($this->params["database"])) { return $this->params["database"] ; }
        if (isset($this->params["db"])) { return $this->params["db"] ; }
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

    protected function canIConnect(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        error_reporting(0);
        if (!function_exists("mysqli_connect")) {
            \Core\BootStrap::setExitCode(1);
            $logging->log("MySQL PHP Extension not installed, fatal error", $this->getModuleName());
            return false ; }
        $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
        error_reporting(E_ALL ^ E_WARNING);
        if (mysqli_connect_errno($con)) {
            $logging->log("Failed to connect to MySQL: " . mysqli_connect_error());
            return false ; }
        else {
            mysqli_close($con);
            return true;}
    }

    protected function loadCurrentSettingsFile() {
//		if (isset($this->params["parent-path"])) { $path = $this->params["parent-path"] ; }
//		if (isset($this->params["guess"])) { $path = getcwd() ; }
//		if (!isset($path)) { $path = getcwd() ; }
//        $len = strlen($path) ;
//        $lastChar = substr($path, ($len-1), $len);
//        if ($lastChar != '/') { $path .= '/' ; }
//        $command  = 'cat '.$path ;
//        $command .= (strlen($this->platformVars->getProperty("settingsFileLocation"))>0)
//            ? $this->platformVars->getProperty("settingsFileLocation").'/' : "";
//        $command .= $this->platformVars->getProperty("settingsFileName");

        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $parent = (isset($this->params["parent-path"])) ? $this->params["parent-path"] : getcwd() ;
        $len = strlen($parent) ;
        $lastChar = substr($parent, ($len-1), $len);
        if ($lastChar != '/') { $parent .= '/' ; }
        (strlen($this->platformVars->getProperty("settingsFileLocation"))>0)
            ? $location = $parent.$this->platformVars->getProperty("settingsFileLocation")
            : $location = $parent."" ;
        $location .= $this->platformVars->getProperty("settingsFileName");
        $this->settingsFileData = file_get_contents($location);
    }

    protected function doExtraSettingsFilesDataChanges() {
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

    protected function doExtraSettingsFilesReverseDataChanges() {
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

    protected function settingsFileDataChange(){
        $replacements =  array('****DB USER****'=>$this->dbUser, '****DB NAME****'=>$this->dbName,
            '****DB PASS****'=>$this->dbPass, '****DB HOST****'=>$this->dbHost, );
        $this->settingsFileData = strtr($this->settingsFileData, $replacements);
    }

    protected function settingsFileReverseDataChange(){
        $settingsFileLines = explode("\n", $this->settingsFileData);
        $replacements = $this->platformVars->getProperty("settingsFileReplacements") ;
        foreach ( $settingsFileLines as &$settingsFileLine ) {
            foreach ( $replacements as $searchFor=>$replaceWith ) {
                if (strpos($settingsFileLine, $searchFor)) {
                    $settingsFileLine = $replaceWith; } } }
        $this->settingsFileData = implode("\n", $settingsFileLines);
    }

    protected function checkSettingsFileOkay(){
        $question = 'Please check '.$this->platformVars->getProperty("friendlyName").' Settings file: '.$this->settingsFileData."\n\nIs this Okay?";
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function createSettingsFile() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
		$parent = (isset($this->params["parent-path"])) ? $this->params["parent-path"] : getcwd() ;
        $len = strlen($parent) ;
        $lastChar = substr($parent, ($len-1), $len);
        if ($lastChar != '/') { $parent .= '/' ; }
        (strlen($this->platformVars->getProperty("settingsFileLocation"))>0)
          ? $location = $parent.$this->platformVars->getProperty("settingsFileLocation")
          : $location = $parent."" ;
        $location .= $this->platformVars->getProperty("settingsFileName");
        $logging->log("Moving new settings file ".$location." in", $this->getModuleName()) ;
        return file_put_contents($location, $this->settingsFileData);
    }

    protected function removeOldSettingsFile(){
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
		$parent = (isset($this->params["parent-path"])) ? $this->params["parent-path"] : getcwd() ;
        $len = strlen($parent) ;
        $lastChar = substr($parent, ($len-1), $len);
        if ($lastChar != '/') { $parent .= '/' ; }
        (strlen($this->platformVars->getProperty("settingsFileLocation"))>0)
          ? $location = $parent.$this->platformVars->getProperty("settingsFileLocation")
          : $location = $parent."" ;
        $location .= $this->platformVars->getProperty("settingsFileName");
        // @todo need windows friendly
        $command    = 'rm -f '.$location ;
        $logging->log("Removing old settings file ".$location, $this->getModuleName()) ;
        self::executeAndOutput($command);
    }

}
