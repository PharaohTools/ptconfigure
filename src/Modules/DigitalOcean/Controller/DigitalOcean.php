<?php

Namespace Controller ;

class DigitalOcean extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="overwrite-new") {
            $thisModel = new \Model\DigitalOcean();
            $this->content["shlResult"] = $thisModel->askWhetherToOverWriteNew($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="overwite-current") {
            $thisModel = new \Model\DigitalOcean();
            $this->content["shlResult"] = $thisModel->askWhetherToOverWriteCurrent($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="destroy-all-droplets") {
            $thisModel = new \Model\DigitalOcean();
            $this->content["shlResult"] = $thisModel->askWhetherToOverWriteCurrent($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

        if ($action=="save-ssh-key") {
            $thisModel = new \Model\DigitalOceanSshKey();
            $this->content["digiOceanResult"] = $thisModel->askWhetherToSaveSshKey($pageVars["route"]["extraParams"]);
            return array ("type"=>"view", "view"=>"digitalOceanAPI", "pageVars"=>$this->content); }

    }

}