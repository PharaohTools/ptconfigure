<?php

Namespace Controller ;

class Joomla extends Base {

    public function executeBuilderfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "joomla-continuous") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "JoomlaContinuous") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        if ($action == "joomla-efficient") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "JoomlaEfficient") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Builderfy Joomla Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDapperfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "joomla") {
            $thisModel = $this->getModelAndCheckDependencies("Dapperfy", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "JoomlaEfficient") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Dapperfy Joomla Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDBConfigure($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "joomla30-conf") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $joomlaPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Joomla30Config") ;
            $thisModel->setPlatformVars($joomlaPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "joomla30-reset") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $joomlaPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Joomla30Config") ;
            $thisModel->setPlatformVars($joomlaPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "joomla15-conf") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $joomlaPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Joomla15Config") ;
            $thisModel->setPlatformVars($joomlaPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "joomla15-reset") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $joomlaPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Joomla15Config") ;
            $thisModel->setPlatformVars($joomlaPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DBConfigure Joomla Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}