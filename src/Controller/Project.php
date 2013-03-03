<?php

Namespace Controller ;

class Project extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];
        $projectModel = new \Model\Project();

        if ($action=="init") {
            $this->content["projectResult"] = $projectModel->askWhetherToInitializeProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="build-install.php") {
            $this->content["projectResult"] = $projectModel->askWhetherToInstallBuildInProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}