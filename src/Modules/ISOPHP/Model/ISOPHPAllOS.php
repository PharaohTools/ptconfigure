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
    public $modelGroup = array("Default") ;

    public $platformHooks = null ;
    public $dbHost ;
    public $dbUser ;
    public $dbPass ;
    public $dbName ;
    public $dbRootUser ;
    public $dbRootPass ;
    public $dbFilePath ;

    public function __construct($params) {
        parent::__construct($params);
        $this->dbFilePath = "db".DS."database.sql";
    }

    public function askInstall(\Model\DBConfigureAllOS $dbConfigObject=null){
        if ($dbConfigObject!=null) { return $this->performDBInstallation($dbConfigObject); }
        else { return $this->performDBInstallation(); }
    }

    public function askWhetherToSaveDB(\Model\DBConfigureAllOS $dbConfigObject=null){
        if ($dbConfigObject!=null) { return $this->performDBSave($dbConfigObject); }
        else { return $this->performDBSave(); }
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

    protected function performDBInstallation(\Model\DBConfigureAllOS $dbConfigObject=null){
        if ($dbConfigObject!==null) {
            return $this->performDBInstallationWithConfig($dbConfigObject) ; }
        else {
            return $this->performDBInstallationWithNoConfig() ; }
    }

    protected function performDBInstallationWithConfig(\Model\DBConfigureAllOS $dbConfigObject) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( !$this->askForDBInstall() ) {
            $logging->log("DB Installation refused", $this->getModuleName()) ;
            return false; }
        $this->setDBFilePath();
        $this->dbHost = $dbConfigObject->getProperty("dbHost");
        $this->dbUser = $dbConfigObject->getProperty("dbUser");
        $this->dbPass = $dbConfigObject->getProperty("dbPass");
        $this->dbName = $dbConfigObject->getProperty("dbName");
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) {
                $logging->log("Exiting due to incorrect db connection", $this->getModuleName()) ;
                return false; }
            if (!$this->useRootToSetUpUserAndDb() ) {
                $logging->log("Unable to connect with non-root details, and you've declined using root", $this->getModuleName()) ;
                return false; }
            $this->dbRootUser = $this->askForRootDBUser();
            $this->dbRootPass = $this->askForRootDBPass();
            $this->databaseCreator();
            $this->userCreator(); }
        $this->doInstallHook("pre") ;
        $this->sqlInstaller();
        $this->doInstallHook("post") ;
        return true;
    }

    // @todo add logging of install hook outputs
    protected function doInstallHook($hook) {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $hook = strtolower($hook) ;
        if (in_array($hook, array("pre", "post"))) {
            if (!is_null($this->platformHooks)) {
                if (is_object($this->platformHooks)) {
                    if (method_exists($this->platformHooks, "{$hook}InstallHook")) {
                        $fullMethodName = "{$hook}InstallHook" ;
                        $res = $this->platformHooks->$fullMethodName($this) ;
                        return $res; }
                    else {
                        $logging->log("The specified Database Install Hook {$hook}InstallHook does not exist for this platform", $this->getModuleName());
                        // no specified install hook exists
                        return false; } }
                else {
                    $logging->log("Database Install Hooks are set, but not as a readable object", $this->getModuleName());
                    // platform hooks is set but is not an object
                    return false; } }
            else {
                 $logging->log("Database Install Hooks are set, but not as a readable object", $this->getModuleName());
                // no platform hooks model
                return false; ; } }
        else {
            $logging->log("Requested DB Install Hook {$hook} is not supported", $this->getModuleName());
            // non existent hook
            return false ; }
    }

    protected function performDBInstallationWithNoConfig() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        if ( !$this->askForDBInstall() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass();
        $this->dbUser = $this->askForDBUser();
        $this->dbPass = $this->askForDBPass();
        $this->dbName = $this->askForDBFreeFormName();
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) {
                $logging->log("Exiting due to incorrect db connection", $this->getModuleName()) ;
                return false; }
            if (!$this->useRootToSetUpUserAndDb() ) {
                $logging->log("Unable to connect with non-root details, and you've declined using root", $this->getModuleName()) ;
                return false; }
            $this->databaseCreator();
            $this->userCreator(); }
        $this->doInstallHook("pre") ;
        $this->sqlInstaller();
        $this->doInstallHook("post") ;
        return true;
    }

    public function setPlatformDBIHooks($platformHooks = null) {
        if ($platformHooks != null) {
            $this->platformHooks = $platformHooks; }
        else if ($this->platformHooks == null) {
            $this->platformHooks = null ; }
        return;
    }

    protected function performDBSave(\Model\DBConfigureAllOS $dbConfigObject=null){
        if ($dbConfigObject!==null) {
            return $this->performDBSaveWithConfig($dbConfigObject) ; }
        else {
            return $this->performDBSaveWithNoConfig() ; }
    }

    protected function performDBSaveWithConfig(\Model\DBConfigureAllOS $dbConfigObject) {
        if ( !$this->askForDBSave() ) { return false; }
        $this->dbHost = $dbConfigObject->platformVars->getConfigProperty("dbHost");
        $this->dbUser = $dbConfigObject->platformVars->getConfigProperty("dbUser");
        $this->dbPass = $dbConfigObject->platformVars->getConfigProperty("dbPass");
        $this->dbName = $dbConfigObject->platformVars->getConfigProperty("dbName");
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; }
            if (!$this->useRootToSetUpUserAndDb() ) { return "You declined using root"; }
            $this->dbRootUser = $this->askForRootDBUser();
            $this->dbRootPass = $this->askForRootDBPass();
            return $this->databaseSaver(); }
        $this->dbRootUser = $this->dbUser ;
        $this->dbRootPass = $this->dbPass;
        return $this->databaseSaver();
    }

    protected function performDBSaveWithNoConfig() {
        if ( !$this->askForDBSave() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass();
        $this->dbName = $this->askForDBFreeFormName();
        $canAdminConnect = $this->canAdminConnect();
        if ($canAdminConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) {
                return "Exiting due to incorrect db connection"; } }
        $this->databaseSaver();
        return true;
    }

    protected function performDBDrop() {
        if ( !$this->askForDBDropActions() ) { return false; }
        if ( $this->askForDBDrop() ) {
            $this->loadDBAdminUser();
            $this->dbName = $this->askForDBFixedName();
            $this->dropDB(); }
//        if ( $this->askForDBUserDrop() ) {
//            if (!isset($this->dbRootUser)) {
//              $this->loadDBAdminUser(); }
//            $this->dbUser = $this->askForDBUser();
//            $this->userDropper(); }
        return true;
    }

    protected function performAddUser() {
        if ( $this->askForDBUserAdd() ) {
            $this->dbUser = $this->askForFreeFormDBUser();
            $this->dbPass = $this->askForDBPass();
            $this->dbName = $this->askForDBFixedName();
            $this->userCreator(); }
        return true;
    }

    protected function performDropUser() {
        if ( $this->askForDBUserDrop() ) {
            $this->dbUser = $this->askForDBUser();
            $this->userDropper(); }
        return true;
    }

    protected function askForDBInstall(){
        $question = 'Do you want to install a database?' ;
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function setDBFilePath(){
        if (isset($this->params["db-file-path"])) {
            $this->dbFilePath = $this->params["db-file-path"] ; }
    }

    protected function askForDBSave(){
        $question = 'Do you want to save a database?' ;
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBDropActions(){
        $question = 'Do you want to perform drop actions (user/db)?' ;
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBDrop(){
        $question = 'Do you want to drop a database?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBUserDrop(){
        $question = 'Do you want to drop a user?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBUserAdd(){
        $question = 'Do you want to add a user?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function verifyContinueWithNonConnectDetails(){
        $question = 'Cannot connect with these details. Sure you want to continue?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForDBHost(){
        if (isset($this->params["host"])) { return $this->params["host"] ; };
        $question = 'What\'s the Mysql Host? Enter for 127.0.0.1';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1' : $input ;
    }

    protected function askForDBUser(){
        if (isset($this->params["user"])) { return $this->params["user"] ; }
        if (isset($this->params["user-name"])) { return $this->params["user-name"] ; };
        if (isset($this->params["username"])) { return $this->params["username"] ; };
        $question = 'What\'s the application DB User?';
        $allDbUsers = array_merge(array("**CREATE NEW USER**"), $this->getDbUsers()) ;
        $user = self::askForArrayOption($question, $allDbUsers, true);
        if ($user=="**CREATE NEW USER**") {
            $question = 'Enter New User Name?';
            $user = self::askForInput($question, true); }
        return $user;
    }

    protected function askForFreeFormDBUser(){
        if (isset($this->params["user"])) { return $this->params["user"] ; }
        if (isset($this->params["user-name"])) { return $this->params["user-name"] ; };
        if (isset($this->params["username"])) { return $this->params["username"] ; };
        $question = 'What\'s the application DB User?';
        return self::askForInput($question, true);
    }

    protected function loadDBAdminUser() {
      $confUser = \Model\AppConfig::getAppVariable("admin-user") ;
      $confPass = \Model\AppConfig::getAppVariable("admin-pass") ;
      $confHost = \Model\AppConfig::getAppVariable("admin-host") ;
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
        return false ;}
    }

    protected function askForDBPass(){
        if (isset($this->params["pass"])) { return $this->params["pass"] ; }
        if (isset($this->params["password"])) { return $this->params["password"] ; }
        $question = 'What\'s the application DB Password?';
        return self::askForInput($question, true);
    }

    protected function askForDBFreeFormName(){
        if (isset($this->params["database"])) { return $this->params["database"] ; }
        if (isset($this->params["db"])) { return $this->params["db"] ; }
        $question = 'What\'s the application DB Name?'."\n";
        $question .= 'Current Db\'s are:'."\n";
        $allDbNames = $this->getDbNameList();
        foreach ($allDbNames as $onedbname) {
            $question .= $onedbname."\n"; }
        return self::askForInput($question, true);
    }

    protected function askForDBFixedName(){
        if (isset($this->params["database"])) { return $this->params["database"] ; }
        if (isset($this->params["db"])) { return $this->params["db"] ; }
        $question = 'What\'s the application DB Name?';
        $allDbNames = $this->getDbNameList();
        return self::askForArrayOption($question, $allDbNames, true);
    }

    protected function canIConnect(){
      error_reporting(0);
      $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
      error_reporting(E_ALL ^ E_WARNING);
      $loggingFactory = new \Model\Logging();
      $logging = $loggingFactory->getModel($this->params);
      if (mysqli_connect_errno($con)) {
          $logging->log("Failed to connect to MySQL: " . mysqli_connect_error(), $this->getModuleName());
          return false ;}
      else {
        mysqli_close($con);
        return true;}
    }

    protected function canAdminConnect(){
      error_reporting(0);
      $con = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
      error_reporting(E_ALL ^ E_WARNING);
      $loggingFactory = new \Model\Logging();
      $logging = $loggingFactory->getModel($this->params);
      if (mysqli_connect_errno($con)) {
          $logging->log("Admin Failed to connect to MySQL: " . mysqli_connect_error(), $this->getModuleName());
          return false ; }
      else {
        mysqli_close($con);
        return true;}
    }

    protected function getDbUsers() {
        if (class_exists("mysqli")) {
            $mysqli = new \mysqli($this->dbHost , $this->dbRootUser , $this->dbRootPass );
            $mysqliResult = $mysqli->query('SELECT User from mysql.user;');
            $users = array();
            while ($user = $mysqliResult->fetch_array()) {
                $users[] = $user[0]; } }
        else {
            $dbc = \mysql_connect($this->dbHost , $this->dbRootUser , $this->dbRootPass ) ;
            $mysqlResult = \mysql_query('SELECT User from mysql.user;', $dbc);
            $users = array();
            while ($user = \mysql_fetch_array($mysqlResult)) {
                $users[] = $user[0]; } }
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
        if (is_object($mysqliResult)) {
            while ($db = $mysqliResult->fetch_array()) {
                $dbs[] = $db[0];} }
        else {
            echo "Database Result Error: ".$mysqli->connect_error ; }
        $dbsTrimmed = array_diff($dbs, array("test", "mysql", "information_schema", "performance_schema"));
        $i=0;
        $dbsSorted = array();
        foreach ($dbsTrimmed as $db) {
            $dbsSorted[$i] = $db;
            $i++; }
        return $dbsSorted;
    }

    protected function useRootToSetUpUserAndDb(){
        $question = 'MySQL Admin Details required to setup DB/User - Continue?';
        return (isset($this->params["yes"])) ? true : self::askYesOrNo($question);
    }

    protected function askForRootDBUser(){
        if (isset($this->params["admin-user"])) { return $this->params["admin-user"] ; }
        if (isset($this->params["admin-username"])) { return $this->params["admin-username"] ; }
        $question = 'What\'s the MySQL Admin User?';
        return self::askForInput($question, true);
    }

    protected function askForRootDBPass(){
        if (isset($this->params["admin-pass"])) { return $this->params["admin-pass"] ; }
        if (isset($this->params["admin-password"])) { return $this->params["admin-password"] ; }
        $question = 'What\'s the MySQL Admin Password?';
        return self::askForInput($question, true);
    }

    protected function databaseCreator() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $logging->log('Attempting to create database (if it doesn\'t already exist) '.$this->dbName, $this->getModuleName()) ;
        $query = 'create database if not exists '.$this->dbName.';';
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Creating database '.$this->dbName.' failed', $this->getModuleName()) ;
            return false ; }
    }

    protected function databaseSaver() {
        $fp = (isset($this->params["parent-path"])) ? $this->params["parent-path"].$this->dbFilePath : $this->dbFilePath ;
        // @todo this should make the db dir if it doesn't exist
        // $comm = "mysqldump -u{$this->dbRootUser} -p{$this->dbRootPass} {$this->dbName} > {$fp} --no-create-db ; " ;
        // $this->executeAndOutput($comm, "Creating db dir...") ;
        $comm = "mysqldump -u{$this->dbRootUser} -p{$this->dbRootPass} {$this->dbName} > {$fp} --no-create-db ; " ;
        $rc = $this->executeAndGetReturnCode($comm, true, true) ;
        $trueyfalsy = ($rc["rc"]==0) ? true : false ;
        return $trueyfalsy ;
    }

    protected function userCreator() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Attempting to create/refresh database user...", $this->getModuleName()) ;
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);

        $logging->log('Attempting to grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\' identified by "{hidden password}";', $this->getModuleName()) ;
        $query = 'grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\' identified by "'.$this->dbPass.'";';
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to grant usage to user failed ', $this->getModuleName()) ;
            return false ; }

        $logging->log('Attempting to grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\' identified by "{hidden password}";', $this->getModuleName()) ;
        $query = 'grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\' identified by "'.$this->dbPass.'";';
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to grant usage to user failed ', $this->getModuleName()) ;
            return false ; }

        $logging->log('Attempting to grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\'...', $this->getModuleName()) ;
        $query = 'grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\'' ;
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to grant all privileges to user failed ', $this->getModuleName()) ;
            return false ; }

        $logging->log('Attempting to grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\'...', $this->getModuleName()) ;
        $query = 'grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\'' ;
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to grant all privileges to user failed ', $this->getModuleName()) ;
            return false ; }

    }

    private function queryOrFalse($dbc, $query){
        $res = mysqli_query($dbc, $query) ;
        if ($res == false) {
            $loggingFactory = new \Model\Logging();
            $logging = $loggingFactory->getModel($this->params);
            $logging->log("Database query failed: ".mysqli_error($dbc), $this->getModuleName()) ;
            return false ; }
        return true ;
    }

    protected function userDropper() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'DROP USER \''.$this->dbUser.'\'@\'localhost\'; ' ;
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to drop User @localhost failed', $this->getModuleName()) ;
            return false ; }
        $query = 'DROP USER \''.$this->dbUser.'\'@\'%\'; ' ;
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to drop User @% failed ', $this->getModuleName()) ;
            return false ; }
        $logging->log("Database User $this->dbUser dropped", $this->getModuleName()) ;
        return true;
    }

    protected function dropDB() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'DROP DATABASE '.$this->dbName.';';
        if ($this->queryOrFalse($dbc, $query) == false) {
            $logging->log('Attempting to Drop Database '.$this->dbName.' failed.', $this->getModuleName()) ;
            return false ; }
        $logging->log("Database $this->dbName dropped", $this->getModuleName()) ;
        return true ;
    }

    protected function sqlInstaller() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
		if (isset($this->params["parent-path"])) { $path = $this->params["parent-path"] ; }
		if (isset($this->params["guess"])) { $path = getcwd().'/' ; }
		if (!isset($path)) { $path = getcwd().'/' ; }
        $len = strlen($path) ;
        $lastChar = substr($path, ($len-1), $len);
        if ($lastChar != '/') { $path .= '/' ; }
        $sqlFileToExecute = $path.$this->dbFilePath ;
        $command  = 'mysql -h'.$this->dbHost.' -u'.$this->dbUser.' -p'.$this->dbPass.' ';
        $command .= $this->dbName.' < '.$sqlFileToExecute;
        $logging->log("Attempting to execute Database script", $this->getModuleName()) ;
        $rc = self::executeAndGetReturnCode($command, true, true);
        $state = ($rc["rc"] == 0) ? "Success" : "Failure" ;
        $logging->log("Database Script execution reports $state", $this->getModuleName()) ;
        return $rc ;
    }


}
