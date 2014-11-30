<?php

Namespace Controller ;

class TaskExecutor extends Base {

    public function executeTFTask($pageVars, $task) {

        $tasks = $this->getTaskfileTasks() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel(array());
        $taskRunners = $tasks[$task] ;
        foreach ($taskRunners as $taskRunner => $params) {
            switch ($taskRunner) {
                case "cleopatra" :
                    if (is_string($params)) { $af = $params ; }
                    else if (is_array($params) && isset($params["af"])) { $af = $params["af"] ; }
                    else if (is_array($params) && isset($params[0])) { $af = $params[0] ; }
                    else { $af = $params["af"] ; }
                    $logging->log("Cleopatra Task Runner","Task") ;
                    exec(CLEOCOMM.'autopilot execute --af="'.$af.'"', $this->content["result"]) ;
                    break ;
                case "dapperstrano" :
                    if (is_string($params)) { $af = $params ; }
                    else if (is_array($params) && isset($params["af"])) { $af = $params["af"] ; }
                    else if (is_array($params) && isset($params[0])) { $af = $params[0] ; }
                    else { $af = $params["af"] ; }
                    $logging->log("Dapperstrano Task Runner","Task") ;
                    exec(DAPPCOMM.'autopilot execute --af="'.$af.'"', $this->content["result"]) ;
                    break ;
                case "log" :
                    $logging->log("Logging Task Runner","Task") ;
                    if (is_string($params)) { $log = $params ; }
                    else if (is_array($params) && isset($params["log"])) { $log = $params["log"] ; }
                    else if (is_array($params) && isset($params[0])) { $log = $params[0] ; }
                    else { $log = "No Log Provided" ; }
                    $this->content["result"] = $logging->log($log,"Task") ;
                    break ;
                default :
                    $msg ="Undefined Task Runner $taskRunner requested" ;
                    $this->content["result"] = $msg ;
                    $logging->log($msg, "Task") ;
                    break ;
            }
        }

        return array ("type"=>"view", "view"=>"Task", "pageVars"=>$this->content);
    }

    protected function getTaskfileTaskForAction($action) {
        $tftasks = self::getTaskfileTasks();
        if (in_array($action, $tftasks)) { return new \Controller\TaskExecutor(); }
        return null ;
    }

    protected static function getTaskfileTasks($taskFile = "Taskfile") {
        if (file_exists($taskFile)) {
            try {
                require_once ($taskFile) ; }
            catch (\Exception $e) {
                echo "Error loading Taskfile $taskFile, error $e\n" ; } }
        else {
            return array() ; }
        $taskObject = new \Model\Taskfile() ;
        $tftasks = $taskObject::$tasks ;
        return $tftasks ;
    }

}