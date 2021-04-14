<?php

Namespace Controller ;

class Wordpress extends Base {

    public function executeBuilderfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "wordpress-continuous") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "WordpressContinuous") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        if ($action == "wordpress-efficient") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "WordpressEfficient") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Builderfy Wordpress Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDapperfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("wordpress"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DapperfyWordpress") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->platform = "wordpress";
            $this->content["result"] = $thisModel->askWhetherToDapperfy();
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Dapperfy Wordpress Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDBConfigure($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "wordpress-conf") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $wordpressPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "WordpressConfig") ;
            $thisModel->setPlatformVars($wordpressPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "wordpress-reset") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $wordpressPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "WordpressConfig") ;
            $thisModel->setPlatformVars($wordpressPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DBConfigure Wordpress Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDBInstall($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "wordpress-install" || $action == "wp-install") {
            $thisModel = $this->getModelAndCheckDependencies("DBInstall", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $wpDBInstallHooks = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "WordpressDBIHooks") ;
            $thisModel->setPlatformDBIHooks($wpDBInstallHooks);
            $thisModel->params["action"] = $action ;
            $this->content["dbResult"] = $this->content["result"] = $thisModel->askWhetherToSaveDB();
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DBInstall Wordpress Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}