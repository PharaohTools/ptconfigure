<?php

Namespace Model ;

class BaseTaskfile extends BaseLinuxApp {

    public $tasks = array() ;
    public $silent = null ;

    public function __construct($params) {
        if (isset($params["silent"]) && $params["silent"] == true ) { $this->silent = true ; }
        parent::__construct($params) ;
    }

    public function getTasks() {
        $loggingFactory = new \Model\Logging();
        $logging = $loggingFactory->getModel(array());
        if (method_exists($this, "setTasks")) {
            $logging->log("Found setTasks method, executing","Task") ;
            $this->setTasks(); }
        else {
            $logging->log("No setTasks method defined","Task") ; }
        return $this->tasks ;
    }

}