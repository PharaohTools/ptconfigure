<?php

Namespace Model;

class ISOPHPAllOS extends Base {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;

    public function __construct($params) {
        parent::__construct($params);
        $this->dbFilePath = "db".DS."database.sql";
    }

    public function askWhetherToAddUser(){
        return $this->performAddUser();
    }

    public function askWhetherToDropUser(){
        return $this->performDropUser();
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

}
