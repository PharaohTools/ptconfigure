<?php

Namespace Model;

class TaskListingUbuntu extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Listing") ;
    protected $environmentName ;
    protected $providerName ;
    protected $boxAmount ;
    protected $requestingModule ;
    protected $actionsToMethods =
        array(
            "list" => "performListing",
        ) ;

    public function __construct($params) {
        parent::__construct($params);
        $this->autopilotDefiner = "Task";
        $this->programNameMachine = "task"; // command and app dir name
        $this->programNameFriendly = "Task!"; // 12 chars
        $this->programNameInstaller = "Task your Environments";
        $this->initialize();
    }

    protected function performListing() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Listing Tasks...", $this->getModuleName());

        // tasks from Taskfile

        // tasks from other Modules

        $allTasks = array() ;
        $allTasks["modules"] ;
        $allTasks["modules"] ;
        $allTasks["modules"] ;
    }

    protected function getTaskfileTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        // tasks from Taskfile
        if (file_exists("Taskfile")) {
            $logging->log("Found Taskfile...", $this->getModuleName());
            $logging->log("Loading Taskfile...", $this->getModuleName());
            try {
                require_once ("Taskfile") ; }
            catch (new \Exception($e)) {
                $logging->log("Unable to load Taskfile, error $e...", $this->getModuleName()); } }
        else {
            $logging->log("No Taskfile found", $this->getModuleName());
            return array() ; }
        $taskObject = new \Taskfile() ;
        $tasks = $taskObject->tasks ;
        return $tasks ;
    }

    protected function getModuleTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);

        // tasks from Taskfile
        if (file_exists("Taskfile")) {
            $logging->log("Found Taskfile...", $this->getModuleName());
            $logging->log("Loading Taskfile...", $this->getModuleName());
            try {
                require_once ("Taskfile") ; }
            catch (\Exception($e)) {
                $logging->log("Unable to load Taskfile, error $e...", $this->getModuleName()); } }
        else {
            $logging->log("No Taskfile found", $this->getModuleName());
            return array() ; }

        $returns = array() ;
        $envs = \Model\AppConfig::getProjectVariable("environments");
        return (is_null($envs)) ? null : $envs ;
    }

}