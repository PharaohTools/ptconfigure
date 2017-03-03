<?php

Namespace Controller ;

class DigitalOceanV2 extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="box-add") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxAdd") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->addBox();
            return array ("type"=>"view", "view"=>"digitalOceanV2API", "pageVars"=>$this->content); }

        if ($action=="box-remove") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxRemove") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["result"] = $thisModel->askWhetherToSaveOverwriteCurrent();
            return array ("type"=>"view", "view"=>"digitalOceanV2API", "pageVars"=>$this->content); }

        if ($action=="box-destroy") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxDestroy") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->destroyBox();
            return array ("type"=>"view", "view"=>"digitalOceanV2API", "pageVars"=>$this->content); }

        if ($action=="box-destroy-all") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxDestroyAll") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->destroyAllBoxes();
            return array ("type"=>"view", "view"=>"digitalOceanV2API", "pageVars"=>$this->content); }

        if ($action=="save-ssh-key") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "SshKey") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askWhetherToSaveSshKey();
            return array ("type"=>"view", "view"=>"digitalOceanV2API", "pageVars"=>$this->content); }

        if ($action=="list") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"digitalOceanV2List", "pageVars"=>$this->content); }

        if ($action=="loadbalancer-add") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "LoadBalancer") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askWhetherToAddLoadBalancer();
            return array ("type"=>"view", "view"=>"digitalOceanV2API", "pageVars"=>$this->content); }

        if ($action=="test") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "NodeTest") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["result"] = $thisModel->askWhetherToTestAllEnvNodes();
            return array ("type"=>"view", "view"=>"digitalOceanV2List", "pageVars"=>$this->content); }

        \Core\BootStrap::setExitCode(1);
        $this->content["messages"][] = "Action $action is not supported by ".get_class($this)." Module";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}