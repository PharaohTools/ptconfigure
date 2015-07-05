<?php

Namespace Model;

class JoomlaTasksAllOS extends BaseTaskfile {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Task") ;
    public $tasks = array() ;
    public $silent = null ;
    protected $actionsToMethods =
        array(
            "saveptvdb" => "performSavePTVDB",
//            "save-ptvdb" => "performSavePTVDB",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->programNameMachine = "joomlatasks"; // command and app dir name
        $this->programNameFriendly = "JoomlaTasks!"; // 12 chars
        $this->programNameInstaller = "JoomlaTasks your Environments";
        $this->setTasks();
    }

    public function performSavePTVDB() {
//        $this->setEnvironment($environmentName);
//        $this->setProvider($providerName);
//        $this->setBoxAmount($boxAmount);
//        return $this->addBox();
    }

    public function setTasks() {
        $pars = $this->params ;
        $this->tasks["saveptvdb"] =
            array(
                array("log" => "Hello, world!"),
                array("method" => "getBranchName"),
                array("method" => "getProjectName"),
                array("ptconfigure" => getcwd().DS."build".DS."config".DS."ptconfigure".DS."cleofy".DS."helloworld.php"),
            );
    }

    public function getProjectName() {
        if (is_null($this->params["silent"])) {
            if (!isset($this->params["name"])) {
                $question = 'Enter a human readable name for your project' ;
                $this->params["name"] = self::askForInput($question, true) ; } }
    }

    public function getBranchName() {
        if (is_null($this->params["silent"])) {
            if (!isset($this->params["branch"])) {
                $question = 'Enter a Branch Name to put your demo on ZZ' ;
                $this->params["branch"] = self::askForInput($question, true) ; } }
    }

}