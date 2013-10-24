<?php

Namespace Controller ;

class DigitalOcean extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="overwrite-new") {
            $thisModel = new \Model\DigitalOceanOverwriteNew();
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveOverwriteNew($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="overwite-current") {
            $thisModel = new \Model\DigitalOceanOverwriteNew();
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveOverwriteNew($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="destroy-all-droplets") {
            $thisModel = new \Model\DigitalOcean();
            $this->content["digiOceanResult"] = $thisModel->askWhetherToOverWriteCurrent($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="save-ssh-key") {
            $thisModel = new \Model\DigitalOceanSshKey();
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveSshKey($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="list") {
            $thisModel = new \Model\DigitalOceanList();
            $this->content["digiOceanResult"] = $thisModel->askWhetherToListData($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanList", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Project Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);

    }

}