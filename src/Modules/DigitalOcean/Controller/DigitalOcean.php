<?php

Namespace Controller ;

class DigitalOcean extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="overwrite-new") {
            $thisModel = new \Model\DigitalOceanOverwriteNew($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveOverwriteNew();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="overwite-current") {
            $thisModel = new \Model\DigitalOceanOverwriteNew($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveOverwriteNew();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="destroy-all-droplets") {
            $thisModel = new \Model\DigitalOcean($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToOverWriteCurrent();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="save-ssh-key") {
            $thisModel = new \Model\DigitalOceanSshKey($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveSshKey();
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="list") {
            $thisModel = new \Model\DigitalOceanList($pageVars["route"]["extraParams"]);
            $this->content["digiOceanResult"] = $thisModel->askWhetherToListData();
            return array ("type"=>"view", "view"=>"digitalOceanList", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}