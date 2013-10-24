<?php

Namespace Controller ;

class NginxControl extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
            return $isHelp; }
        $action = $pageVars["route"]["action"];

        if ($action=="start") {
            $NginxControlModel = new \Model\NginxControl();
            $this->content["NginxControlResult"] = $NginxControlModel->askWhetherToStartNginx();
            return array ("type"=>"view", "view"=>"NginxControl", "pageVars"=>$this->content); }

        if ($action=="stop") {
            $NginxControlModel = new \Model\NginxControl();
            $this->content["NginxControlResult"] = $NginxControlModel->askWhetherToStopNginx();
            return array ("type"=>"view", "view"=>"NginxControl", "pageVars"=>$this->content); }

        else if ($action=="restart") {
            $NginxControlModel = new \Model\NginxControl();
            $this->content["NginxControlResult"] = $NginxControlModel->askWhetherToRestartNginx();
            return array ("type"=>"view", "view"=>"NginxControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Nginx Control Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}