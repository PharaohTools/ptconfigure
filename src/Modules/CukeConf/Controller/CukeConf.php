<?php

Namespace Controller ;

class CukeConf extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
          return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="configure" || $action== "config" || $action== "conf") {
            $cukeConfModel = new \Model\CukeConf();
            $this->content["cukeConfResult"] = $cukeConfModel->askWhetherToCreateCuke();
            return array ("type"=>"view", "view"=>"cukeConf", "pageVars"=>$this->content); }

        else if ($action=="reset") {
            $cukeConfModel = new \Model\CukeConf();
            $this->content["cukeConfResult"] = $cukeConfModel->askWhetherToResetCuke();
            return array ("type"=>"view", "view"=>"cukeConf", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Cuke Configuration Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}