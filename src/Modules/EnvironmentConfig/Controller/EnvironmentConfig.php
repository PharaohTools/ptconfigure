<?php

Namespace Controller ;

class EnvironmentConfig extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="list") {
            $this->content["result"] = $thisModel->askWhetherToListEnvironments();
            return array ("type"=>"view", "view"=>"environmentConfigList", "pageVars"=>$this->content); }

        if ($action=="list-local") {
            $this->content["result"] = $thisModel->askWhetherToListLocalEnvironments();
            return array ("type"=>"view", "view"=>"environmentConfigList", "pageVars"=>$this->content); }

        if ($action=="configure" || $action=="config") {
            $this->content["result"] = $thisModel->askWhetherToEnvironmentConfig();
            return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

        if (in_array($action, array("config-default", "configure-default") )) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "GenericAutos") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askAction($action);
            return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

        if ($action=="delete" || $action=="del") {
            $this->content["result"] = $thisModel->askWhetherToDeleteEnvironment();
            return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

        \Core\BootStrap::setExitCode(1);
        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}