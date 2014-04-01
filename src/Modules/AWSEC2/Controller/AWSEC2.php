<?php

Namespace Controller ;

class AWSEC2 extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="box-add") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxAdd") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["awsResult"] = $thisModel->addBox();
            return array ("type"=>"view", "view"=>"awsAPI", "pageVars"=>$this->content); }

        if ($action=="box-remove") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxRemove") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["awsResult"] = $thisModel->askWhetherToSaveOverwriteCurrent();
            return array ("type"=>"view", "view"=>"awsAPI", "pageVars"=>$this->content); }

        if ($action=="box-destroy") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "BoxDestroy") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["awsResult"] = $thisModel->destroyBox();
            return array ("type"=>"view", "view"=>"awsAPI", "pageVars"=>$this->content); }

        if ($action=="save-ssh-key") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "SshKey") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["awsResult"] = $thisModel->askWhetherToSaveSshKey();
            return array ("type"=>"view", "view"=>"awsAPI", "pageVars"=>$this->content); }

        if ($action=="list") {
            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $this->content["awsResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"awsList", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Amazon Web Services Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}