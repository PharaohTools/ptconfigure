<?php

Namespace Controller ;

class NginxControl extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
            return $isHelp; }
        $action = $pageVars["route"]["action"];

        if (in_array($action, array("start", "stop", "restart"))) {
            $NginxControlModel = new \Model\NginxControl($pageVars["route"]["extraParams"]);
            $this->content["NginxControlResult"] = $NginxControlModel->askWhetherToCtlNginx($action);
            return array ("type"=>"view", "view"=>"NginxControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Nginx Control Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}