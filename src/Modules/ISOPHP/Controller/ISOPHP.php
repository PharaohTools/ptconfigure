<?php

Namespace Controller ;

class ISOPHP extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
        $action = $pageVars["route"]["action"];

        if ($action=="create") {
            $this->content["result"] = $thisModel->askWhetherToCreateISOPHPApplication();
            return array ("type"=>"view", "view"=>"ISOPHP", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid ISOPHP Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeBuilderfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "isophp-continuous") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "ISOPHPContinuous") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        if ($action == "isophp-efficient") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "ISOPHPEfficient") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->params["action"] = $action ;
            $this->content["result1"] = $thisModel->askInstall();
            $this->content["result2"] = $thisModel->result;
            return array ("type"=>"view", "view"=>"builderfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Builderfy ISOPHP Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDapperfy($pageVars) {

        $action = $pageVars["route"]["action"];

        if (in_array($action, array("isophp"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DapperfyISOPHP") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->platform = "isophp";
            $this->content["result"] = $thisModel->askWhetherToDapperfy();
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        if (in_array($action, array("isophp-ptvirtualize"))) {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "DapperfyISOPHPPTVirtualize") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $thisModel->platform = "isophp";
            $this->content["result"] = $thisModel->askWhetherToDapperfy();
            return array ("type"=>"view", "view"=>"dapperfy", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Dapperfy ISOPHP Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDBConfigure($pageVars) {

        $action = $pageVars["route"]["action"];

        if ($action == "isophp-conf") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isophpPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "ISOPHPConfig") ;
            $thisModel->setPlatformVars($isophpPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToConfigureDB();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        if ($action == "isophp-reset") {
            $thisModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isophpPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "ISOPHPConfig") ;
            $thisModel->setPlatformVars($isophpPlatform);
            $thisModel->params["action"] = $action ;
            $this->content["result"] = $thisModel->askWhetherToResetDBConfiguration();
            return array ("type"=>"view", "view"=>"DBConfigure", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DBConfigure ISOPHP Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

    public function executeDBInstall($pageVars) {
        $action = $pageVars["route"]["action"];
        if ($action == "isophp-save") {
            $confModel = $this->getModelAndCheckDependencies("DBConfigure", $pageVars) ;
            // if we don't have an object, its an array of errors
            if (is_array($confModel)) { return $this->failDependencies($pageVars, $this->content, $confModel) ; }
            $isophpPlatform = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "ISOPHPConfig") ;
            $confModel->setPlatformVars($isophpPlatform);
            $thisModel = $this->getModelAndCheckDependencies("DBInstall", $pageVars) ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["dbResult"] = $thisModel->askWhetherToSaveDB($confModel);
            return array ("type"=>"view", "view"=>"DBInstall", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid DBInstall ISOPHP Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}