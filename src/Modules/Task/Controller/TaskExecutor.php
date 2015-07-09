<?php

Namespace Controller ;

class TaskExecutor extends Base {

    public function executeTask($pageVars, $task) {

        $sourceParams = implode(" ", $this->formatParams($pageVars["route"]["extraParams"])) ;
        $alltasks = $this->getTaskfileTasks($pageVars["route"]["extraParams"]) ;
        $alltasks = array_merge($alltasks, $this->getModuleTasks()) ;
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel(array());
        $this->content["result"] = array() ;
        foreach ($alltasks as $taskSource => $tasks) {
            $taskRunners = $tasks[$task] ;
            if ($taskRunners==null) {
                $logging->log("No {$taskSource} Task {$task} found", "Task") ;
                continue ; }
            else {
                $logging->log("{$taskSource} Task {$task} found", "Task") ; }
            foreach ($taskRunners as $taskRunnerKey => $params) {
                $taskRunner = array_keys($params);
                $taskRunner = $taskRunner[0];
                //var_dump($taskRunner) ;
                switch ($taskRunner) {
                    case "ptconfigure" :
                        if (is_string($params[$taskRunner])) { $af = $params[$taskRunner] ; }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["af"])) { $af = $params[$taskRunner]["af"] ; }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner][0])) { $af = $params[$taskRunner][0] ; }
                        else { $af = $params[$taskRunner][0] ; }
                        $logging->log("PTConfigure Task Runner", "Task") ;
                        $this->params["autopilot-file"] = $af ;
                        $auto = new \Controller\Autopilot();
                        $ep = array_merge( $this->params, $pageVars["route"]["extraParams"]) ;
                        $route = array("control" => "Autopilot", "action" => "install", "extraParams" => $ep) ;
                        $emptyPageVars = array("messages"=>array(), "route"=>$route);
                        echo "sux";
                        $ax = $auto->execute($emptyPageVars);
                        echo "nutz";
                        if (isset($ax["pageVars"]["messages"])) { $this->content["result"][] = $ax["pageVars"]["messages"] ; }
                        else if (isset($ax["pageVars"]["autoExec"])) { $this->content["result"][] = $ax["pageVars"]["autoExec"] ; }
                        else { $this->content["result"][] = "Unreadable Output" ;}
                        break ;
                    case "ptdeploy" :
                        if (is_string($params[$taskRunner])) { $af = $params[$taskRunner] ; }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["af"])) { $af = $params[$taskRunner]["af"] ; }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner][0])) { $af = $params[$taskRunner][0] ; }
                        else { $af = $params[$taskRunner]["af"] ; }
                        $logging->log("PTDeploy Task Runner","Task") ;
                        exec(PTDCOMM.'autopilot execute --af="'.$af.'" '.$sourceParams, $ex) ;
                        $this->content["result"] = "Pharaoh Deploy Task $ex";
                        break ;
                    case "log" :
                        $logging->log("Logging Task Runner","Task - Logging") ;
                        if (is_string($params[$taskRunner])) { $log = $params[$taskRunner] ; }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["log"])) { $log = $params[$taskRunner]["log"] ; }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner][0])) { $log = $params[$taskRunner][0] ; }
                        else { $log = "No Log Provided" ; }
                        $this->content["result"][] = "Logging Task Step: $log"; ;
                        $logging->log($log,"Task","Task - Logging") ;
                        break ;
                    case "method" :
                        $logging->log("Method Task Runner","Task") ;
                        if (is_string($params[$taskRunner])) {
                            // @todo do error
                        }
                        else if (is_array($params[$taskRunner]) && isset($params[$taskRunner]["method"]) && isset($params[$taskRunner]["object"])) {
                            $p["method"] = $params[$taskRunner]["method"] ;
                            $p["object"] = $params[$taskRunner]["object"] ; }
                        else if (
                                is_array($params[$taskRunner]) &&
                                isset($params[$taskRunner][0]) &&
                                isset($params[$taskRunner][1]) &&
                                is_object($params[$taskRunner][1]) ) {
                            $p["method"] = $params[$taskRunner][0] ;
                            $p["object"] = $params[$taskRunner][1] ; }
                        else {
                            $log = "No Usable method Provided" ;
                            $this->content["result"][] = $log ; //"Method Task Step: $result"; ;
                            $logging->log($log,"Task") ;
                            break ; }
                        $result = call_user_func_array(array($p["object"], $p["method"]), array($pageVars["route"]["extraParams"]));
                        if (isset($result["params"]) && is_array($result["params"])) {
                            $methodExtras = $this->formatParams($result["params"], false) ;
                            foreach ($methodExtras as $methodExtra) {
                                if (!in_array($methodExtra, $pageVars["route"]["extraParams"])) {
                                    $pageVars["route"]["extraParams"][] = $methodExtra ; } } }
                        $this->content["result"][] = $result ; //"Method Task Step: ".implode("\n", $result);
                        $logging->log($result,"Task") ;
                        break;
                    default :
                        $msg ="Undefined Task Runner '$taskRunner' requested" ;
                        $this->content["result"][] = $msg ;
                        $logging->log($msg, "Task") ;
                        break ;
            } }
            echo "do1"; }
        echo "do2";
        return array ("type"=>"view", "view"=>"Task", "pageVars"=>$this->content);
    }

    protected static function getTaskfileTasks($pageVars, $taskFile = "Taskfile") {
        if (file_exists($taskFile)) {
            try {
                require_once ($taskFile) ; }
            catch (\Exception $e) {
                echo "Error loading Taskfile $taskFile, error $e\n" ; } }
        else { return array() ; }
        $taskObject = new \Model\Taskfile(self::formatParams(array_merge(array("silent"=>true),$pageVars))) ;
        $tftasks = array() ;
        $tftasks["TaskfileTasks"] = $taskObject->getTasks() ;
        return $tftasks ;
    }

    protected function getModuleTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel($this->params);
        $logging->log("Looking for Module Tasks...", "Task");
        $tasks = array();
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            if (method_exists($info, "taskActions")) {
                $cname = get_class($info) ;
                $moduleName = substr($cname, 5, strlen($cname)-9) ;
                $moduleFactoryClass = '\Model\\'.$moduleName ;
                $moduleFactory = new $moduleFactoryClass() ;
                $taskModel = $moduleFactory->getModel($this->params, "Task");
                $tasks[$moduleName] = $taskModel->tasks ; } }
        return $tasks ;
    }


    private static function formatParams($params, $extraParams = true) {
        $newParams = array();
        foreach($params as $origParamKey => $origParamVal) {
            $newParams[] = '--'.$origParamKey.'='.$origParamVal ; }
        if ($extraParams ==true) {
            $newParams[] = '--yes' ;
            $newParams[] = "--hide-title=yes";
            $newParams[] = "--hide-completion=yes"; }
        return $newParams ;
    }

}