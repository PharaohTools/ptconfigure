<?php

Namespace Model;

class TaskListingBase extends BaseLinuxApp {

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
        $allTasks = array() ;
        // tasks from Taskfile
        $allTasks["taskfile"] = $this->getTaskfileTasks() ;
        // task hook directory, autos in there should have their name automatically be a task
        $allTasks["hooks"] = $this->getHookTasks();
        // tasks from other Modules
        $allTasks["modules"] = $this->getModuleTasks() ;
        return $allTasks ;
    }

    protected function getTaskfileTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $taskFile = $this->getTaskfile() ;
        $logging->log("Looking for Taskfile ($taskFile)...", $this->getModuleName());
        if (file_exists($taskFile)) {
            $logging->log("Found Taskfile ($taskFile)...", $this->getModuleName());
            $logging->log("Loading Taskfile ($taskFile)...", $this->getModuleName());
            try {
                require_once ($taskFile) ; }
            catch (\Exception $e) {
                $logging->log("Unable to load Taskfile, error $e...", $this->getModuleName()); } }
        else {
            $logging->log("No Taskfile found", $this->getModuleName());
            return array() ; }

        $taskObject = new \Model\Taskfile(array_merge(array("silent"=>true), $this->params) ) ;
        $tftasks = $taskObject->getTasks() ;
//        $taskObject = new \Model\Taskfile($this->params) ;
//        $tasks = $taskObject::$tasks ;
        return $tftasks ;
    }

    protected function getTaskfile() {
        if (isset($this->params["Taskfile"])) { $taskFile = $this->params["Taskfile"] ; }
        else { $taskFile = "Taskfile" ; }
        return $taskFile ;
    }

    protected function getModuleTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Looking for Module Tasks...", $this->getModuleName());
        $tasks = array();
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "taskActions")) {
                $cname = get_class($info) ;
                $moduleName = substr($cname, 5, strlen($cname)-9) ;
                $moduleFactoryClass = '\Model\\'.$moduleName ;
                $moduleFactory = new $moduleFactoryClass() ;
                $taskModel = $moduleFactory->getModel($this->params, "Task");
                var_dump($taskModel);
                $tasks[$moduleName] = $taskModel->tasks ; } }
        return $tasks ;
    }

    protected function getHookTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Looking for Hook Tasks...", $this->getModuleName());
        $tasks = array();
        return $tasks ;
    }

}