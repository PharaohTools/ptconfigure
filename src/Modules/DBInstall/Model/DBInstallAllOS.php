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
    public $dbFilePath = "db/database.sql";

    public function askWhetherToInstallDB(\Model\DBConfigure $dbConfigObject=null){
        if ($dbConfigObject!=null) { return $this->performDBInstallation($dbConfigObject); }
        else { return $this->performDBInstallation(); }
    }

    public function askWhetherToSaveDB(\Model\DBConfigure $dbConfigObject=null){
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

    protected function performDBInstallation(\Model\DBConfigure $dbConfigObject=null){
        if ($dbConfigObject!==null) {
            return $this->performDBInstallationWithConfig($dbConfigObject) ; }
        else {
            return $this->performDBInstallationWithNoConfig() ; }
    }

    protected function performDBInstallationWithConfig(\Model\DBConfigure $dbConfigObject) {
        if ( !$this->askForDBInstall() ) { return false; }
        $this->setDBFilePath();
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
        $this->doInstallHook("pre") ;
        $this->sqlInstaller();
        $this->doInstallHook("post") ;
        return "Seems Fine...";
    }

    // @todo add logging of install hook outputs
    protected function doInstallHook($hook) {
        $hook = strtolower($hook) ;
        if (in_array($hook, array("pre", "post"))) {
            if (!is_null($this->platformHooks)) {
                if (is_object($this->platformHooks)) {
                    if (method_exists($this->platformHooks, "{$hook}InstallHook")) {
                        $fullMethodName = "{$hook}InstallHook" ;
                        $this->platformHooks->$fullMethodName($this) ;
                        return ; }
                    else {
                        echo "error 1" ;
                        // no install hook specified
                        return ; } }
                else {
                    echo "error 2" ;
                    // platform hooks is set but is not an object
                    return ; } }
             else {
                 echo "error 3" ;
                // no platform hooks model
                return ; } }
         else {
             echo "error 4" ;
            // non existent hook
            return ; }
    }

    protected function performDBInstallationWithNoConfig() {
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
        $this->doInstallHook("pre") ;
        $this->sqlInstaller();
        $this->doInstallHook("post") ;
        return "Seems Fine...";
    }

    public function setPlatformDBIHooks($platformHooks = null) {
        if ($platformHooks != null) {
            $this->platformHooks = $platformHooks; }
        else if ($this->platformHooks == null) {
            $this->platformHooks = null ; }
        return;
    }

    protected function performDBSave(\Model\DBConfigure $dbConfigObject=null){
        if ($dbConfigObject!==null) {
            return $this->performDBSaveWithConfig($dbConfigObject) ; }
        else {
            return $this->performDBSaveWithNoConfig() ; }
    }

    protected function performDBSaveWithConfig(\Model\DBConfigure $dbConfigObject) {
        if ( !$this->askForDBSave() ) { return false; }
        $this->dbHost = $dbConfigObject->getProperty("dbHost");
        $this->dbUser = $dbConfigObject->getProperty("dbUser");
        $this->dbPass = $dbConfigObject->getProperty("dbPass");
        $this->dbName = $dbConfigObject->getProperty("dbName");
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; }
            if (!$this->useRootToSetUpUserAndDb() ) { return "You declined using root"; }
            $this->dbRootUser = $this->askForRootDBUser();
            $this->dbRootPass = $this->askForRootDBPass(); }
        $this->databaseSaver();
        return "Seems Fine...";
    }

    protected function performDBSaveWithNoConfig() {
        if ( !$this->askForDBSave() ) { return false; }
        $this->dbHost = $this->askForDBHost();
        $this->dbRootUser = $this->askForRootDBUser();
        $this->dbRootPass = $this->askForRootDBPass();
        $this->dbName = $this->askForDBFreeFormName();
        $canIConnect = $this->canIConnect();
        if ($canIConnect!==true) {
            if (!$this->verifyContinueWithNonConnectDetails() ) { return "Exiting due to incorrect db connection"; } }
        $this->databaseSaver();
        return "Seems Fine...";
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
        return "Seems Fine...";
    }

    protected function performAddUser() {
        if ( $this->askForDBUserAdd() ) {
            $this->dbUser = $this->askForFreeFormDBUser();
            $this->dbPass = $this->askForDBPass();
            $this->dbName = $this->askForDBFixedName();
            $this->userCreator(); }
        return "Seems Fine...";
    }

    protected function performDropUser() {
        if ( $this->askForDBUserDrop() ) {
            $this->dbUser = $this->askForDBUser();
            $this->userDropper(); }
        return "Seems Fine...";
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
        if (isset($this->params["mysql-host"])) { return $this->params["mysql-host"] ; };
        $question = 'What\'s the Mysql Host? Enter for 127.0.0.1';
        $input = self::askForInput($question) ;
        return ($input=="") ? '127.0.0.1' : $input ;
    }

    protected function askForDBUser(){
        if (isset($this->params["mysql-user"])) { return $this->params["mysql-user"] ; }
        if (isset($this->params["mysql-user-name"])) { return $this->params["mysql-user-name"] ; };
        if (isset($this->params["mysql-username"])) { return $this->params["mysql-username"] ; };
        $question = 'What\'s the application DB User?';
        $allDbUsers = array_merge(array("**CREATE NEW USER**"), $this->getDbUsers()) ;
        $user = self::askForArrayOption($question, $allDbUsers, true);
        if ($user=="**CREATE NEW USER**") {
            $question = 'Enter New User Name?';
            $user = self::askForInput($question, true); }
        return $user;
    }

    protected function askForFreeFormDBUser(){
        if (isset($this->params["mysql-user"])) { return $this->params["mysql-user"] ; }
        if (isset($this->params["mysql-user-name"])) { return $this->params["mysql-user-name"] ; };
        if (isset($this->params["mysql-username"])) { return $this->params["mysql-username"] ; };
        $question = 'What\'s the application DB User?';
        return self::askForInput($question, true);
    }

    protected function loadDBAdminUser() {
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

    protected function askForDBPass(){
        if (isset($this->params["mysql-pass"])) { return $this->params["mysql-pass"] ; }
        if (isset($this->params["mysql-password"])) { return $this->params["mysql-password"] ; }
        if (isset($this->params["mysql-user-password"])) { return $this->params["mysql-user-password"] ; }
        $question = 'What\'s the application DB Password?';
        return self::askForInput($question, true);
    }

    protected function askForDBFreeFormName(){
        if (isset($this->params["mysql-database"])) { return $this->params["mysql-database"] ; }
        if (isset($this->params["mysql-db"])) { return $this->params["mysql-db"] ; }
        $question = 'What\'s the application DB Name?'."\n";
        $question .= 'Current Db\'s are:'."\n";
        $allDbNames = $this->getDbNameList();
        foreach ($allDbNames as $onedbname) {
            $question .= $onedbname."\n"; }
        return self::askForInput($question, true);
    }

    protected function askForDBFixedName(){
        if (isset($this->params["mysql-database"])) { return $this->params["mysql-database"] ; }
        if (isset($this->params["mysql-db"])) { return $this->params["mysql-db"] ; }
        $question = 'What\'s the application DB Name?';
        $allDbNames = $this->getDbNameList();
        return self::askForArrayOption($question, $allDbNames, true);
    }

    protected function canIConnect(){
      error_reporting(0);
      $con = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
      error_reporting(E_ALL ^ E_WARNING);
      if (mysqli_connect_errno($con)) {
        return "Failed to connect to MySQL: " . mysqli_connect_error(); }
      else {
        mysqli_close($con);
        return true;}
    }

    protected function canAdminConnect(){
      error_reporting(0);
      $con = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
      error_reporting(E_ALL ^ E_WARNING);
      if (mysqli_connect_errno($con)) {
        return "Admin Failed to connect to MySQL: " . mysqli_connect_error(); }
      else {
        mysqli_close($con);
        return true;}
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
        if (isset($this->params["mysql-admin-user"])) { return $this->params["mysql-admin-user"] ; }
        $question = 'What\'s the MySQL Admin User?';
        return self::askForInput($question, true);
    }

    protected function askForRootDBPass(){
        if (isset($this->params["mysql-admin-pass"])) { return $this->params["mysql-admin-pass"] ; }
        $question = 'What\'s the MySQL Admin Password?';
        return self::askForInput($question, true);
    }

    protected function databaseCreator() {
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'create database if not exists '.$this->dbName.';';
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
    }

    protected function databaseSaver() {
        $fp = (isset($this->params["parent-path"])) ? $this->params["parent-path"].$this->dbFilePath : $this->dbFilePath ;
        $comm = "mysqldump -u{$this->dbRootUser} -p{$this->dbRootPass} {$this->dbName} > {$fp} --no-create-db ; " ;
        echo $comm."\n" ;
        $this->executeAndOutput($comm, "Database Dumping...") ;
    }

    protected function userCreator() {
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\' identified by "'.$this->dbPass.'";';
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
        $query = 'grant usage on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\' identified by "'.$this->dbPass.'";';
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
        $query = 'grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'%\'' ;
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
        $query = 'grant all privileges on '.$this->dbName.'.* to '.$this->dbUser.'@\'localhost\'' ;
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
    }

    protected function userDropper() {
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        $query = 'DROP USER \''.$this->dbUser.'\'@\'localhost\'; ' ;
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
        $query = 'DROP USER \''.$this->dbUser.'\'@\'%\'; ' ;
        echo "$query\n";
        mysqli_query($dbc, $query) or var_dump (mysqli_error($dbc));
        print "Database User $this->dbUser dropped\n";
    }

    protected function dropDB() {
        $dbc = mysqli_connect($this->dbHost, $this->dbRootUser, $this->dbRootPass);
        echo (mysqli_error($dbc));
        $query = 'DROP DATABASE '.$this->dbName.';';
        mysqli_query($dbc, $query) ;
        print "Database $this->dbName dropped\n";
    }

    protected function sqlInstaller() {
		if (isset($this->params["parent-path"])) { $path = $this->params["parent-path"] ; }
		if (isset($this->params["guess"])) { $path = getcwd()."/" ; }
		if (!isset($path)) { $path = getcwd()."/" ; }
        $len = strlen($path) ;
        $lastChar = substr($path, ($len-1), $len);
        if ($lastChar != '/') { $path .= '/' ; }
        $sqlFileToExecute = $path.$this->dbFilePath ;
        $command  = 'mysql -h'.$this->dbHost.' -u'.$this->dbUser.' -p'.$this->dbPass.' ';
        $command .= $this->dbName.' < '.$sqlFileToExecute;
        self::executeAndOutput($command);
        print "Database script executed\n";
    }


}
