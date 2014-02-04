<?php

Namespace Controller ;

class DigitalOcean extends Base {

    public function execute($pageVars) {

        $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Base") ;
        // if we don't have an object, its an array of errors
        if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
        $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
        if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

        $action = $pageVars["route"]["action"];

        if ($action=="overwrite-new") {

            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "OverwriteNew") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveOverwriteNew();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="overwite-current") {

            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "OverwriteCurrent") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

            $thisModel = new \Model\DigitalOceanOverwriteNew($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveOverwriteNew();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

//        if ($action=="destroy-all-droplets") {
//            $thisModel = new \Model\DigitalOcean($pageVars["route"]["extraParams"]);
//            $this->content["digiOceanResult"] = $thisModel->askWhetherToOverWriteCurrent();
//            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="save-ssh-key") {

            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "OverwriteCurrent") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }

            $thisModel = new \Model\DigitalOceanSshKey($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveSshKey();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="list") {

            $thisModel = $this->getModelAndCheckDependencies(substr(get_class($this), 11), $pageVars, "Listing") ;
            // if we don't have an object, its an array of errors
            if (is_array($thisModel)) { return $this->failDependencies($pageVars, $this->content, $thisModel) ; }
            $isDefaultAction = self::checkDefaultActions($pageVars, array(), $thisModel) ;
            if ( is_array($isDefaultAction) ) { return $isDefaultAction; }
            $thisModel = new \Model\DigitalOceanList($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"digitalOceanList", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}