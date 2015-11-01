<?php

Namespace Controller ;

class SeleniumServer extends Base {

    public function execute($pageVars) {
        $defaultExecution = $this->defaultExecution($pageVars) ;
        if (is_array($defaultExecution)) { return $defaultExecution ; }

        $action = $pageVars["route"]["action"];
        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;

        if ($action=="start") {
            $this->content["params"] = $thisModel->params;
            $this->content["appName"] = $thisModel->autopilotDefiner;
            $newAction = ucfirst($action) ;
            $this->content["result"] = $thisModel->{"ask".$newAction}();
            $this->content["module"] = $thisModel->getModuleName();
            return array ("type"=>"view", "view"=>"app".$newAction, "pageVars"=>$this->content); }

        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}