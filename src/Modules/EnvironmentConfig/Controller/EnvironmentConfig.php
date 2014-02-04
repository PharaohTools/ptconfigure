<?php

Namespace Controller ;

class EnvironmentConfig extends Base {

    public function execute($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action=="configure" || $action=="config") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Configuration") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["result"] = $thisModel->askWhetherToEnvironmentConfig();
            return array ("type"=>"view", "view"=>"environmentConfig", "pageVars"=>$this->content); }

        if ($action=="list") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["result"] = $thisModel->askWhetherToEnvironmentConfig();
            return array ("type"=>"view", "view"=>"environmentConfigList", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}