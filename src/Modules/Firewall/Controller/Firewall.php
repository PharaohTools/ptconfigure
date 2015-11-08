<?php

Namespace Controller ;

class Firewall extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }

        $action = $pageVars["route"]["action"];

        if ($action=="help") {
            $helpModel = new \Model\Help();
            $this->content["helpData"] = $helpModel->getHelpData($pageVars["route"]["control"]);
            return array ("type"=>"view", "view"=>"help", "pageVars"=>$this->content); }

        if ($action=="install") {
            $this->content["result"] = $thisModel->askInstall();
            $this->content["appName"] = $thisModel->programNameInstaller ;
            return array ("type"=>"view", "view"=>"firewall", "pageVars"=>$this->content); }

        if ($action=="status") {
            $this->content["params"] = $thisModel->params;
            $this->content["appName"] = $thisModel->programNameInstaller;
            $this->content["appStatusResult"] = $thisModel->askStatus();
            return array ("type"=>"view", "view"=>"appStatus", "pageVars"=>$this->content); }

        if (in_array($action, array("enable", "reload", "disable", "allow", "deny", "reject", "limit", "delete", "insert", "reset", "default") )) {
            $this->content["result"] = $thisModel->askAction($action);
            $this->content["appName"] = $thisModel->programNameInstaller ;
            return array ("type"=>"view", "view"=>"firewall", "pageVars"=>$this->content); }

        \Core\BootStrap::setExitCode(1);
        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}