<?php

Namespace Controller ;

class ApacheVHostEditor extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars) ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="list") {
            $this->content["result"] = $thisModel->askWhetherToListVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        if ($action=="add") {
            $this->content["result"] = $thisModel->askWhetherToCreateVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        if ($action=="add-balancer") {
            $this->content["result"] = $thisModel->askWhetherToCreateBalancerVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        else if ($action=="remove" || $action=="rm") {
            $this->content["result"] = $thisModel->askWhetherToDeleteVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        else if ($action=="enable" || $action=="en") {
            $this->content["result"] = $thisModel->askWhetherToEnableVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        else if ($action=="disable" || $action=="dis") {
            $this->content["result"] = $thisModel->askWhetherToDisableVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        else if ($action=="enable-default" || $action=="en-default") {
            $this->content["result"] = $thisModel->askWhetherToEnableDefaultVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        else if ($action=="disable-default" || $action=="dis-default") {
            $this->content["result"] = $thisModel->askWhetherToDisableDefaultVHost();
            return array ("type"=>"view", "view"=>"ApacheVHostEditor", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid VHost Creator Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}