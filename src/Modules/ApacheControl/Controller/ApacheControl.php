<?php

Namespace Controller ;

class ApacheControl extends Base {

    public function execute($pageVars) {
        $this->content["route"] = $pageVars["route"];
        $this->content["messages"] = $pageVars["messages"];
        $action = $pageVars["route"]["action"];

        if ($action=="start") {
            $ApacheControlModel = new \Model\ApacheControl();
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToStartApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        if ($action=="stop") {
            $ApacheControlModel = new \Model\ApacheControl();
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToStopApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        else if ($action=="restart") {
            $ApacheControlModel = new \Model\ApacheControl();
            $this->content["ApacheControlResult"] = $ApacheControlModel->askWhetherToRestartApache();
            return array ("type"=>"view", "view"=>"ApacheControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid VHost Creator Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}