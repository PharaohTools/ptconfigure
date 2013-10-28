<?php

Namespace Controller ;

class ApacheControl extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
            return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="start") {
            $ApacheControlModel = new \Model\ApacheControl($pageVars["route"]["extraParams"]);
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToStartApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        if ($action=="stop") {
            $ApacheControlModel = new \Model\ApacheControl($pageVars["route"]["extraParams"]);
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToStopApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        else if ($action=="restart") {
            $ApacheControlModel = new \Model\ApacheControl($pageVars["route"]["extraParams"]);
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToRestartApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Apache Control Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}