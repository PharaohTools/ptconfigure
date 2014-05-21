<?php

Namespace Controller ;

class Parallax extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];

        if ($action=="cli") {
            $this->content["cliResult"] = $thisModel->askWhetherToRunParallelCommand();
            return array ("type"=>"view", "view"=>"parallaxCli", "pageVars"=>$this->content); }

        if ($action=="child") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Child") ;
            $this->content["commandExecResult"] = $thisModel->askWhetherToDoCommandExecution($pageVars);
            $this->content["layout"] = "blank";
            return array ("type"=>"view", "view"=>"parallaxChild", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Parallax Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}