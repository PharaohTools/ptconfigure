<?php

Namespace Controller ;

class ParallelSshChild extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="execute") {
            $commandExecModel = new \Model\ParallelSshChild();
            $this->content["commandExecResult"] = $commandExecModel->askWhetherToDoParallelSshChildCommand($pageVars);
            $this->content["layout"] = "blank";
            return array ("type"=>"view", "view"=>"commandExec", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Host Editor Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}