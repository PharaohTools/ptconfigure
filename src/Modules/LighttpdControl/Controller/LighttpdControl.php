<?php

Namespace Controller ;

class LighttpdControl extends Base {

    public function execute($pageVars) {
        $isHelp = parent::checkForHelp($pageVars) ;
        if ( is_array($isHelp) ) {
            return $isHelp; }
        $action = $pageVars["route"]["action"];

        $LighttpdControlModel = new \Model\LighttpdControl($pageVars["route"]["extraParams"]);

        if (in_array($action, array("start", "stop", "restart"))) {
            $this->content["LighttpdControlResult"] = $LighttpdControlModel->askWhetherToCtlLighttpd($action);
            return array ("type"=>"view", "view"=>"LighttpdControl", "pageVars"=>$this->content); }

        $this->content["messages"][] = "Invalid Lighttpd Control Action";
        return array ("type"=>"control", "control"=>"index", "pageVars"=>$this->content);
    }

}