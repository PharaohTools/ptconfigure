<?php

Namespace Controller ;

class Project extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];
        $projectModel = new \Model\Project($pageVars["route"]["extraParams"]);

        if ($action=="container") {
            $this->content["projectResult"] = $projectModel->askWhetherToInitializeProjectContainer();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="init") {
            $this->content["projectResult"] = $projectModel->askWhetherToInitializeProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="build-install") {
            $this->content["projectResult"] = $projectModel->askWhetherToInstallBuildInProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        if ($action=="new-defaults") {
            $this->content["projectResult"] = $projectModel->askWhetherToInstallBuildInProject();
            return array ("type"=>"view", "view"=>"project", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}