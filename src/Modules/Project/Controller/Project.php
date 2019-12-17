<?php

Namespace Controller ;

class Project extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array("init", "initialize"), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="container") {
            $this->content["projectResult"] = $thisModel->askWhetherToInitializeProjectContainer();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="init" || $action=="initialize") {
            $this->content["projectResult"] = $thisModel->askWhetherToInitializeProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="build-install") {
            $this->content["projectResult"] = $thisModel->askWhetherToInstallBuildInProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="new-defaults") {
            $this->content["projectResult"] = $thisModel->askWhetherToInstallBuildInProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}