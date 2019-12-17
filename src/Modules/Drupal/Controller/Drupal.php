<?php

Namespace Controller ;

class Drupal extends Base {

    public function executeBuilderfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "drupal-continuous") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DrupalContinuous") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        if ($action == "drupal-efficient") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DrupalEfficient") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Builderfy Drupal Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDapperfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("drupal", "drupal7"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DapperfyDrupal") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->platform = "drupal7";
            $this->content["result"] = $thisModel->askWhetherToDapperfy();
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        if (in_array($action, array("drupal6"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DapperfyDrupal") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->platform = "drupal6";
            $this->content["result"] = $thisModel->askWhetherToDapperfy();
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        if (in_array($action, array("drupal-ptvirtualize", "drupal7-ptvirtualize"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DapperfyDrupalPTVirtualize") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->platform = "drupal7";
            $this->content["result"] = $thisModel->askWhetherToDapperfy();
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Dapperfy Drupal Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDBConfigure($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "drupal7-conf") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $drupalPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Drupal7Config") ;
            $thisModel->setPlatformVars($drupalPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "drupal7-reset") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $drupalPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Drupal7Config") ;
            $thisModel->setPlatformVars($drupalPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "drupal6-conf") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $drupalPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Drupal6Config") ;
            $thisModel->setPlatformVars($drupalPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "drupal6-reset") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $drupalPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Drupal6Config") ;
            $thisModel->setPlatformVars($drupalPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DBConfigure Drupal Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}