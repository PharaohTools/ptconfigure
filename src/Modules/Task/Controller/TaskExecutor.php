<?php

Namespace Controller ;

class TaskExecutor extends Base {

    public function executeTFTask($pageVars, $task) {

        $tasks = $this->getTaskfileTasks() ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel(array());
        $taskRunners = $tasks[$task] ;
        foreach ($taskRunners as $taskRunnerKey => $params) {
            $taskRunner = array_keys($params);
            $taskRunner = $taskRunner[0];
            //var_dump($taskRunner) ;
            switch ($taskRunner) {
                case "cleopatra" :
                    var_dump($params[$taskRunner]) ;
                    if (is_string($params[$taskRunner])) { $af = $params[$taskRunner] ; }
                    else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["af"])) { $af = $params[$taskRunner]["af"] ; }
                    else if (is_array($params[$taskRunner]) && isset($params[$taskRunner][0])) { $af = $params[$taskRunner][0] ; }
                    else { $af = $params[$taskRunner][0] ; }
                    $logging->log("Cleopatra Task Runner","Task") ;
                    exec(CLEOCOMM.'autopilot execute --af="'.$af.'"', $this->content["result"]) ;
                    break ;
                case "dapperstrano" :
                    if (is_string($params[$taskRunner])) { $af = $params[$taskRunner] ; }
                    else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["af"])) { $af = $params[$taskRunner]["af"] ; }
                    else if (is_array($params[$taskRunner]) && isset($params[$taskRunner][0])) { $af = $params[$taskRunner][0] ; }
                    else { $af = $params[$taskRunner]["af"] ; }
                    $logging->log("Dapperstrano Task Runner","Task") ;
                    exec(DAPPCOMM.'autopilot execute --af="'.$af.'"', $this->content["result"]) ;
                    break ;
                case "log" :
                    $logging->log("Logging Task Runner","Task") ;
                    var_dump($params[$taskRunner]);
                    if (is_string($params[$taskRunner])) { $log = $params[$taskRunner] ; }
                    else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["log"])) { $log = $params[$taskRunner]["log"] ; }
                    else if (is_array($params[$taskRunner]) && isset($params[$taskRunner][0])) { $log = $params[$taskRunner][0] ; }
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